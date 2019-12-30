<?php

/**
 * All the AJAX calls for the hotel bookings/prices used in the Round Up
 *
 * This is required due to the caching plugins used in WordPress
 *
 * Entry to these functions will be from the POST request to the file with the input 'action'
 *    get_room_rate         - Return the rate for a deluxe room
 *    get_harbour_room_rate - Return the rate for a deluxe room with harbour view
 *    get_booking_link      - Return the website for the on-line booking link
 *    get_booking_code      - Return the telephone booking code
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */
defined('ABSPATH') or die('');

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'get_room_rate':
        nsru_room_rates( 'normal' );
        break;
    case 'get_harbour_room_rate':
        nsru_room_rates( 'harbour' );
        break;
    case 'get_booking_link':
        nsru_booking_link();
        break;
    case 'get_booking_code':
        nsru_booking_code();
        break;
}

function nsru_room_rates( $type ) {
    $round_up_options = get_option( 'round_up_options' );

    $hotel_price = '';
    if( 'harbour' === $type ) {
        $price = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_special_price', $round_up_options ) ? $round_up_options['hotel_harbour_special_price'] : '' ) : '';
        $hotel_harbour_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_enable', $round_up_options ) ? intval($round_up_options['hotel_harbour_enable']) : 0 ) : 0;
        if('' !== $price) {
            if(1 === $hotel_harbour_enable) {
                $hotel_price = 'Deluxe Room with Harbour View for the special rate of $' . $price;
            } else {
                $hotel_price = '<span class="harbour-rooms-sold-out">Deluxe Rooms with Harbour View are fully sold out!</span>';
            }
        }
    } else {
        $price = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_special_price', $round_up_options ) ? $round_up_options['hotel_special_price'] : '' ) : '';
        $hotel_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_enable', $round_up_options ) ? intval($round_up_options['hotel_enable']) : 0 ) : 0;
        if('' !== $price) {
            if(1 === $hotel_enable) {
                $hotel_price = 'Deluxe Room for the special rate of $' . $price;
            } else {
                $hotel_price = '<span class="deluxe-rooms-sold-out">Deluxe Rooms are fully sold out</span>';
            }
        }
    }

    header('Content-type: application/json');
    echo json_encode($hotel_price);
    
    die();

} // nsru_room_rates

function nsru_booking_link() {
    $round_up_options = get_option( 'round_up_options' );

    $hotel_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_enable', $round_up_options ) ? intval($round_up_options['hotel_enable']) : 0 ) : 0;
    $hotel_harbour_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_enable', $round_up_options ) ? intval($round_up_options['hotel_harbour_enable']) : 0 ) : 0;

    if(1 === $hotel_enable || 1 === $hotel_harbour_enable) {
        $website = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_booking_website', $round_up_options ) ? $round_up_options['hotel_booking_website'] : '' ) : '';
        $link = 'Book on-line by clicking the <a href="' . $website . '" target="_blank" title="Click Hotel Booking Link">Hotel Booking Link</a>.';
    } else {
        $link = '<span class="hotel-booking-website-over">On-line hotel bookings are now closed.</span>';
    }

    header('Content-type: application/json');
    echo json_encode($link);
    
    die();

} // nsru_booking_link

function nsru_booking_code() {
    $round_up_options = get_option( 'round_up_options' );

    $hotel_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_enable', $round_up_options ) ? intval($round_up_options['hotel_enable']) : 0 ) : 0;
    $hotel_harbour_enable = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_harbour_enable', $round_up_options ) ? intval($round_up_options['hotel_harbour_enable']) : 0 ) : 0;

    if(1 === $hotel_enable || 1 === $hotel_harbour_enable) {
        $link = is_array( $round_up_options ) ? ( array_key_exists( 'hotel_booking_code', $round_up_options ) ? $round_up_options['hotel_booking_code'] : '' ) : '';
        $link = 'Book by phone by calling the hotel and quoting the Reservation ID: ' . $link . ' to the Reservation Agent.';
    } else {
        $link = '<span class="hotel-booking-code-over">Sadly all special rate hotel rooms are now sold out.</span>';
    }

    header('Content-type: application/json');
    echo json_encode($link);
    
    die();

} // nsru_booking_code
