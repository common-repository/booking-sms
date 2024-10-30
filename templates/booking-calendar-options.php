<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">

    <div class="icon32"><img src="<?php echo plugins_url( 'images/logo_32px_32px.png', dirname( __FILE__ ) ); ?>" /></div>
    <h2>Booking Calendar SMS Options</h2>

    <form method="post" action="options.php">
		<?php settings_fields('clockwork_booking_calendar_admin_sms'); ?>
		<?php do_settings_sections('clockwork_booking_calendar_admin_sms'); ?>
    <?php settings_errors('clockwork_booking_calendar_admin_sms'); ?>
    <?php submit_button(); ?>
    </form>

    <form method="post" action="options.php">
		<?php settings_fields('clockwork_booking_calendar_customer_sms'); ?>
		<?php do_settings_sections('clockwork_booking_calendar_customer_sms'); ?>
    <?php settings_errors('clockwork_booking_calendar_customer_sms'); ?>
    <?php submit_button(); ?>
    </form>

    <form method="post" action="options.php">
		<?php settings_fields('clockwork_booking_calendar_reminder_sms'); ?>
		<?php do_settings_sections('clockwork_booking_calendar_reminder_sms'); ?>
    <?php settings_errors('clockwork_booking_calendar_reminder_sms'); ?>
    <?php submit_button(); ?>
    </form>

</div>
