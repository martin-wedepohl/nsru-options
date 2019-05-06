<?php

defined( 'ABSPATH' ) or die( '' );

/**
 * All the required short codes used by the North Shore Round Up Options Plugin.
 * 
 * @package NSRU_Options
 */
    
/**
 * Returns the Round Up year.
 * 
 * Will return the year of either the current Round Up year or the year of the first Round Up.
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
}
add_shortcode( 'nsru_get_year', 'nsru_get_year_shortcode' );

/**
 * Return number of the round up with a suffix.
 * 
 * @return string Round Up number
 */
function nsru_get_annual_shortcode() {

    return '<span class="annual"></span>';

}
add_shortcode( 'nsru_get_annual', 'nsru_get_annual_shortcode' );

/**
 * Get a formatted date for the Round Up.
 * 
 * @param  array $atts {
 *     Optional array of arguments.
 *     @param string format Format for the date. Default 'F j, Y'. Accepts any PHP date format string.
 *     @param string type   Type of year.        Default 'start'.  Accepts 'start', 'end'.
 * }
 * 
 * @return string Round Up date
 */
function nsru_get_date_shortcode( $atts ) {
    $round_up_options = get_option( 'round_up_options' );
    $round_up_start   = is_array( $round_up_options ) ? ( array_key_exists( 'start_date', $round_up_options ) ? $round_up_options['start_date'] : '' ) : '';
    $round_up_end     = is_array( $round_up_options ) ? ( array_key_exists( 'end_date',   $round_up_options ) ? $round_up_options['end_date']   : '' ) : '';
    
    date_default_timezone_set( get_option('timezone_string') );

    $a = shortcode_atts( array(
        'format' => 'F j, Y',
        'type'   => 'start',
    ), $atts, 'nsru_get_date' );
    
    if( 'start' === $a['type'] ) {
        $timestamp = strtotime( $round_up_start );
    } elseif ( 'end' === $a['type'] ) {
        $timestamp = strtotime( $round_up_end );
    } else {
        $timestamp = strtotime( 'now' );
    }
    
    return date( $a['format'], $timestamp );
}
add_shortcode( 'nsru_get_date', 'nsru_get_date_shortcode' );

/**
 * Return the number of days to the round up or if it is on or if it is over.
 * 
 * Will return
 *    starts in X days.
 *    starts TODAY!
 *    is on NOW!
 *    is sadly over ... see you next year.
 * 
 * @return string Number of days to the Round Up.
 */
function nsru_get_days_to_shortcode() {
    return '<span class="days_to_round_up"></span>';
}
add_shortcode( 'nsru_get_days_to', 'nsru_get_days_to_shortcode' );

/**
 * Return the round up dates handling if the round up spans different months.
 * 
 * @return string Round Up Dates string.
 */
function nsru_get_round_up_dates_shortcode() {
    
    return '<span class="round_up_dates"><br /></span>';
    
}
add_shortcode( 'nsru_get_round_up_dates', 'nsru_get_round_up_dates_shortcode' );

/**
 * Return a list of speakers separated by line breaks.
 * 
 * @return string 
 */
function nsru_get_speakers_shortcode() {
    
    return '<p class="speakers"></p>';
    
}
add_shortcode( 'nsru_get_speakers', 'nsru_get_speakers_shortcode' );

/**
 * Returns the PayPal surcharge.
 * 
 * The PayPal surcharge will be based on the if the date is before or after the end of discounted tickets.
 * 
 * @return string
 */
function nsru_get_surcharge_shortcode() {

    date_default_timezone_set( get_option('timezone_string') );

    $round_up_options = get_option( 'round_up_options' );
    $end_date = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

    if ( '' === $end_date ) {
        // If there is no end date then there is no discounted tickets
        $end_date_ts = strtotime( 'now - 1 day' );
    } else {
        $end_date_ts = strtotime( $end_date . ' + 1 day' );
    }
    
    $now_ts = time();
    
    if ( $now_ts >= $end_date_ts ) {
        // Discounted prices have expired
        $surcharge = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge', $round_up_options ) ? number_format((float)$round_up_options['online_surcharge'], 2, '.', '') : '' ) : '';
    } else {
        // Discounted pirces are in effect
        $surcharge = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge_discount', $round_up_options ) ? number_format((float)$round_up_options['online_surcharge_discount'], 2, '.', '') : '' ) : '';
    }
    
    if ( '' !== $surcharge ) {
        $surcharge = '<span class="surcharge">$' . $surcharge . '</span>';
    }        

    return $surcharge;
}
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

    date_default_timezone_set( get_option('timezone_string') );

    $round_up_options = get_option( 'round_up_options' );
    $end_date = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

    if ( '' === $end_date ) {
        // If there is no end date then there is no discounted tickets
        $end_date_ts = strtotime( 'now - 1 day' );
    } else {
        $end_date_ts = strtotime( $end_date . ' + 1 day' );
    }
    
    $now_ts = time();
    
    $price = is_array( $round_up_options ) ? ( array_key_exists( 'ticket_price', $round_up_options ) ? $round_up_options['ticket_price'] : '' ) : '';
    $retstr = '<p class="round-up-price">The cost to attend the North Shore Round Up will be $' . $price . '.';
    if ( $now_ts < $end_date_ts ) {
    // Discounted pirces are in effect
    $end_date = date('F j, Y', $end_date_ts);
    $price = is_array( $round_up_options ) ? ( array_key_exists( 'ticket_price_discount', $round_up_options ) ? $round_up_options['ticket_price_discount'] : '' ) : '';
        $retstr .= "<br /><strong>NOTE:</strong> Tickets are on sale for the discounted price of $$price if you purchase them <strong>before</strong> $end_date.";
    }
    $retstr .= '</p>';
    
    return $retstr;
}
add_shortcode( 'nsru_get_price', 'nsru_get_price_shortcode' );

/**
 * Get the PayPal Buy Now Code.
 * 
 * The PayPal service charge will be displayed above the PayPal Buy Now Button
 * 
 * @return string
 */
function nsru_get_paypal_shortcode() {

    $round_up_options = get_option( 'round_up_options' );

    $enable_paypal = is_array( $round_up_options ) ? ( array_key_exists( 'paypal_enable', $round_up_options ) ? intval($round_up_options['paypal_enable']) : 0 ) : 0;
    
    if(0 === $enable_paypal) {
        return '<p class="paypal_closed">PayPal purchase is now closed. Please pick up your tickets at the event.</p>';
    }
    
    date_default_timezone_set( get_option('timezone_string') );
    $end_date = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

    if ( '' === $end_date ) {
        // If there is no end date then there is no discounted tickets
        $end_date_ts = strtotime( 'now - 1 day' );
    } else {
        $end_date_ts = strtotime( $end_date . ' + 1 day' );
    }
    
    $now_ts = time();
    
    if ( $now_ts >= $end_date_ts ) {
        // Discounted prices have expired
        $surcharge  = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge', $round_up_options ) ? number_format((float)$round_up_options['online_surcharge'], 2, '.', '') : '' ) : '';
        $paypalcode =  is_array( $round_up_options ) ? ( array_key_exists( 'paypal_code', $round_up_options ) ? $round_up_options['paypal_code'] : '' ) : '';
    } else {
        // Discounted pirces are in effect
        $surcharge = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge_discount', $round_up_options ) ? number_format((float)$round_up_options['online_surcharge_discount'], 2, '.', '') : '' ) : '';
        $paypalcode =  is_array( $round_up_options ) ? ( array_key_exists( 'paypal_code_discount', $round_up_options ) ? $round_up_options['paypal_code_discount'] : '' ) : '';
    }
    
    if ( '' !== $surcharge ) {
        $surcharge = "<p class='ticket_surcharge'>Please note there is an additional service charge of $$surcharge when buying tickets on-line.</p>";
    }
    
    if ( '' !== $paypalcode ) {
        $retval  = $surcharge;
        $retval .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">';
        $retval .= '<input name="cmd" type="hidden" value="_s-xclick">';
        $retval .= '<input name="hosted_button_id" type="hidden" value="' . $paypalcode . '">';
        $retval .= '<input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" type="image">';
        $retval .= '<img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" border="0" />';
        $retval .= '</form>';
    }

    return $retval;
    
}
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
    
}
add_shortcode( 'nsru_get_how_paypal_works', 'nsru_get_how_paypal_works_shortcode' );

/**
 * Get North Shore Round Up Past Chairs.
 * 
 * @return string
 */
function nsru_past_chairs_shortcode() {

    $round_up_options = get_option( 'round_up_options' );
    $start_year = is_array( $round_up_options ) ? ( array_key_exists( 'first_year', $round_up_options ) ? intval($round_up_options['first_year']) : 0 ) : 0;
    
    $posts = get_posts(array(
        'post_type'   => 'nsru_pastchairs',
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'       => 'ASC',
    ));
    
    $retstr = '<div class="pastchairstitle">Past Chairs of the North Shore Round Up:</div><div class="pastchairs"><table><tbody>';
    foreach($posts as $post) {
        $title = get_the_title($post->ID);
        $meta = get_post_meta( $post->ID, NSRU_PastChairs::GetMetaKey(), false );
        $year = intval($meta[0]);
        $num  = $year - $start_year + 1;
        if($num > 1 && 1 == $num % 4) {
            $retstr .= '</tr><tr>';
        } elseif (1 === $num) {
            $retstr .= '<tr>';
        }
        $retstr .= "<td>$num: $year - $title</td>";
    }
    $retstr .= '</tr></tbody></table></div><!-- /.pastchairs -->';
    
    return $retstr;
    
}
add_shortcode( 'nsru_past_chairs', 'nsru_past_chairs_shortcode' );

/**
 * Get North Shore Round Up Committee Members.
 * 
 * @return string
 */
function nsru_committee_shortcode() {

    $round_up_options = get_option( 'round_up_options' );
    
    $posts = get_posts(array(
        'post_type'   => 'nsru_committee',
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'       => 'DESC',
    ));
    
    $retstr = '<div class="committee"><table><tbody>';
    $first = true;
    foreach($posts as $post) {
        $title = get_the_title($post->ID);
        $meta = get_post_meta( $post->ID, NSRU_Committee::GetMetaKey(), true );        
        $name = (0 === strlen($meta['name'])) ? '<strong>POSITION OPEN</strong>' : $meta['name'];
        $group = (0 === strlen($meta['group'])) ? '-----' : $meta['group'];
        if (true === $first) {
            $retstr .= '<tr>';
            $first = false;
        } else {
            $retstr .= '</tr><tr>';
        }
        $retstr .= "<td>$title</td><td>$name</td><td>$group</td>";
    }
    $retstr .= '</tr></tbody></table></div><!-- /.committee -->';
    
    return $retstr;
    
}
add_shortcode( 'nsru_committee', 'nsru_committee_shortcode' );

/**
 * Get North Shore Round Up Meetings.
 * 
 * @return string
 */
function nsru_meetings_shortcode() {

    return '<div class="meetings"></div><!-- /.meetings -->';
    
}
add_shortcode( 'nsru_meetings', 'nsru_meetings_shortcode' );

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
}
add_shortcode( 'nsru_get_hotel_price', 'nsru_get_hotel_price_shortcode' );

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
}
add_shortcode( 'nsru_get_hotel_booking_link', 'nsru_get_hotel_booking_link_shortcode' );

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
}
add_shortcode( 'nsru_get_hotel_booking_code', 'nsru_get_hotel_booking_code_shortcode' );

