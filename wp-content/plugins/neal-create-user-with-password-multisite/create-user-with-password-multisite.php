<?php

/*
  Plugin Name: 多站点创建用户时可添加用户密码
  Plugin URI: http://www.mooveagency.com
  Description: Add ability to register user with password on WP multisite installation.
  Version: 1.0.5.
  Author: Jakub Glos
  Author URI: http://www.mooveagency.com
  Text Domain: create-user-with-password-multisite
 */

global $signup_password_form_printed;
//------------------------------------------------------------------------//
//---Config---------------------------------------------------------------//
//------------------------------------------------------------------------//
$signup_password_use_encryption = 'yes'; //Either 'yes' OR 'no'
$signup_password_form_printed = 0;

// no need on cron job
if (defined('DOING_CRON') || isset($_GET['doing_wp_cron'])) {
    return;
}

add_filter('add_signup_meta', 'stacktech_wpmu_signup_password_meta_filter',99);
add_filter('random_password', 'stacktech_wpmu_signup_password_random_password_filter',10,1);
// show passwords
add_action('user_new_form', 'cuwp_plug_pass', 10, 1);

// add script
add_action('admin_print_scripts-user-new.php', 'cuwp_script');

// add css
//wp_enqueue_style('cuwp-style', plugins_url('css/style.css', __FILE__));

// listen for REQUEST
add_action('admin_action_createuser', 'cuwp_listen', 3);


// remove filter that updates welcome email
remove_filter('site_option_welcome_user_email', 'welcome_user_msg_filter');

function cuwp_script() {
        wp_enqueue_script('cuwp_main_script', plugins_url('js/cuwp.js', __FILE__));
}

function cuwp_plug_pass($user_action) {
  if( $user_action == 'add-new-user'){
      ?>
      <table class="form-table hook-pass">
          <tbody>
          <input type="hidden" name="cuwp_security" value="cuwp" />
          <tr class="form-field form-required user-pass1-wrap">
              <th scope="row">
                  <label for="pass1">
                      <?php _e( 'Password' ); ?>
                      <span class="description hide-if-js"><?php _e( '(required)' ); ?></span>
                  </label>
              </th>
              <td>
                  <input class="hidden" value=" " /><!-- #24364 workaround -->
                  <button type="button" class="button button-secondary wp-generate-pw hide-if-no-js"><?php _e( 'Show password' ); ?></button>
                  <div class="wp-pwd hide-if-js">
                      <?php $initial_password = wp_generate_password( 24 ); ?>
                      <span class="password-input-wrapper">
                          <input type="password" name="cuwp_pass1" id="pass1" class="regular-text" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
                      </span>
                      <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
                          <span class="dashicons dashicons-hidden"></span>
                          <span class="text"><?php _e( 'Hide' ); ?></span>
                      </button>
                      <button type="button" class="button button-secondary wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change' ); ?>">
                          <span class="text"><?php _e( 'Cancel' ); ?></span>
                      </button>
                      <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
                  </div>
                  <p><span class="description"><?php _e( 'A password reset link will be sent to the user via email.' ); ?></span></p>
              </td>
          </tr>
          <tr class="form-field form-required user-pass2-wrap hide-if-js">
              <th scope="row"><label for="pass2"><?php _e( 'Repeat Password' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
              <td>
              <input name="cuwp_pass2" type="password" id="pass2" autocomplete="off" />
              </td>
          </tr>
          <tr class="pw-weak">
              <th><?php _e( 'Confirm Password' ); ?></th>
              <td>
                  <label>
                      <input type="checkbox" name="pw_weak" class="pw-checkbox" />
                      <?php _e( 'Confirm use of weak password' ); ?>
                  </label>
              </td>
          </tr>
         <!--  <tr class="form-field form-required">
              <th scope="row"><label for="cuwp_pass1"><?php _e('Password', 'create-user-with-password-multisite'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
              <td><input name="cuwp_pass1" type="password" id="pass1" autocomplete="off" /><input class="hidden" value=" " /></td>
          </tr>

          <tr class="form-field form-required">
              <th scope="row"><label for="cuwp_pass2"><?php _e('Repeat Password', 'create-user-with-password-multisite'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
              <td><input name="cuwp_pass2" type="password" id="pass2" autocomplete="off" /></td>
          </tr>

          <tr>
              <td>
                  <div class="pass-error">

                      <?php _e("Passwords do not match.", 'create-user-with-password-multisite'); ?>
                  </div>
              </td>
          </tr> -->
          <tr>
              <th scope="row"><label for="noconfirmation"><?php _e('Skip Confirmation Email') ?></label></th>
              <td><label for="noconfirmation"><input type="checkbox" name="noconfirmation" id="noconfirmation" value="1" <?php checked( $new_user_ignore_pass ); ?> /> <?php _e( 'Add the user without sending an email that requires their confirmation.' ); ?></label></td>
          </tr>
      </tbody>
      </table>
      <?php
  }
}

function cuwp_listen() {
       
  if (isset($_REQUEST['cuwp_security']) && 'cuwp' == $_REQUEST['cuwp_security']) {
      
      if(sanitize_text_field($_REQUEST['cuwp_pass1']) != sanitize_text_field($_REQUEST['cuwp_pass2'])){
          wp_die(__('Passwords do not match.', 'create-user-with-password-multisite'));
      }
      
      global $wpdb;
      check_admin_referer('create-user', '_wpnonce_create-user');
      if (!current_user_can('create_users'))
          wp_die(__('Cheatin&#8217; uh?'));

      if (!is_multisite()) {
          $user_id = edit_user();

          if (is_wp_error($user_id)) {
              $add_user_errors = $user_id;
          } else {
              if (current_user_can('list_users'))
                  $redirect = 'users.php?update=add&id=' . $user_id;
              else
                  $redirect = add_query_arg('update', 'add', 'user-new.php');
              wp_redirect($redirect);
              die();
          }
      } else {
          $user_details = wpmu_validate_user_signup($_REQUEST['user_login'], $_REQUEST['email']);
          if (is_wp_error($user_details['errors']) && !empty($user_details['errors']->errors)) {
              $add_user_errors = $user_details['errors'];
          } else {
              /**
               * Filter the user_login, also known as the username, before it is added to the site.
               *
               * @since 2.0.3
               *
               * @param string $user_login The sanitized username.
               */
              $new_user_login = apply_filters('pre_user_login', sanitize_user(wp_unslash($_REQUEST['user_login']), true));
              if (isset($_POST['noconfirmation']) ) {
                  add_filter('wpmu_signup_user_notification', '__return_false'); // Disable confirmation email
              }
              $add_meta = apply_filters( 'add_signup_meta', array() );
              $meta = array('add_to_blog' => $wpdb->blogid, 'new_role' => $_REQUEST['role']); 
              $meta = array_merge($add_meta, $meta);
              wpmu_signup_user($new_user_login, $_REQUEST['email'], $meta);
              if (isset($_POST['noconfirmation']) ) {
                  $key = $wpdb->get_var($wpdb->prepare("SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $_REQUEST['email']));
                  wpmu_activate_signup($key);
                  $redirect = add_query_arg(array('update' => 'addnoconfirmation'), 'user-new.php');
              } else {
                  $redirect = add_query_arg(array('update' => 'newuserconfirmation'), 'user-new.php');
              }

              // set password for user
              $user = get_user_by('email', sanitize_email($_REQUEST['email']));

              wp_set_password(sanitize_text_field($_REQUEST['cuwp_pass1']), $user->ID);

              // if (isset($_POST['noconfirmation']) && is_super_admin()) {
              //     // send email with login details
              //     $email = 'Dear User,'. '<br/>' .
              //             'Your new account has been set up.'. '<br/>' .
              //             '<br/>' .
              //             'You can log in with the following information:'. '<br/>' .
              //             'Username: %1$s' . '<br/>' .
              //             'Password: %2$s' . '<br/>' .
              //             '<br/>' .
              //             '%3$s ' . '<br/>' .
              //             '<br/>' .
              //             'Thanks!'. '<br/>';

              //     $replaced_all = sprintf(__($email, 'create-user-with-password-multisite'), sanitize_user(wp_unslash($_REQUEST['user_login']), true), sanitize_text_field($_REQUEST['cuwp_pass1']), get_admin_url());

              //     $headers = 'From: ' . get_option('admin_email') . "\r\n" .
              //             'Content-type: text/html; charset=utf-8\n' .
              //             'Reply-To: noreply@noreply.com' . "\r\n" .
              //             'X-Mailer: PHP/' . phpversion();

              //     $mail = wp_mail(sanitize_text_field($_REQUEST['email']), __('Login details', 'create-user-with-password-multisite'), $replaced_all, $headers);
              //     if (true == $mail) {
              //         wp_redirect($redirect);
              //     } else {
              //         wp_die(__('We are sorry but an error has occurred whilst sending the email with the login details. Please deactivate the "Create User with Password Multisite" plugin and contact us via email to resolve this issue: plugins@mooveagency.com', 'create-user-with-password-multisite'));
              //     }

              //     die();
              // } else {

                  wp_redirect($redirect);
                  die();
              // }
          }
      }
  }
}
// fire in administration only
// if (is_admin() ) {
//     add_filter('add_signup_meta', 'stacktech_wpmu_signup_password_meta_filter',99);
//     add_filter('random_password', 'stacktech_wpmu_signup_password_random_password_filter',10,1);FB::info($signup_password_use_encryption,'key');
//     require_once( 'php/cuwp.php' );
//     $mdu = new CUWP_Create_User_With_Password();
// }

/**
 * Install
 */
function cuwp_activate() {
    // store old message in option
    $old_message = get_site_option('welcome_user_email');
    update_option('cuwp_welcome_user_email', $old_message);

    // set new message
    $text_var = 'Dear User, \n' .
            'Thank you for the registration. Please check the email address provided for login details. \n' .
            '--The Team @ SITE_NAME \n';
    $text = __($text_var, 'create-user-with-password-multisite');

    update_site_option('welcome_user_email', $text);
}

register_activation_hook(__FILE__, 'cuwp_activate');

/**
 * Deactivation
 */
function cuwp_deactivate() {
    // set old message back
    $old_message = get_option('cuwp_welcome_user_email');
    update_site_option('welcome_user_email', $old_message);

    // delete option with the message
    delete_option('cuwp_welcome_user_email');
}

register_deactivation_hook(__FILE__, 'cuwp_deactivate');



if ( ! function_exists( 'stacktech_signup_password_encrypt' ) ) {
  function stacktech_signup_password_encrypt() {
    return stacktech_wpmu_signup_password_encrypt();
  }
}

function stacktech_wpmu_signup_password_encrypt($data) {
  if(!isset($chars))
  {
  // 3 different symbols (or combinations) for obfuscation
  // these should not appear within the original text
  $sym = array('∂', '•xQ', '|');

  foreach(range('a','z') as $key=>$val)
  $chars[$val] = str_repeat($sym[0],($key + 1)).$sym[1];
  $chars[' '] = $sym[2];

  unset($sym);
  }

  // encrypt
  $data = base64_encode(strtr($data, $chars));
  return $data;

}

if ( ! function_exists( 'stacktech_signup_password_decrypt' ) ) {
  function stacktech_signup_password_decrypt() {
    return stacktech_wpmu_signup_password_decrypt();
  }
}

function stacktech_wpmu_signup_password_decrypt($data) {
  if(!isset($chars))
  {
  // 3 different symbols (or combinations) for obfuscation
  // these should not appear within the original text
  $sym = array('∂', '•xQ', '|');

  foreach(range('a','z') as $key=>$val)
  $chars[$val] = str_repeat($sym[0],($key + 1)).$sym[1];
  $chars[' '] = $sym[2];

  unset($sym);
  }

  // decrypt
  $charset = array_flip($chars);
  $charset = array_reverse($charset, true);

  $data = strtr(base64_decode($data), $charset);
  unset($charset);
  return $data;
}



if ( ! function_exists( 'stacktech_signup_password_meta_filter' ) ) {
  function stacktech_signup_password_meta_filter() {
    return stacktech_wpmu_signup_password_meta_filter();
  }
}

function stacktech_wpmu_signup_password_meta_filter($meta) {
 
  global $signup_password_use_encryption;
  $cuwp_pass1 = isset($_POST['cuwp_pass1'])?$_POST['cuwp_pass1']:'';
  if ( !empty( $cuwp_pass1 ) ) {
    if ( $signup_password_use_encryption == 'yes' ) {
      $cuwp_pass1 = stacktech_wpmu_signup_password_encrypt($cuwp_pass1);
    }
    $add_meta = array('password' => $cuwp_pass1 );
    $meta = array_merge($add_meta, $meta);
  }
  return $meta;
}

if ( ! function_exists( 'stacktech_signup_password_random_password_filter' ) ) {
  function stacktech_signup_password_random_password_filter() {
    return stacktech_wpmu_signup_password_random_password_filter();
  }
}

function stacktech_wpmu_signup_password_random_password_filter($password) {
  global $wpdb, $signup_password_use_encryption;
  if ( isset($_GET['key']) && ! empty($_GET['key']) ) {
    $key = $_GET['key'];
  } else if ( isset($_POST['key']) && ! empty($_POST['key']) ) {
    $key = $_POST['key'];
  }
  if ( !empty($_POST['cuwp_pass1']) ) {
    $password = $_POST['cuwp_pass1'];
  } else if ( !empty( $key ) ) {
    $signup = $wpdb->get_row(
      $wpdb->prepare( "SELECT * FROM $wpdb->signups WHERE activation_key = '%s'",
        $key
      )
    );
    if ( ! ( empty($signup) || $signup->active ) ) {
      //check for password in signup meta
      $meta = maybe_unserialize($signup->meta);
      if ( !empty( $meta['password'] ) ) {
        if ( $signup_password_use_encryption == 'yes' ) {
          $password = stacktech_wpmu_signup_password_decrypt($meta['password']);
        } else {
          $password = $meta['password'];
        }
        unset( $meta['password'] );
        $meta = maybe_serialize( $meta );
        $wpdb->update(
          $wpdb->signups,
          array( 'meta' => $meta ),
          array( 'activation_key' => $key ),
          array( '%s' ),
          array( '%s' )
        );
      }

    }
  }

  return $password;
}
