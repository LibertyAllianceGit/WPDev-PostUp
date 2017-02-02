<?php
/*
Plugin Name: WP Developers | PostUp Integration
Plugin URI: http://wpdevelopers.com
Description: Integrate with PostUp.
Version: 1.3.1
Author: Tyler Johnson
Author URI: http://tylerjohnsondesign.com/
Copyright: Tyler Johnson
Text Domain: wpdevpostup
*/

/**
Plugin Updates
**/
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/LibertyAllianceGit/wpdev-postup',
    __FILE__,
    'wpdev-postup'
);

/**
Enqueue Admin Files
**/
function wpdev_postup_admin_files() {
        wp_enqueue_style( 'wpdev-postup-admin-css', plugin_dir_url(__FILE__) . 'inc/wpdev-postup-admin.css' );
}
add_action('admin_enqueue_scripts', 'wpdev_postup_admin_files', 20);

/**
Options Page
**/
class WPDevPostup {
	private $wpdev_postup_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpdev_postup_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'wpdev_postup_page_init' ) );
	}

	public function wpdev_postup_add_plugin_page() {
		add_menu_page(
			'WPDev Postup', // page_title
			'WPDev Postup', // menu_title
			'manage_options', // capability
			'wpdev-postup', // menu_slug
			array( $this, 'wpdev_postup_create_admin_page' ), // function
			'dashicons-email', // icon_url
			100 // position
		);
	}

	public function wpdev_postup_create_admin_page() {
		$this->wpdev_postup_options = get_option( 'wpdev_postup_option_name' ); ?>

		<div class="wrap wpdev-postup-admin">
			<h2><img src="<?php echo plugin_dir_url(__FILE__) . 'inc/wpdev_postup_logo.png'; ?>" alt="WPDevelopers | PostUp" /></h2>
			<p>To place the sign-up form, use <code>[wpdevform]</code>. You can define specific lists for each form using the attribue <code>listid="#"</code>, as well as change the placeholder and button text using <code>placeholder="Text here"</code> and <code>button="Send It"</code>. The full shortcode is: <code>[wpdevform listid="1" placeholder="Email Address" button="Subscribe"]</code></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'wpdev_postup_option_group' );
					do_settings_sections( 'wpdev-postup-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function wpdev_postup_page_init() {
		register_setting(
			'wpdev_postup_option_group', // option_group
			'wpdev_postup_option_name', // option_name
			array( $this, 'wpdev_postup_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'wpdev_postup_setting_section', // id
			'Settings', // title
			array( $this, 'wpdev_postup_section_info' ), // callback
			'wpdev-postup-admin' // page
		);

		add_settings_field(
			'login_0', // id
			'PostUp Username', // title
			array( $this, 'login_0_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);

		add_settings_field(
			'password_1', // id
			'PostUp Password', // title
			array( $this, 'password_1_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);

		add_settings_field(
			'list_id_2', // id
			'Default List ID', // title
			array( $this, 'list_id_2_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);

		add_settings_field(
			'success_message_3', // id
			'Success Message', // title
			array( $this, 'success_message_3_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);

		add_settings_field(
			'error_message_4', // id
			'Error Message', // title
			array( $this, 'error_message_4_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);

		add_settings_field(
			'input_placeholder_5', // id
			'Input Placeholder', // title
			array( $this, 'input_placeholder_5_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);

		add_settings_field(
			'submit_button_text_6', // id
			'Submit Button Text', // title
			array( $this, 'submit_button_text_6_callback' ), // callback
			'wpdev-postup-admin', // page
			'wpdev_postup_setting_section' // section
		);
	}

	public function wpdev_postup_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['login_0'] ) ) {
			$sanitary_values['login_0'] = sanitize_text_field( $input['login_0'] );
		}

		if ( isset( $input['password_1'] ) ) {
			$sanitary_values['password_1'] = sanitize_text_field( $input['password_1'] );
		}

		if ( isset( $input['list_id_2'] ) ) {
			$sanitary_values['list_id_2'] = sanitize_text_field( $input['list_id_2'] );
		}

		if ( isset( $input['success_message_3'] ) ) {
			$sanitary_values['success_message_3'] = sanitize_text_field( $input['success_message_3'] );
		}

		if ( isset( $input['error_message_4'] ) ) {
			$sanitary_values['error_message_4'] = sanitize_text_field( $input['error_message_4'] );
		}

		if ( isset( $input['input_placeholder_5'] ) ) {
			$sanitary_values['input_placeholder_5'] = sanitize_text_field( $input['input_placeholder_5'] );
		}

		if ( isset( $input['submit_button_text_6'] ) ) {
			$sanitary_values['submit_button_text_6'] = sanitize_text_field( $input['submit_button_text_6'] );
		}

		return $sanitary_values;
	}

	public function wpdev_postup_section_info() {

	}

	public function login_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_postup_option_name[login_0]" id="login_0" value="%s">',
			isset( $this->wpdev_postup_options['login_0'] ) ? esc_attr( $this->wpdev_postup_options['login_0']) : ''
		);
	}

	public function password_1_callback() {
		printf(
			'<input class="regular-text" type="password" name="wpdev_postup_option_name[password_1]" id="password_1" value="%s">',
			isset( $this->wpdev_postup_options['password_1'] ) ? esc_attr( $this->wpdev_postup_options['password_1']) : ''
		);
	}

	public function list_id_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_postup_option_name[list_id_2]" id="list_id_2" value="%s">',
			isset( $this->wpdev_postup_options['list_id_2'] ) ? esc_attr( $this->wpdev_postup_options['list_id_2']) : ''
		);
	}

	public function success_message_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_postup_option_name[success_message_3]" id="success_message_3" value="%s"><label class="wpdev-postup-label">Default: Thanks for subscribing!</label>',
			isset( $this->wpdev_postup_options['success_message_3'] ) ? esc_attr( $this->wpdev_postup_options['success_message_3']) : ''
		);
	}

	public function error_message_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_postup_option_name[error_message_4]" id="error_message_4" value="%s"><label class="wpdev-postup-label">Default: Uh oh! There was an issue with your email address.</label>',
			isset( $this->wpdev_postup_options['error_message_4'] ) ? esc_attr( $this->wpdev_postup_options['error_message_4']) : ''
		);
	}

	public function input_placeholder_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_postup_option_name[input_placeholder_5]" id="input_placeholder_5" value="%s"><label class="wpdev-postup-label">Default: Email Address</label>',
			isset( $this->wpdev_postup_options['input_placeholder_5'] ) ? esc_attr( $this->wpdev_postup_options['input_placeholder_5']) : ''
		);
	}

	public function submit_button_text_6_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_postup_option_name[submit_button_text_6]" id="submit_button_text_6" value="%s"><label class="wpdev-postup-label">Default: Subscribe</label>',
			isset( $this->wpdev_postup_options['submit_button_text_6'] ) ? esc_attr( $this->wpdev_postup_options['submit_button_text_6']) : ''
		);
	}

}
if ( is_admin() )
	$wpdev_postup = new WPDevPostup();

/**
Variable Setup
**/
$wpdevpostupopt = get_option( 'wpdev_postup_option_name' ); // Array of All Options
$postuplogin = $wpdevpostupopt['login_0']; // Login
$postuppassword = $wpdevpostupopt['password_1']; // Password
$postuplist_id = $wpdevpostupopt['list_id_2']; // List ID
$formsuccess_message = $wpdevpostupopt['success_message_3']; // Success Message
$formerror_message = $wpdevpostupopt['error_message_4']; // Error Message
$form_placeholder = $wpdevpostupopt['input_placeholder_5']; // Input Placeholder
$formbutton_text = $wpdevpostupopt['submit_button_text_6']; // Submit Button Text

global $postuplogin;
global $postuppassword;
global $postuplist_id;
global $formsuccess_message;
global $formerror_message;
global $form_placeholder;
global $formbutton_text;

/**
Enqueue JS
**/
function wpdev_postup_submit_js() {
  wp_enqueue_script('wpdev-postup-submit', plugin_dir_url(__FILE__) . 'js/wpdev-postup.js', array('jquery'), '1.0', true);

  wp_localize_script('wpdev-postup-submit', 'wpdevpostup', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce('wpdevpostupnonce'),
  ));
}
add_action( 'wp_enqueue_scripts', 'wpdev_postup_submit_js' );

/**
Ajax Function Setup - Setup for All Users
**/
add_action('wp_ajax_nopriv_wpdev_postup_add', 'wpdev_postup_add');
add_action('wp_ajax_wpdev_postup_add', 'wpdev_postup_add');

/**
Ajax Function
**/
function wpdev_postup_add() {
    // Check nonce for security purposes
    check_ajax_referer('wpdevpostupnonce', 'security');

    // Submitted email address
    $emailaddress = $_POST['email'];
    $wpdevlistid = $_POST['listid'];

    // If is ajax, continue with function
    if(defined('DOING_AJAX') && DOING_AJAX ) {

      //  Import Globals
      global $postuplogin;
      global $postuppassword;
      global $formsuccess_message;
      global $formerror_message;

      // Authentication data
      $login    = $postuplogin;
      $password = $postuppassword;

      /* Create recipient in database */
      // Recipient add URL
      $urladd = 'https://api.postup.com/api/recipient';
      // User data
      $data_add = array(
        'address'         => $emailaddress,
        'externalId'      => $emailaddress,
        'channel'         => 'E',
        'status'          => 'N'
      );
      // JSON encoded data
      $datajson_add = json_encode($data_add);
      // Open connection
      $ch_add = curl_init();
      curl_setopt($ch_add, CURLOPT_URL, $urladd);
      curl_setopt($ch_add, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch_add, CURLOPT_POSTFIELDS, $datajson_add);
      curl_setopt($ch_add, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_add, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch_add, CURLOPT_USERPWD, "$login:$password");
      curl_setopt($ch_add, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Lenght: ' . strlen($datajson_add)
      ));
      // Get post result
      $result_add = curl_exec($ch_add);
      // Close connection
      curl_close($ch_add);

      $output_add = json_decode($result_add);
      $recipientid = $output_add->recipientId;

      /* Add receipient to list */
      // Recipient add URL
      $urllist = 'https://api.postup.com/api/listsubscription';
      $data_list = array(
        'listId'         => $wpdevlistid,
        'recipientId'    => $recipientid,
        'status'         => 'NORMAL'
      );
      $datajson_list = json_encode($data_list);

      $ch_list = curl_init();
      curl_setopt($ch_list, CURLOPT_URL, $urllist);
      curl_setopt($ch_list, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch_list, CURLOPT_POSTFIELDS, $datajson_list);
      curl_setopt($ch_list, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_list, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch_list, CURLOPT_USERPWD, "$login:$password");
      curl_setopt($ch_list, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Lenght: ' . strlen($datajson_list)
      ));

      $result_list = curl_exec($ch_list);
      curl_close($ch_list);

      // Options Check
      if(!empty($formsuccess_message)) {
        $successmessage = $formsuccess_message;
      } else {
        $successmessage = 'Thanks for subscribing!';
      }

      if(!empty($formerror_message)) {
        $errormessage = $formerror_message;
      } else {
        $errormessage = 'Uh oh! There was an issue with your email address.';
      }


      $output_list = json_decode($result_list);
      if(!empty($output_list->status) && $output_list->status == 'NORMAL') {
        echo $successmessage;
      } elseif(!empty($output_list->status) && $output_list->status == 'error') {
        echo $errormessage;
      } else {
        echo 'Sorry. There appears to be an issue. Please try again.';
      }
  }
  // If not doing ajax, kill function
  die();
}

/**
Form Creation
**/
function wpdev_postup_form( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'listid' => '',
			'placeholder' => '',
			'button' => '',
		),
		$atts
	);

  // Import Globals
  global $postuplist_id;
  global $form_placeholder;
  global $formbutton_text;

  // Variable Setup
  if(!empty($atts['listid'])) {
    $formlistid = $atts['listid'];
  } elseif (!empty($postuplist_id)) {
    $formlistid = $postuplist_id;
  } else {
    $formlistid = '1';
  }

  if(!empty($atts['placeholder'])) {
    $formplaceholder = $atts['placeholder'];
  } elseif(!empty($form_placeholder)) {
    $formplaceholder = $form_placeholder;
  } else {
    $formplaceholder = 'Email Address';
  }

  if(!empty($atts['button'])) {
    $formbutton = $atts['button'];
  } elseif(!empty($formbutton_text)) {
    $formbutton = $formbutton_text;
  } else {
    $formbutton = 'Subscribe';
  }


  // Output
  $output = '
  <div id="wpdev-postup-form-cont">
  <div id="wpdev-postup-form-fields">
  <form id="wpdev-postup-form" data-list-id="' . $formlistid . '" action="">
  <input id="wpdevemailaddress" type="text" name="emailaddress" value="" placeholder="' . $formplaceholder . '" />
  <input type="submit" value="' . $formbutton . '">
  </div>
  <div id="wpdev-postup-load" style="display: none;"><?xml version="1.0" encoding="utf-8"?><svg width=\'30px\' height=\'30px\' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring-alt"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#afafb7" fill="none" stroke-width="10" stroke-linecap="round"></circle><circle cx="50" cy="50" r="40" stroke="#ffffff" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div>
  </form>
  </div>';

  return $output;

}
add_shortcode( 'wpdevform', 'wpdev_postup_form' );



/**
Get List Information
**/
/*
  $login = 'apiadmin@libertyalliance.com';
  $password = 'Dphsh+pt71';
  $url = 'https://api.postup.com/api/list/1';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
  $result = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($result);
  return $output->title;

*/
