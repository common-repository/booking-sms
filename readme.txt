=== Booking Calendar - Clockwork SMS ===
Author: Clockwork
Website: http://www.clockworksms.com/platforms/wordpress/?utm_source=wordpress&utm_medium=plugin&utm_campaign=bookingcalendar
Contributors: mediaburst, martinsteel, mediaburstjohnc
Tags: SMS, Clockwork, Clockwork SMS, Mediaburst, Bookings, Booking, Booking Calendar, Appointments, Booking SMS, Booking Notifications
Text Domain: booking_sms
Requires at least: 3.0.0
Tested up to: 4.4.0
Stable tag: 1.1.3
License: MIT

Works with the Booking Calendar plugin to send SMS notifications when somebody
makes a booking, using the Clockwork API.

== Description ==

Allows you to send SMS notifications to your site administrator when a booking is made,
to visitors to confirm their booking and a reminder text message a specified interval
before the booking takes place.

You need a [Clockwork SMS account](http://www.clockworksms.com/platforms/wordpress/?utm_source=wordpress&utm_medium=plugin&utm_campaign=bookingcalendar) and some Clockwork credit to use this plugin.

= Requires =

* Wordpress 3 or higher

* [Booking Calendar](http://wordpress.org/extend/plugins/booking/) 4 or higher

* A [Clockwork SMS account](http://www.clockworksms.com/platforms/wordpress/?utm_source=wordpress&utm_medium=plugin&utm_campaign=bookingcalendar)

== Installation ==

1. Upload the 'booking-sms' directory to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your Clockwork API key in the 'Clockwork Options' page under 'Clockwork SMS'
4. Set your SMS options for Booking Calendar

== Frequently Asked Questions ==

= What is a Clockwork API key? =

To send SMS you will need to sign up for a Clockwork SMS account
and purchase some SMS credit. When you sign up you'll be given an API key.

= Can I send to multiple mobile numbers? =

Yes, separate each mobile number with a comma.

= What format should the mobile number be in? =

All mobile numbers should be entered in international format without a
leading + symbol or international dialing prefix.

For example a UK number should be entered 447123456789, and a Republic
of Ireland number would be entered 353870123456.

== Screenshots ==

1. SMS settings

== Changelog ==

= 1.1.3 =
* Remove old branding

= 1.1.2 =
* Security Hardening

= 1.1.0 =
* Fix XSS Vulnerability

= 1.0.5 =

* Trim whitespace when storing API keys
* Fix test message for Invoice accounts
* Tested with WordPress 4.4

= 1.0.4 =

* Fix a couple of coding standards issues
* Clarify license (MIT)

= 1.0.3 =

* WordPress 3.8 compatibility.

= 1.0.2 =

* Added standard Clockwork "test" functionality.

= 1.0.1 =

* Fixed an issue with GMT/BST offsets.

= 1.0.0 =

* Initial release.
