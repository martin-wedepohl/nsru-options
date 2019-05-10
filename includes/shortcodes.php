<?php

/**
 * All the required short codes used by the North Shore Round Up Options Plugin
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */


defined( 'ABSPATH' ) or die( '' );

/**
 * Returns a span that AJAX can populate with the required Round Up Year.
 *
 * This is needed to use Wordpress Caching Plugins
 *
 * Will a span for the year of either the current Round Up year or the year of the first Round Up.
 *
 * @param  array $atts {
 *     Optional array of arguments.
 *     @param string type Type of year. Default 'current'. Accepts 'current', 'first'
 * }
 *
 * @return int Year of the Round Up.
 */
function nsru_get_year_shortcode( $atts ) {

    $a = shortcode_atts( array( 'type' => 'current' ), $atts, 'nsru_get_year' );

    if( 'first' === $a['type'] ) {
        return '<span class="first_year"></span>';
    } elseif ( 'current' === $a['type'] ) {
        return '<span class="current_year"></span>';
    }

} // nsru_get_year_shortcode
add_shortcode( 'nsru_get_year', 'nsru_get_year_shortcode' );

/**
 * Return a span that AJAX can populated with the number of the round up with a suffix.
 *
 * This is required when using Wordpress Chaching Plugins
 *
 * @return string Round Up number
 */
function nsru_get_annual_shortcode() {

    return '<span class="annual"></span>';

} // nsru_get_annual_shortcode
add_shortcode( 'nsru_get_annual', 'nsru_get_annual_shortcode' );

/**
 * Returns a span that AJAX can populate with the number of days to the round up or if it is on or if it is over.
 *
 * @return string Number of days to the Round Up.
 */
function nsru_get_days_to_shortcode() {

    return '<span class="days_to_round_up"></span>';

} // nsru_get_days_to_shortcode
add_shortcode( 'nsru_get_days_to', 'nsru_get_days_to_shortcode' );

/**
 * Return a span that AJAX can populate with the round up dates handling if the round up spans different months.
 *
 * @return string Round Up Dates string.
 */
function nsru_get_round_up_dates_shortcode() {

    return '<span class="round_up_dates"><br /></span>';

} // nsru_get_round_up_dates_shortcode
add_shortcode( 'nsru_get_round_up_dates', 'nsru_get_round_up_dates_shortcode' );

/**
 * Return a span that AJAX can populate with a list of speakers separated by line breaks.
 *
 * @return string
 */
function nsru_get_speakers_shortcode() {

    return '<p class="speakers"></p>';

} // nsru_get_speakers_shortcode
add_shortcode( 'nsru_get_speakers', 'nsru_get_speakers_shortcode' );

/**
 * Returns the PayPal surcharge.
 *
 * The PayPal surcharge will be based on the if the date is before or after the end of discounted tickets.
 *
 * @return string
 */
function nsru_get_surcharge_shortcode() {
    
    return '<span class="surcharge"></span>';

} // nsru_get_surcharge_shortcode
add_shortcode( 'nsru_get_surcharge', 'nsru_get_surcharge_shortcode' );

/**
 * Price of the Round Up Tickets.
 *
 * If there are discounted tickets on sale the price for the discounted tickets and end date for the
 * discount will be displayed as well.
 *
 * @return string
 */
function nsru_get_price_shortcode() {

    return '<span class="price"></span>';

} // nsru_get_price_shortcode
add_shortcode( 'nsru_get_price', 'nsru_get_price_shortcode' );

/**
 * Get the PayPal Buy Now Code.
 *
 * The PayPal service charge will be displayed above the PayPal Buy Now Button
 *
 * @return string
 */
function nsru_get_paypal_shortcode() {

    return '<span class="paypal"></span>';

} // nsru_get_paypal_shortcode
add_shortcode( 'nsru_get_paypal', 'nsru_get_paypal_shortcode' );

/**
 * Get the How PayPal Works Button.
 *
 * This will only be displayed if PayPal is enabled.
 *
 * @return string
 */
function nsru_get_how_paypal_works_shortcode() {

    $round_up_options = get_option( 'round_up_options' );

    $enable_paypal = is_array( $round_up_options ) ? ( array_key_exists( 'paypal_enable', $round_up_options ) ? intval($round_up_options['paypal_enable']) : 0 ) : 0;

    if(0 === $enable_paypal) {
        return '';
    }

    $retval  = '<a title="How PayPal Works" href="https://www.paypal.com/webapps/mpp/paypal-popup">';
    $retval .= '<img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_74x46.jpg" alt="PayPal Logo" border="0" />';
    $retval .= '</a>';

    return $retval;

} // nsru_get_how_paypal_works_shortcode
add_shortcode( 'nsru_get_how_paypal_works', 'nsru_get_how_paypal_works_shortcode' );

/**
 * Get North Shore Round Up Past Chairs.
 *
 * @return string
 */
function nsru_past_chairs_shortcode() {

    return '<span class="nsru_past_chairs"></span>';

} // nsru_past_chairs_shortcode
add_shortcode( 'nsru_past_chairs', 'nsru_past_chairs_shortcode' );

/**
 * Get North Shore Round Up Committee Members.
 *
 * @return string
 */
function nsru_committee_shortcode() {

    return '<span class="nsru_committee"></span>';

} // nsru_committee_shortcode
add_shortcode( 'nsru_committee', 'nsru_committee_shortcode' );

/**
 * Returns a span that AJAX can populate with the North Shore Round Up Meetings.
 *
 * @return string
 */
function nsru_meetings_shortcode() {

    return '<div class="meetings"></div><!-- /.meetings -->';

} // nsru_meetings_shortcode
add_shortcode( 'nsru_meetings', 'nsru_meetings_shortcode' );

/**
 *
 * @param type $atts
 * @return string
 */
function nsru_get_hotel_price_shortcode( $atts ) {
    $round_up_options = get_option( 'round_up_options' );

    $a = shortcode_atts( array(
        'type' => 'normal',
    ), $atts, 'nsru_get_hotel_price' );

    $hotel_price = '';
    if( 'harbour' === $a['type'] ) {
        $price = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_special_price', $round_up_options ) ? $round_up_options['hotel_harbour_special_price'] : '' ) : '';
        $hotel_harbour_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_enable', $round_up_options ) ? intval($round_up_options['hotel_harbour_enable']) : 0 ) : 0;
        if('' !== $price) {
            if(1 === $hotel_harbour_enable) {
                $hotel_price = '<span class="harbour-rooms">Deluxe Room with Harbour View for the special rate of $' . $price . '</span>';
            } else {
                $hotel_price = '<span class="harbour-rooms-sold-out">Deluxe Rooms with Harbour View are fully sold out!</span>';
            }
        }
    } else {
        $price = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_special_price', $round_up_options ) ? $round_up_options['hotel_special_price'] : '' ) : '';
        $hotel_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_enable', $round_up_options ) ? intval($round_up_options['hotel_enable']) : 0 ) : 0;
        if('' !== $price) {
            if(1 === $hotel_enable) {
                $hotel_price = '<span class="delux-room-rate">Deluxe Room for the special rate of $' . $price . '</span>';
            } else {
                $hotel_price = '<span class="delux-rooms-sold-out">Deluxe Rooms are fully sold out</span>';
            }
        }
    }

    return $hotel_price;

} // nsru_get_hotel_price_shortcode
add_shortcode( 'nsru_get_hotel_price', 'nsru_get_hotel_price_shortcode' );

/**
 *
 * @return string
 */
function nsru_get_hotel_booking_link_shortcode() {
    $round_up_options = get_option( 'round_up_options' );

    $hotel_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_enable', $round_up_options ) ? intval($round_up_options['hotel_enable']) : 0 ) : 0;
    $hotel_harbour_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_enable', $round_up_options ) ? intval($round_up_options['hotel_harbour_enable']) : 0 ) : 0;

    if(1 === $hotel_enable || 1 === $hotel_harbour_enable) {
        $website = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_booking_website', $round_up_options ) ? $round_up_options['hotel_booking_website'] : '' ) : '';
        $link = '<span class="hotel-booking-website">Book on-line by clicking the <a href="' . $website . '" target="_blank" title="Click Hotel Booking Link">Hotel Booking Link</a>.</span>';
    } else {
        $link = '<span class="hotel-booking-website-over">On-line hotel bookings are now closed.</span>';
    }

    return $link;

} // nsru_get_hotel_booking_link_shortcode
add_shortcode( 'nsru_get_hotel_booking_link', 'nsru_get_hotel_booking_link_shortcode' );

/**
 *
 * @return string
 */
function nsru_get_hotel_booking_code_shortcode() {
    $round_up_options = get_option( 'round_up_options' );

    $hotel_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_enable', $round_up_options ) ? intval($round_up_options['hotel_enable']) : 0 ) : 0;
    $hotel_harbour_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_enable', $round_up_options ) ? intval($round_up_options['hotel_harbour_enable']) : 0 ) : 0;

    if(1 === $hotel_enable || 1 === $hotel_harbour_enable) {
        $link = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_booking_code', $round_up_options ) ? $round_up_options['hotel_booking_code'] : '' ) : '';
        $link = '<span class="hotel-booking-code">Book by phone by calling the hotel and quoting the Reservation ID: ' . $link . ' to the Reservation Agent.</span>';
    } else {
        $link = '<span class="hotel-booking-code-over">Sadly all special rate hotel rooms are now sold out.</span>';
    }

    return $link;

} // nsru_get_hotel_booking_code_shortcode
add_shortcode( 'nsru_get_hotel_booking_code', 'nsru_get_hotel_booking_code_shortcode' );
