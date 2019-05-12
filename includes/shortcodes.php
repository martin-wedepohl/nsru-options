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
 * Returns a span that AJAX can populate with the North Shore Round Up room rates.
 * 
 * The span will be for either the Deluxe Room or the Deluxe Room with Harbour View
 * 
 * @param array $atts - Array of attributes for the shortcode. Default type => 'normal'. Accepts 'normal', 'harbour'.
 * 
 * @return string
 */
function nsru_get_hotel_price_shortcode( $atts ) {

    $a = shortcode_atts( array(
        'type' => 'normal',
    ), $atts, 'nsru_get_hotel_price' );

    $hotel_price = '';
    if( 'harbour' === $a['type'] ) {
        $hotel_price = '<span class="harbour_room_rate"></span>';
    } else {
        $hotel_price = '<span class="deluxe_room_rate"></span>';
    }

    return $hotel_price;

} // nsru_get_hotel_price_shortcode
add_shortcode( 'nsru_get_hotel_price', 'nsru_get_hotel_price_shortcode' );

/**
 *
 * @return string
 */
function nsru_get_hotel_booking_link_shortcode() {
    
    return '<span class="hotel_booking_website"></span>';

} // nsru_get_hotel_booking_link_shortcode
add_shortcode( 'nsru_get_hotel_booking_link', 'nsru_get_hotel_booking_link_shortcode' );

/**
 *
 * @return string
 */
function nsru_get_hotel_booking_code_shortcode() {

    return '<span class="hotel_booking_code"></span>';

} // nsru_get_hotel_booking_code_shortcode
add_shortcode( 'nsru_get_hotel_booking_code', 'nsru_get_hotel_booking_code_shortcode' );

/**
 * Get the How PayPal Works Button.
 *
 * This will only be displayed if PayPal is enabled.
 *
 * @return string
 */
function nsru_get_how_paypal_works_shortcode() {

    return '<span class="how_paypal_works"></span>';

} // nsru_get_how_paypal_works_shortcode
add_shortcode( 'nsru_get_how_paypal_works', 'nsru_get_how_paypal_works_shortcode' );

/**
 * Shortcodes below this point are actual shortcodes rather than just returning a span that AJAX
 * can modify.
 * 
 * These shortcodes are for static or rarely changing values that can be cached.
 */

function nsru_get_hotel_information_shortcode( $atts, $content = null ) {

    $round_up_options = get_option( 'round_up_options' );
    
    $a = shortcode_atts( array(
        'type' => 'name',
        'link' => true,
    ), $atts, 'nsru_get_hotel_information' );
    
    switch( $a['type'] ) {
        case 'local':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_local_phone', $round_up_options ) ? $round_up_options['hotel_local_phone'] : '' ) : '';
            if(true === $a['link']) {
                $link = preg_replace('/[-+. ]/', '', $data);                // Remove -, +, . and space from phone number
                $data = '<a href="tel:' . $link . '">' . $data . '</a>';
            }
            break;
        case 'tollfree_can':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_canadian_toll_free', $round_up_options ) ? $round_up_options['hotel_canadian_toll_free'] : '' ) : '';
            if(true === $a['link']) {
                $link = preg_replace('/[-+. ]/', '', $data);                // Remove -, +, . and space from phone number
                $data = '<a href="tel:' . $link . '">' . $data . '</a>';
            }
            break;
        case 'tollfree_us':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_us_toll_free', $round_up_options ) ? $round_up_options['hotel_us_toll_free'] : '' ) : '';
            if(true === $a['link']) {
                $link = preg_replace('/[-+. ]/', '', $data);                // Remove -, +, . and space from phone number
                $data = '<a href="tel:' . $link . '">' . $data . '</a>';
            }
            break;
        case 'fax':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_fax', $round_up_options ) ? $round_up_options['hotel_fax'] : '' ) : '';
            if(true === $a['link']) {
                $link = preg_replace('/[-+. ]/', '', $data);                // Remove -, +, . and space from phone number
                $data = '<a href="tel:' . $link . '">' . $data . '</a>';
            }
            break;
        case 'email':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_email', $round_up_options ) ? $round_up_options['hotel_email'] : '' ) : '';
            if(true === $a['link']) {
                $data = '<a href="mailto:' . $data . '">' . $data . '</a>';
            }
            break;
        case 'website':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_website', $round_up_options ) ? $round_up_options['hotel_website'] : '' ) : '';
            if(true === $a['link']) {
                $name = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_name', $round_up_options ) ? $round_up_options['hotel_name'] : '' ) : '';
                $data = '<a href="' . $data . '" target="_blank" rel="noopener">' . $name . '</a>';
            }
            break;
        case 'address':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_address', $round_up_options ) ? $round_up_options['hotel_address'] : '' ) : '';
            break;
        default:
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_name', $round_up_options ) ? $round_up_options['hotel_name'] : '' ) : '';
            break;
    }
    
    return $content . ' ' . $data;
    
}
add_shortcode( 'nsru_get_hotel_information', 'nsru_get_hotel_information_shortcode' );

function nsru_get_location_information_shortcode( $atts, $content = null ) {

    $round_up_options = get_option( 'round_up_options' );
    
    $a = shortcode_atts( array(
        'type' => 'name',
        'link' => true,
    ), $atts, 'nsru_get_location_information' );
    
    switch( $a['type'] ) {
        case 'website':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'location_website', $round_up_options ) ? $round_up_options['location_website'] : '' ) : '';
            if(true === $a['link']) {
                $name = is_array( $round_up_options ) ? ( array_key_exists( 'location_name', $round_up_options ) ? $round_up_options['location_name'] : '' ) : '';
                $data = '<a href="' . $data . '" target="_blank" rel="noopener">' . $name . '</a>';
            }
            break;
        case 'address':
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'location_address', $round_up_options ) ? $round_up_options['location_address'] : '' ) : '';
            break;
        default:
            $data = is_array( $round_up_options ) ? ( array_key_exists( 'location_name', $round_up_options ) ? $round_up_options['location_name'] : '' ) : '';
            break;
    }
    
    return $content . ' ' . $data;
    
}
add_shortcode( 'nsru_get_location_information', 'nsru_get_location_information_shortcode' );
