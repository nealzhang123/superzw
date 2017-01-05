<?php   
/*
Plugin Name: cm 社交內容發佈平台
Plugin URI: http://c-m.hk
Description: cm 社交內容發佈平台
Version: 1.0
Author: Sherlock
Author URI: http://c-m.hk
License: GPL
*/

if (!session_id())
    session_start();
	
require_once plugin_dir_path(__FILE__) . 'config.php';
require_once CM_SOCIAL_API_PATH . 'Facebook/autoload.php';
require_once CM_SOCIAL_API_PATH . 'twitter/autoload.php';
require_once CM_SOCIAL_API_PATH . 'google-api-php/vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class CMSocial {
	//so_type ,1是facebook, 2是twitter
	public $so_db;
	public $user_name, $is_mobile;
	public $fb, $tw, $gg;
	public $account_types;

	function __construct() {

		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	        spl_autoload_register( 'CMSocial::autoloadClass', true, true);
	    } else {
	        spl_autoload_register( 'CMSocial::autoloadClass' );
	    }

	    if( wp_is_mobile() )
	    	$this->is_mobile = true;

	    date_default_timezone_set('PRC');

	    $this->so_db = new Social_Db;

	    $current_user = wp_get_current_user();
		$this->user_name = $current_user->display_name;

		$this->fb = new Facebook\Facebook([
			'app_id' => FACEBOOK_APP_ID,
			'app_secret' => FACEBOOK_APP_SECRET,
			'default_graph_version' => 'v2.4',
		]);

		$this->tw = new TwitterOAuth( TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET );

		$this->gg = new Google_Client();

		$this->account_types = array(
			1 => 'fackbook',
			2 => 'twitter',
			);
	}

	//自动加载类
	function autoloadClass( $classname ) {
        $filename = plugin_dir_path( __FILE__ ) . 'class/class_' . strtolower( $classname ) . '.php';
	    if ( is_readable( $filename ) ) {
	        require_once $filename;
	    }
    }

    function date_format( $date ) {
    	return date( 'Y-m-d', strtotime( $date ) );
    }

    //插件激活时候，建立相关数据表
    function plugin_activation() {
    	$plugin = new CMSocial;

    	$plugin->so_db->init_tables();
    }

    //加载各个视图
    function load_view( $folder, $view ){
    	if( !empty( $folder ) )
    		$filename = $folder . '/' . $view . '-view.php';
    	else
    		$filename = $view . '-view.php';

    	require( CM_SOCIAL_VIEW_PATH . $filename );
    }

    function cm_social_stat_results() {
    	echo 'no content';
    }

    function cm_social_account_manage() {
    	global $user_ID;

		$helper = $this->fb->getRedirectLoginHelper();

		$tw_request_token = $this->tw->oauth( 'oauth/request_token' );

		$redirect_uri = admin_url( 'admin.php?page=cm_social_account_manage' );
		
		$this->gg->setAuthConfig( CM_SOCIAL_API_PATH . 'google-api-php/googleApi.json' );
		$this->gg->setRedirectUri( $redirect_uri );
		//$this->gg->addScope( Google_Service_Drive::DRIVE );
		$this->gg->setScopes( [
	        "https://www.googleapis.com/auth/plus.me",
	        "https://www.googleapis.com/auth/plus.stream.read",
	        "https://www.googleapis.com/auth/plus.stream.write",
	        "https://www.googleapis.com/auth/userinfo.email",
	        "https://www.googleapis.com/auth/userinfo.profile",
	        "https://www.googleapis.com/auth/plus.media.upload",
	        "https://www.googleapis.com/auth/plus.circles.read",
	        "https://www.googleapis.com/auth/plus.circles.write",
	    ] );
	    $this->gg->setIncludeGrantedScopes( true );
	    $this->gg->setAccessType( "offline" );
		//Google_Service_Drive::DRIVE

		//權限認證返回
		$this->data['error'] = false;

		//for facebook auth request
		if( !empty( $_GET['action'] ) && $_GET['action'] == 'auth' && $_GET['type'] == 'fb' ) {
			try {
			    $accessToken = $helper->getAccessToken();
			    // OAuth 2.0 client handler
				$oAuth2Client = $this->fb->getOAuth2Client();

				// Exchanges a short-lived access token for a long-lived one
				$accessTokenObj = $oAuth2Client->getLongLivedAccessToken( $accessToken );
				$expire_obj = $accessTokenObj->getExpiresAt();
				if( !empty( $expire_obj ) ) {
					$expire_time = $expire_obj->format('Y-m-d H:i:s');
				}else{
					$expire_time = '';
				}

				//$expire_time = date( 'Y-m-d H:m:s', $accessToken_expire );
				$accessTokenLong = $accessTokenObj->getValue();

			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				$this->data['error'] = true;
				$this->data['msg']   = $e->getMessage();
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				$this->data['error'] = true;
				$this->data['msg']   = $e->getMessage();
			}

			if ( isset( $accessToken ) ) {
				try{
					$res = $this->fb->get( '/me?fields=name,email', $accessToken )->getDecodedBody();

					$data = array( 
						'user_id'        => $user_ID,
						'so_type'		 => 1,
						'account_email'  => $res['email'], 
						'account_name'   => $res['name'], 
						'account_id'     => $res['id'], 
						'account_access' => $accessTokenLong, 
						'account_status' => 1,
						'verify_time'    => current_time( 'mysql' )
						);
					// echo '<pre>';print_r($data);echo '</pre>';

					if( !empty( $expire_time ) ) {
						$data['expire_time'] = $expire_time;
					}

					$id = $this->so_db->update_account_info( $data );

					if( !empty( $id ) ) {
						$this->data['msg'] = '操作成功';
					}else{
						$this->data['error'] = true;
						$this->data['msg']   = '無法連接到數據庫,操作失敗';
					}
				}catch( Facebook\Exceptions\FacebookResponseException $e ){
					$this->data['error'] = true;
					$this->data['msg']   = $e->getMessage();
				}
			}
		}

		//for twitter auth request
		if( !empty( $_GET['oauth_token'] ) ) {
			$oauth_token    = $_GET['oauth_token'];
			$oauth_verifier = $_GET['oauth_verifier'];

			try {
				$twitter = new TwitterOAuth( TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token, $oauth_verifier );
				$access = $twitter->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

				$connection = new TwitterOAuth( TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access['oauth_token'], $access['oauth_token_secret'] );
				
				$result = $connection->get( 'account/verify_credentials', ['include_email' => true] );
				//echo '<pre>';print_r($result);echo '</pre>';

				$data = array( 
					'user_id'            => $user_ID,
					'so_type'            => 2,
					'account_name'       => $result->name, 
					'account_id'         => $result->id, 
					'oauth_token'        => $access['oauth_token'], 
					'oauth_token_secret' => $access['oauth_token_secret'],
					'verify_time'        => current_time( 'mysql' )
					);
				// echo '<pre>';print_r($data);echo '</pre>';

				$id = $this->so_db->update_account_info( $data );

				if( !empty( $id ) ) {
					$this->data['msg'] = '操作成功';
				}else{
					$this->data['error'] = true;
					$this->data['msg']   = '無法連接到數據庫,操作失敗';
				}
			} catch (Exception $e) {
				$this->data['error'] = true;
				$this->data['msg']   = '操作失敗';
			}
		}

		//for google+ api request authorize
		if ( isset( $_GET['code'] ) ) {
		    $token = $this->gg->fetchAccessTokenWithAuthCode( $_GET['code'] );
		    $this->gg->setAccessToken( $token );
		    $id_token = $this->gg->getAccessToken();
		    echo '<pre>test3';print_r($id_token);echo '</pre>';

		    $httpClient = $this->gg->authorize();

// make an HTTP request
$response = $httpClient->get('https://www.googleapis.com/plus/v1/people/me');
echo '<pre>test4';print_r($response);echo '</pre>';
		    // $ticket = $this->gg->verifyIdToken();
		    // echo '<pre>test1';print_r($ticket);echo '</pre>';
		     // if ( $ticket ) {
		     //   $data = $ticket->getAttributes();
		     //   echo '<pre>test2';print_r($data);echo '</pre>';
		     // }
		     exit();
		}
		//get the list of all type social account
		//$accounts_list = $this->so_db->get_social_accounts();
		$accounts_list = new Social_Accounts_List();
		$accounts_list->prepare_items();
		//echo '<pre>test';print_r($_SESSION);echo '</pre>';

		$this->data['accounts_list'] = $accounts_list;

		$permissions = ['email','manage_pages','publish_actions','user_posts']; // optional'email, user_posts '
		$fb_auth_url = $helper->getLoginUrl(  admin_url( 'admin.php?page=cm_social_account_manage&action=auth&type=fb' ), $permissions );
		$this->data['fb_auth_url'] = $fb_auth_url;

		//twitter
		$tw_auth_url = $this->tw->url( 'oauth/authorize', array( 'oauth_token' => $tw_request_token['oauth_token'] ) );
		$this->data['tw_auth_url'] = $tw_auth_url;

		//google
		$gg_auth_url = $this->gg->createAuthUrl();
		$this->data['gg_auth_url'] = $gg_auth_url;

    	$this->load_view( '', 'social-account-list' );
    }


    function cm_social_public_post() {
    	$fb_accounts = $this->so_db->get_social_accounts_by_type( 1 );
    	$tw_accounts = $this->so_db->get_social_accounts_by_type( 2 );

    	//提交表單內容
    	if( !empty( $_POST ) && wp_verify_nonce( $_POST['social_public'], 'social_public' ) ) {
    		if( !empty( $_FILES ) && $_FILES['social_upload']['size'] > 10000000 ){
    			$this->data['error'] = true;
				$this->data['msg']   = '文件大小超過10M';

    			$this->load_view( '', 'social-public-post' );
    			return;
    		}

			$title   = $_POST['title'];
			$content = $_POST['content'];
    		

    		//如果選中的有facebook賬號
    		if( !empty( $_POST['facebook'] ) ) {
    			$data = array(
    				'message' => $content
    				);

    			//根據上傳的內容,選擇api行為
    			$has_file = false;

    			if( !empty( $_FILES ) && !empty( $_FILES['social_upload']['name'] ) ) {
    				$endpoint = 'photos';
    				$has_file = true;

    			}elseif( !empty( $_POST['video'] ) ){
    				$endpoint = 'feed';
    				$data['link'] = $_POST['video'];

    			}else{
    				$endpoint = 'feed';
    			}


	    		foreach ( $_POST['facebook'] as $fb_id ) {
	    			$fb_account = $this->so_db->get_social_accounts_by_id( $fb_id );

	    			if( $has_file ) {
		    			$data['source'] = $this->fb->fileToUpload( $_FILES['social_upload']['tmp_name'] );
		    		}
	    			try {
		    			$response = $this->fb->post( '/' . $fb_account['account_id'] . '/' . $endpoint, $data, $fb_account['account_access'] );
						$this->data['msg']   = '發佈成功';

					} catch(Facebook\Exceptions\FacebookResponseException $e) {
						$this->data['error'] = true;
						$this->data['msg']   = 'Graph returned an error: ' . $e->getMessage();

					} catch(Facebook\Exceptions\FacebookSDKException $e) {
						$this->data['error'] = true;
						$this->data['msg']   = 'Facebook SDK returned an error: ' . $e->getMessage();
					}
	    		}
    		}


    		//如果選中的有twitter賬號
    		if( !empty( $_POST['twitter'] ) ) {
    			$data = array(
    				'status' => $content
    				);

    			//根據上傳的內容,選擇api行為
    			$has_file = false;

    			if( !empty( $_FILES ) && !empty( $_FILES['social_upload']['name'] ) ) {
    				$data['status'] = mb_substr( $content, 0, 130, 'utf-8' );
    				$has_file = true;

    			}elseif( !empty( $_POST['video'] ) ){
    				$video_length = strlen( $_POST['video'] );
    				$max = 130 - $video_length;
    				$left_content = mb_substr( $content, 0, $max, 'utf-8' );

    				$data['status'] = $left_content . $_POST['video'];
    			}else{
    				$data['status'] = mb_substr( $content, 0, 130, 'utf-8' );
    			}


	    		foreach ( $_POST['twitter'] as $tw_id ) {
	    			$tw_account = $this->so_db->get_social_accounts_by_id( $tw_id );
	    			$tw_conn = new TwitterOAuth( TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $tw_account['oauth_token'], $tw_account['oauth_token_secret'] );

	    			if( $has_file ) {
	    				$media = $tw_conn->upload( 'media/upload', ['media' => $_FILES['social_upload']['tmp_name']] );
		    			$data['media_ids'] = $media->media_id_string;
		    		}
	    			try {
		    			$result = $tw_conn->post( 'statuses/update', $data );
						$this->data['msg']   = '發佈成功';
					} catch( Exception $e ) {
						$this->data['error'] = true;
						$this->data['msg']   = 'twitter 發佈失敗';
					}
	    		}
    		}
    	}

    	 //  		$fb_accounts = $this->so_db->get_social_accounts_by_type( 1 );

   //  		foreach ( $fb_accounts as $fb_account ) {
   //  			$test= $this->fb->sendRequest(
   //  				'POST',
   //  				'/' . $fb_account['account_id'] . '/feed',
   //  				array(
   //  			    	'message' => $content
   //  			  	),
   //  			  	$fb_account['account_access']
   //  			);
   //  		}

    	$this->data['fb_accounts'] = $fb_accounts;
    	$this->data['tw_accounts'] = $tw_accounts;

    	$this->load_view( '', 'social-public-post' );
    }
}

function cm_init_social() {  
	$plugin = new CMSocial;

    add_menu_page( __('CM內容發佈'), __('CM內容發佈'), "manage_categories", 'cm_social', array( $plugin, 'cm_social_stat_results' ), 'dashicons-welcome-view-site' );
    add_submenu_page( 'cm_social', __('動態消息'), __('動態消息'), 'manage_categories', 'cm_social' ,array( $plugin, 'cm_social_stat_results' ) );
    
    add_submenu_page( 'cm_social', __('賬號管理'), __('賬號管理'), 'manage_categories', 'cm_social_account_manage' ,array( $plugin, 'cm_social_account_manage' ) );
    add_submenu_page( 'cm_social', __('內容發佈'), __('內容發佈'), 'manage_categories', 'cm_social_public_post' ,array( $plugin, 'cm_social_public_post' ) );
    // add_submenu_page( 'hospital_track', __('产前队列管理'), __('产前队列管理'), "manage_options", 'pre_birth_manage', array( $plugin, 'pre_birth_manage' ) );
    // add_submenu_page( 'hospital_track', __('出生队列管理'), __('出生队列管理'), "manage_options", 'four_track_manage', array( $four_track, 'four_track_manage' ) );
    // add_submenu_page( 'hospital_track', __('文件导入'), __('文件导入'), "manage_options", 'muti_import', array( $plugin, 'muti_import' ) );
    // add_submenu_page( 'hospital_track', __('预警手机邮件设置'), __('预警手机邮件设置'), 'manage_options', 'email_phone', array( $plugin, 'email_and_phone' ) );
    
    // //add_submenu_page( 'hospital_track', __('数据调试'), __('数据调试'), 'manage_network', 'hos_test' ,array( $plugin, 'hos_test' ) );
    // add_action( 'wp_dashboard_setup', array( $plugin,'load_widgets' ) );
    // add_submenu_page( null, null, null, 'manage_options', 'track_export_file', array( $plugin, 'track_export_file' ) );
    // add_submenu_page( null, null, null, 'manage_options', 'four_track_export', array( $four_track, 'four_track_export' ) );

    //add_submenu_page( 'hospital_track', __('成员管理'), __('成员管理'), "manage_options", 'user_manage', array( $plugin, 'user_manage' ) );

  //   if( !current_user_can('manage_options') ){
		// remove_menu_page('hospital_track');
  //   }

}

function cm_social_load_scripts( $hook ) {
	$page_arr = array( 'cm_social', 'cm_social_account_manage', 'cm_social_public_post' );

	if( !in_array( $_GET['page'], $page_arr ) && 'index.php' != $hook ) 
		return;

	//load js files
	wp_enqueue_script( 'jquery-script', 'https://cdn.bootcss.com/jquery/2.2.1/jquery.min.js' );
    wp_enqueue_script( 'jquery-ui-script', 'https://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.js' );
    wp_enqueue_script( 'jquery-form-script', 'https://cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js' );
    wp_enqueue_script( 'swiper-min-script', 'https://cdn.bootcss.com/Swiper/3.3.1/js/swiper.jquery.min.js' );
    wp_enqueue_script( 'ui-widget-script', 'https://cdn.bootcss.com/blueimp-file-upload/9.10.4/vendor/jquery.ui.widget.js' );
	//wp_enqueue_script( 'bootstrap-min-script', 'http://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js' );
	wp_enqueue_script( 'pnotify-script', 'http://cdn.bootcss.com/pnotify/3.0.0/pnotify.min.js' );
	wp_enqueue_script( 'sweet-script', 'https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js' );

	wp_enqueue_script( 'cm-social-script', CM_SOCIAL_JS_URL . 'cm_social.js' );
	wp_enqueue_script( 'blueimp-mix-script', CM_SOCIAL_JS_URL . 'uploadPreview.js' );
	//wp_enqueue_script( 'cm-social-upload-main-script', CM_SOCIAL_JS_PATH . 'cm_social_upload_main.js' );
	//wp_enqueue_script( 'cm-social-fileupload-mix-script', CM_SOCIAL_JS_PATH . 'jquery.fileupload.mix.js' );


	//load css files
    wp_enqueue_style( 'jquery-ui-css', 'https://cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.css' );
    wp_enqueue_style( 'swiper-min-css', 'https://cdn.bootcss.com/Swiper/3.3.0/css/swiper.min.css' );
    //wp_enqueue_style( 'bootstrap-min-css', 'http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.css' );
	wp_enqueue_style( 'pnotify-min-style', 'https://cdn.bootcss.com/pnotify/3.0.0/pnotify.min.css' );
	wp_enqueue_style( 'pnotify-brighttheme-style', 'https://cdn.bootcss.com/pnotify/3.0.0/pnotify.brighttheme.min.css' );
	wp_enqueue_style( 'sweet-style', 'https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css' );
	wp_enqueue_style( 'font-awesome-style', 'https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css' );
	//wp_enqueue_style( 'jquery-fileupload-mix-css', CM_SOCIAL_CSS_URL . 'jquery.fileupload.mix.css' );
	//wp_enqueue_style( 'cm-social-css', CM_SOCIAL_CSS_URL . 'cm-social.css' );
	
}

add_action( 'admin_menu', 'cm_init_social' );

register_activation_hook( __FILE__, array( 'CMSocial', 'plugin_activation' ) );

// add_action( 'wp_ajax_hos-muti-upload-ajax', array( 'HospitalTrack','hospital_muti_upload_ajax' ) );

// add_action( 'wp_ajax_hospital-track-ignore-ajax', array( 'HospitalTrack','hospital_track_ignore_ajax' ) );
// add_action( 'wp_ajax_hospital-track-search-ajax', array( 'HospitalTrack','hospital_track_search_ajax' ) );
// add_action( 'wp_ajax_hospital-pre-birth-edit-list-ajax', array( 'HospitalTrack','hospital_pre_birth_edit_list_ajax' ) );
// add_action( 'wp_ajax_hospital-pre-birth-edit-save-ajax', array( 'HospitalTrack','hospital_pre_birth_edit_save_ajax' ) );
// add_action( 'wp_ajax_hospital-pre-birth-get-track-result-ajax', array( 'HospitalTrack','hospital_pre_birth_get_track_result_ajax' ) );
// add_action( 'wp_ajax_hospital-pre-birth-track-result-import-ajax', array( 'HospitalTrack','hospital_pre_birth_track_result_import_ajax' ) );

add_action('admin_enqueue_scripts', 'cm_social_load_scripts');

//add_action('admin_menu','mytest');

function mytest() {
	//remove_menu_page( 'edit.php' );
}