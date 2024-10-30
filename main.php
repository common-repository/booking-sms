<?php
class Clockwork_Booking_Calendar_Plugin extends Clockwork_Plugin {

  protected $plugin_name = 'Booking Calendar';
  protected $language_string = 'clockwork_booking_calendar';
  protected $prefix = 'clockwork_booking_calendar';
  protected $folder = '';
  
  protected $forms = array();
  
  /**
   * Constructor: setup callbacks and plugin-specific options
   *
   * @author James Inman
   */
  public function __construct() {
    parent::__construct();
    
    // Set the plugin's Clockwork SMS menu to load the contact forms
    $this->plugin_callback = array( $this, 'clockwork_booking_calendar' );
    $this->plugin_dir = basename( dirname( __FILE__ ) );
    
    add_action( 'wpdev_new_booking', array( $this, 'new_booking' ), 1, 5 ); 
    add_action( 'send_reminder', array( $this, 'send_reminder_notification' ), 10, 3 );
  }
  
  /**
   * Setup the admin navigation
   *
   * @return void
   * @author James Inman
   */
  public function setup_admin_navigation() {
    parent::setup_admin_navigation();
  }
  
  /**
   * Setup HTML for the admin <head>
   *
   * @return void
   * @author James Inman
   */
  public function setup_admin_head() {
    echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'css/clockwork.css', __FILE__ ) . '">';
  }
  
  /**
   * Register the settings for this plugin
   *
   * @return void
   * @author James Inman
   */
  public function setup_admin_init() {
    parent::setup_admin_init();
    
    // Register admin SMS settings
    register_setting( 'clockwork_booking_calendar_admin_sms', 'clockwork_booking_calendar_admin_sms' );
    add_settings_section( 'clockwork_booking_calendar_admin_sms', 'Admin SMS Notifications', array( $this, 'admin_settings_text' ), 'clockwork_booking_calendar_admin_sms' );
    add_settings_field( 'enabled', 'Enabled', array( $this, 'admin_enabled_input' ), 'clockwork_booking_calendar_admin_sms', 'clockwork_booking_calendar_admin_sms' );
    add_settings_field( 'mobile', 'Mobile Number', array( $this, 'admin_mobile_number_input' ), 'clockwork_booking_calendar_admin_sms', 'clockwork_booking_calendar_admin_sms' );
    add_settings_field( 'message', 'Message', array( $this, 'admin_message_input' ), 'clockwork_booking_calendar_admin_sms', 'clockwork_booking_calendar_admin_sms' );
    
    // Register customer SMS settings
    register_setting( 'clockwork_booking_calendar_customer_sms', 'clockwork_booking_calendar_customer_sms', array( $this, 'validate_customer_options' ) );
    add_settings_section( 'clockwork_booking_calendar_customer_sms', 'Confirmation SMS Notifications', array( $this, 'customer_settings_text' ), 'clockwork_booking_calendar_customer_sms' );
    add_settings_field( 'customer_enabled', 'Enabled', array( $this, 'customer_enabled_input' ), 'clockwork_booking_calendar_customer_sms', 'clockwork_booking_calendar_customer_sms' );
    add_settings_field( 'confirmation_message', 'Message', array( $this, 'confirmation_message_input' ), 'clockwork_booking_calendar_customer_sms', 'clockwork_booking_calendar_customer_sms' );
    
    // Register reminder SMS settings
    register_setting( 'clockwork_booking_calendar_reminder_sms', 'clockwork_booking_calendar_reminder_sms', array( $this, 'validate_reminder_options' ) );
    add_settings_section( 'clockwork_booking_calendar_reminder_sms', 'Reminder SMS Notifications', array( $this, 'reminder_settings_text' ), 'clockwork_booking_calendar_reminder_sms' );
    add_settings_field( 'enabled', 'Enabled', array( $this, 'reminder_enabled_input' ), 'clockwork_booking_calendar_reminder_sms', 'clockwork_booking_calendar_reminder_sms' );
    add_settings_field( 'schedule', 'Schedule', array( $this, 'reminder_schedule_input' ), 'clockwork_booking_calendar_reminder_sms', 'clockwork_booking_calendar_reminder_sms' );
    add_settings_field( 'confirmation_message', 'Message', array( $this, 'reminder_message_input' ), 'clockwork_booking_calendar_reminder_sms', 'clockwork_booking_calendar_reminder_sms' );
  }
    
  /**
   * Main text for the admin settings
   *
   * @return void
   * @author James Inman
   */
  public function admin_settings_text() {
    echo '<p>You can choose to receive a text message whenever a new booking is added.</p>';
  }
  
  /**
   * Main text for the customer settings
   *
   * @return void
   * @author James Inman
   */
  public function customer_settings_text() {
    echo __( '<p>You can choose to send a confirmation text message to everyone who makes a booking. This requires a field called <kbd>phone</kbd> in your booking form.</p>' );
  }
  
  /**
   * Main text for the reminder settings
   *
   * @return void
   * @author James Inman
   */
  public function reminder_settings_text() {
    echo __( '<p>You can choose to send a reminder text message to everyone who makes a booking before the booking takes place. This requires a field called <kbd>phone</kbd> in your booking form.</p>' );
  }
  
  /**
   * Input box for the mobile number
   *
   * @return void
   * @author James Inman
   */
  public function admin_mobile_number_input() {
    $options = get_option( 'clockwork_booking_calendar_admin_sms' );
    if( isset( $options['mobile'] ) ) {
      echo '<input id="clockwork_booking_calendar_admin_sms_admin_mobile" name="clockwork_booking_calendar_admin_sms[mobile]" size="40" type="text" value="' . $options['mobile'] . '" />';
    } else {
      echo '<input id="clockwork_booking_calendar_admin_sms_admin_mobile" name="clockwork_booking_calendar_admin_sms[mobile]" size="40" type="text" value="" />';    
    }
		echo ' <p class="description">' . __('International format, starting with a country code e.g. 447123456789. You can enter multiple mobile numbers seperated by a comma.', 'clockwork_booking_calendar') . '</p>';
  }
  
  /**
   * Whether admin settings are enabled
   *
   * @return void
   * @author James Inman
   */
  public function admin_enabled_input() {
    $options = get_option( 'clockwork_booking_calendar_admin_sms' );
    if( isset( $options['enabled'] ) && ( $options['enabled'] == true ) ) {
      echo '<input id="clockwork_booking_calendar_admin_sms_enabled" name="clockwork_booking_calendar_admin_sms[enabled]" type="checkbox" checked="checked" value="1" />';
    } else {
      echo '<input id="clockwork_booking_calendar_admin_sms_enabled" name="clockwork_booking_calendar_admin_sms[enabled]" type="checkbox" value="1" />';    
    }
  }
  
  /**
   * Input box for the mobile number
   *
   * @return void
   * @author James Inman
   */
  public function customer_enabled_input() {
    $options = get_option( 'clockwork_booking_calendar_customer_sms' );
    if( isset( $options['enabled'] ) && ( $options['enabled'] == true ) ) {
      echo '<input id="clockwork_booking_calendar_customer_sms_enabled" name="clockwork_booking_calendar_customer_sms[enabled]" type="checkbox" checked="checked" value="1" />';
    } else {
      echo '<input id="clockwork_booking_calendar_customer_sms_enabled" name="clockwork_booking_calendar_customer_sms[enabled]" type="checkbox" value="1" />';    
    }
    
  }
    
  /**
   * Form field for the message to send to administrators
   *
   * @return void
   * @author James Inman
   */
  public function admin_message_input() {
    $options = get_option( 'clockwork_booking_calendar_admin_sms' );
    $placeholder = 'A new booking has been added on ' . get_bloginfo('name') . ' for %datetime%.';

    if( isset( $options['message'] ) ) {
      echo '<textarea id="clockwork_booking_calendar_admin_sms_message" name="clockwork_booking_calendar_admin_sms[message]" rows="3" cols="45" placeholder="' . $placeholder . '">' . $options['message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_booking_calendar_admin_sms_message" name="clockwork_booking_calendar_admin_sms[message]" rows="3" cols="45" placeholder="' . $placeholder . '"></textarea>';  
    }
    echo ' <p class="description">' . __( 'The following tags can be used in your SMS message: <kbd>%datetime%</kbd>', 'clockwork_booking_calendar' ) . '</p>';
  }  
    
  /**
   * Form field for the confirmation message to send to customers
   *
   * @return void
   * @author James Inman
   */
  public function confirmation_message_input() {
    $options = get_option( 'clockwork_booking_calendar_customer_sms' );
    $placeholder = 'Your booking at ' . get_bloginfo('name') . ' for %datetime% is now confirmed.';

    if( isset( $options['confirmation_message'] ) ) {
      echo '<textarea id="clockwork_booking_calendar_customer_sms_confirmation" name="clockwork_booking_calendar_customer_sms[confirmation_message]" rows="3" cols="45" placeholder="' . $placeholder . '">' . $options['confirmation_message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_booking_calendar_customer_sms_confirmation" name="clockwork_booking_calendar_customer_sms[confirmation_message]" rows="3" cols="45" placeholder="' . $placeholder . '"></textarea>';  
    }
    echo ' <p class="description">' . __( 'The following tags can be used in your SMS message: <kbd>%datetime%</kbd>', 'clockwork_booking_calendar' ) . '</p>';
  }  
  
  /**
   * Validate the customer options
   *
   * @param array $opt 
   * @return void
   * @author James Inman
   */
  public function validate_customer_options( $opt ) {
    return $opt;
  }
  
  /**
   * Validate the reminder options
   *
   * @param array $opt 
   * @return void
   * @author James Inman
   */
  public function validate_reminder_options( $opt ) {
    return $opt;
  }
  
  /**
   * Whether reminder settings are enabled
   *
   * @return void
   * @author James Inman
   */
  public function reminder_enabled_input() {
    $options = get_option( 'clockwork_booking_calendar_reminder_sms' );
    if( isset( $options['enabled'] ) && ( $options['enabled'] == true ) ) {
      echo '<input id="clockwork_booking_calendar_reminder_sms_enabled" name="clockwork_booking_calendar_reminder_sms[enabled]" type="checkbox" checked="checked" value="1" />';
    } else {
      echo '<input id="clockwork_booking_calendar_reminder_sms_enabled" name="clockwork_booking_calendar_reminder_sms[enabled]" type="checkbox" value="1" />';    
    }
  }
      
  /**
   * Form field for the message to send for reminders
   *
   * @return void
   * @author James Inman
   */
  public function reminder_message_input() {
    $options = get_option( 'clockwork_booking_calendar_reminder_sms' );
    $placeholder = 'Don\'t forget your booking at ' . get_bloginfo('name') . ' for %datetime%.';

    if( isset( $options['message'] ) ) {
      echo '<textarea id="clockwork_booking_calendar_reminder_sms_message" name="clockwork_booking_calendar_reminder_sms[message]" rows="3" cols="45" placeholder="' . $placeholder . '">' . $options['message'] . '</textarea>';
    } else {
      echo '<textarea id="clockwork_booking_calendar_reminder_sms_message" name="clockwork_booking_calendar_reminder_sms[message]" rows="3" cols="45" placeholder="' . $placeholder . '"></textarea>';  
    }
    echo ' <p class="description">' . __( 'The following tags can be used in your SMS messages: <kbd>%datetime%</kbd>', 'clockwork_booking_calendar' ) . '</p>';
  }
  
  /**
   * Form field for the reminder message to send
   *
   * @return void
   * @author James Inman
   */
  public function reminder_schedule_input() {
    $options = get_option( 'clockwork_booking_calendar_reminder_sms' );
    
    if( isset( $options['length'] ) ) {
      $input = '<input id="clockwork_booking_calendar_reminder_sms_length" name="clockwork_booking_calendar_reminder_sms[length]" size="2" type="text" value="' . $options['length'] . '" />';
    } else {
      $input = '<input id="clockwork_booking_calendar_reminder_sms_length" name="clockwork_booking_calendar_reminder_sms[length]" size="2" type="text" value="" />';    
    }
    
    $select = '<select id="clockwork_booking_calendar_reminder_sms_freq" name="clockwork_booking_calendar_reminder_sms[freq]" size="1">';
    if( isset( $options['freq'] ) && $options['freq'] == '3600' ) { 
      $select .= '<option value="3600" selected="selected">hours</option>';    
    } else {
      $select .= '<option value="3600">hours</option>';    
    }
    if( isset( $options['freq'] ) && $options['freq'] == '86400' ) { 
      $select .= '<option value="86400" selected="selected">days</option>';    
    } else {
      $select .= '<option value="86400">days</option>';    
    }
    $select .= '</select>';
    
    echo 'Send a reminder message ' . $input . ' ' . $select . ' before the event.';
  }
  
  /**
   * Called when a new booking is created
   *
   * @param string $booking_id ID of the new booking
   * @param string $bktype Booking resource ID
   * @param string $dates Dates of the booking
   * @param string $time_array Time array in specific format: array( $start_time, $end_time )
   * @param string $sdform Booking form fields in specific booking format
   * @return void
   * @author James Inman
   */
  public function new_booking( $booking_id, $bktype, $dates, $time_array, $sdform ) {
    $options = get_option( 'clockwork_options' );
    $clockwork = new WordPressClockwork( $options['api_key'] );
    
    // Send admin notification
    $options = @array_merge( $options, get_option( 'clockwork_booking_calendar_admin_sms' ) );    
    if( isset( $options['enabled'] ) && $options['enabled'] == true ) {    
      // Setup the message
      $message = str_replace( '%datetime%', $this->prepare_date_string( $dates, $time_array ), $options['message'] );      
      $mobile = explode( ',', $options['mobile'] );
      
      // Send the message
      try {
        $messages = array();
        foreach( $mobile as $to ) {
          $messages[] = array( 'from' => $options['from'], 'to' => $to, 'message' => $message );
        }
        $result = $clockwork->send( $messages );
      } catch( ClockworkException $e ) {
        $result = "Error: " . $e->getMessage();
      } catch( Exception $e ) { 
        $result = "Error: " . $e->getMessage();
      }
      
      error_log( var_export( $result, true ), 0 );
    }
    
    // Send customer notification
    $options = get_option( 'clockwork_options' );
    $options = @array_merge( $options, get_option( 'clockwork_booking_calendar_customer_sms' ) );
    
    preg_match( '/phone[0-9]+\^([\d\s\+]+)/', $sdform, $matches );
    if( isset( $matches[1] ) && isset( $options['enabled'] ) && $options['enabled'] == true ) {
      // Setup the message
      $message = str_replace( '%datetime%', $this->prepare_date_string( $dates, $time_array ), $options['confirmation_message'] );
      
      // Send the message
      try {
        $messages = array();
        $messages[] = array( 'from' => $options['from'], 'to' => $matches[1], 'message' => $message );
        $result = $clockwork->send( $messages );
      } catch( ClockworkException $e ) {
        $result = "Error: " . $e->getMessage();
      } catch( Exception $e ) { 
        $result = "Error: " . $e->getMessage();
      }
      
      error_log( var_export( $result, true ), 0 );
    }  
    
    // Setup the reminder SMS notification
    $options = get_option( 'clockwork_options' );
    $options = @array_merge( $options, get_option( 'clockwork_booking_calendar_reminder_sms' ) );
    if( $options['enabled'] == true && isset( $matches[1] ) ) {
      $offset = ( intval( $options['freq'] ) * intval( $options['length'] ) );
      
      if( !empty( $dates ) && !empty( $time_array ) ) {
        $date_array = explode( ', ', $dates );
        $parse = $date_array[0] . ' ' . $time_array[0][0] . ':' . $time_array[0][1];
      } elseif( !empty( $dates ) && empty( $time_array ) ) {
        $parse = $date_array[0];
      }
      
      $parse = str_replace( '.', '-', $parse ) . ' ' . date_default_timezone_get();
      $time = ( strtotime( $parse ) - $offset );
      
      wp_schedule_single_event( $time, 'send_reminder', array( 'to' => $matches[1], 'dates' => $dates, 'times' => $time_array ) );
    }  
  }
  
  /**
   * Send a reminder SMS notification
   *
   * @param string $to 
   * @param string $dates 
   * @param string $times 
   * @return void
   * @author James Inman
   */
  public function send_reminder_notification( $to, $dates, $times ) {
    $options = get_option( 'clockwork_options' );
    $options = array_merge( $options, get_option( 'clockwork_booking_calendar_reminder_sms' ) );

    $clockwork = new WordPressClockwork( $options['api_key'] );
    
    if( isset( $to ) && $options['enabled'] == true ) {
      // Setup the message
      $message = str_replace( '%datetime%', $this->prepare_date_string( $dates, $times ), $options['message'] );
      
      // Send the message
      try {
        $messages = array();
        $messages[] = array( 'from' => $options['from'], 'to' => $to, 'message' => $message );
        $result = $clockwork->send( $messages );
      } catch( ClockworkException $e ) {
        $result = "Error: " . $e->getMessage();
      } catch( Exception $e ) { 
        $result = "Error: " . $e->getMessage();
      }
      
      error_log( $result, 0 );
    }
  }
  
  /**
   * Return a string referring to dates and times a booking is made
   *
   * @param string $dates 
   * @param string $times 
   * @return void
   * @author James Inman
   */
  public function prepare_date_string( $dates, $times ) {
    $str = '';
    
    $dates = explode( ', ', $dates );
    
    for( $i = 0; $i < count($dates); $i++ ) {
      $datestr = str_replace( '.', '-', $dates[$i] );     
      $timestamp = strtotime( $datestr );
      $date = date( get_option( 'booking_date_format' ), $timestamp );
      $str .= $date; 
    }
    
    if( !empty( $times ) ) {
      $str .= "\n";
    }
    
    if( isset( $times[0] ) && $times[0] != '00:00:00' ) {
      $str .= "\n";
      $timestamp = strtotime( $times[0][0] . ':' . $times[0][1] );
      $time = date( get_option( 'booking_time_format' ), $timestamp );
      $str .= 'Start time: ' . $time;      
    } 
    
    if( isset( $times[1] ) && $times[1] != '00:00:00' ) {
      $str .= "\n";
      $timestamp = strtotime( $times[1][0] . ':' . $times[1][1] );
      $time = date( get_option( 'booking_time_format' ), $timestamp );
      $str .= 'Start time: ' . $time;      
    }
    
    return $str;
  }
  
  /**
   * Function to provide a callback for the main plugin action page
   *
   * @return void
   * @author James Inman
   */
  public function clockwork_booking_calendar() {
    $this->render_template( 'booking-calendar-options' );
  }
  
  /**
   * Check if username and password have been entered
   *
   * @return void
   * @author James Inman
   */
  public function get_existing_username_and_password() { }
  
}

$cp = new Clockwork_Booking_Calendar_Plugin();