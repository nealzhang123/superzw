<?php

/**
 * Main plugin class
 * */
class CUWP_Create_User_With_Password {

    /**
     * Initialization
     */
    public function __construct() {
        // show passwords
        add_action('user_new_form', array($this, 'cuwp_plug_pass'),10,1);

        // add script
        add_action('admin_print_scripts-user-new.php', array($this, 'cuwp_script'));

        // add css
        wp_enqueue_style('cuwp-style', plugins_url('css/style.css', dirname(__FILE__)));

        // listen for REQUEST
        add_action('admin_action_createuser', array($this, 'cuwp_listen'), 3);


        // remove filter that updates welcome email
        remove_filter('site_option_welcome_user_email', 'welcome_user_msg_filter');

        
    }

    /**
     * Hooks Javascript into administration
     */
    public function cuwp_script() {
        wp_enqueue_script('cuwp_main_script', plugins_url('js/cuwp.js', dirname(__FILE__)));
    }

    /**
     * Adds password input field to the register form
     */
    public function cuwp_plug_pass($user_action) {
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

    /**
     * Listens for REQUEST and fire this code instead of the core's
     */
    public function cuwp_listen() {
       
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
                    wpmu_signup_user($new_user_login, $_REQUEST['email'], array('add_to_blog' => $wpdb->blogid, 'new_role' => $_REQUEST['role']));
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

                    if (isset($_POST['noconfirmation']) && is_super_admin()) {
                        // send email with login details
                        $email = 'Dear User,'. '<br/>' .
                                'Your new account has been set up.'. '<br/>' .
                                '<br/>' .
                                'You can log in with the following information:'. '<br/>' .
                                'Username: %1$s' . '<br/>' .
                                'Password: %2$s' . '<br/>' .
                                '<br/>' .
                                '%3$s ' . '<br/>' .
                                '<br/>' .
                                'Thanks!'. '<br/>';

                        $replaced_all = sprintf(__($email, 'create-user-with-password-multisite'), sanitize_user(wp_unslash($_REQUEST['user_login']), true), sanitize_text_field($_REQUEST['cuwp_pass1']), get_admin_url());

                        $headers = 'From: ' . get_option('admin_email') . "\r\n" .
                                'Content-type: text/html; charset=utf-8\n' .
                                'Reply-To: noreply@noreply.com' . "\r\n" .
                                'X-Mailer: PHP/' . phpversion();

                        $mail = wp_mail(sanitize_text_field($_REQUEST['email']), __('Login details', 'create-user-with-password-multisite'), $replaced_all, $headers);
                        if (true == $mail) {
                            wp_redirect($redirect);
                        } else {
                            wp_die(__('We are sorry but an error has occurred whilst sending the email with the login details. Please deactivate the "Create User with Password Multisite" plugin and contact us via email to resolve this issue: plugins@mooveagency.com', 'create-user-with-password-multisite'));
                        }

                        die();
                    } else {

                        wp_redirect($redirect);
                        die();
                    }
                }
            }
        }
    }

}
