<?php
/**
* 
*/
class Social_Db {

	function init_tables() {
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

		//社交平台賬號表
		$cm_social_account = $wpdb->prefix . 'cm_social_account';

		//so_type ,1是facebook, 2是twitter
		$sql = "CREATE TABLE $cm_social_account  (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11),
			so_type smallint,
			account_id varchar(255),
			account_name varchar(255),
			account_email varchar(255),
			account_access varchar(255),
			oauth_token varchar(255),
			oauth_token_secret varchar(255),
			account_status smallint,
			verify_time timestamp,
			expire_time timestamp,
            PRIMARY KEY  (id,user_id,so_type)
		)";

		dbDelta( $sql );
	}

	/**
	 * @param  $data
	 * @return $id
	 */
	function update_account_info( $data ) {
		global $wpdb;

		$cm_social_account = $wpdb->prefix . 'cm_social_account';

		$sql = 'select id from ' . $cm_social_account . ' where user_id=' . $data['user_id'] . ' AND account_id="' . $data['account_id'] . '"';

		$id = $wpdb->get_var( $sql );

		if( $id > 0 ) {
			$wpdb->update( $cm_social_account, $data, array( 'id' => $id ) );
		}else{
			$wpdb->insert( $cm_social_account, $data );
			$id = $wpdb->insert_id;
		}

		return $id;
	}

	function get_social_accounts() {
		global $wpdb, $user_ID;

		$cm_social_account = $wpdb->prefix . 'cm_social_account';

		$sql = 'select * from ' . $cm_social_account . ' where user_id=' . $user_ID . ' order by so_type';

		$results = $wpdb->get_results( $sql, 'ARRAY_A' ); 

		return $results;
	}

	function get_social_accounts_by_type( $type ) {
		global $wpdb, $user_ID;

		$cm_social_account = $wpdb->prefix . 'cm_social_account';

		$sql = 'select * from ' . $cm_social_account . ' where user_id=' . $user_ID . ' and so_type=' . $type;

		$results = $wpdb->get_results( $sql, 'ARRAY_A' ); 

		return $results;
	}

	function get_social_accounts_by_id( $id ) {
		global $wpdb, $user_ID;

		$cm_social_account = $wpdb->prefix . 'cm_social_account';

		$sql = 'select * from ' . $cm_social_account . ' where id=' . $id;

		$results = $wpdb->get_row( $sql, 'ARRAY_A' ); 

		return $results;
	}
}
?>