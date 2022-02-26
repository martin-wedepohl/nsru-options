<?php

/**
 * All the AJAX calls for the dates used in the Round Up
 *
 * This is required due to the caching plugins used in WordPress
 *
 * Entry to these functions will be from the POST request to the file with the input 'action'
 *    get_first_year     - Return the first year the Round Up was held
 *    get_year           - Return the year of the current Round Up
 *    get_round_up_dates - Get the Round Up dates as a range
 *    get_annual         - Get the number of the Round Up with a suffix st, nd, rd, th
 *    days_to_round_up   - Return the number of days to the Round Up
 *    get_surcharge      - Get the online ticket surcharge
 *    get_paypal         - Display the PayPal section
 *    get_ticket_price   - Display the ticket prices
 *    how_paypal_works   - Display How PayPal works button
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'ABSPATH' ) or die( '' );

$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

switch ( $action ) {
	case 'get_first_year':
		nsru_get_year_e( 'first' );
		break;
	case 'get_now_year':
		nsru_get_year_e( 'now' );
		break;
	case 'get_year':
		nsru_get_year_e( 'current' );
		break;
	case 'get_round_up_dates':
		nsru_get_round_up_dates();
		break;
	case 'get_annual':
		nsru_get_annual();
		break;
	case 'days_to_round_up':
		nsru_days_to_round_up();
		break;
	case 'get_surcharge':
		nsru_surcharge();
		break;
	case 'get_paypal':
		nsru_paypal();
		break;
	case 'get_square':
		nsru_square();
		break;
	case 'get_scholarship':
		nsru_scholarship();
		break;
	case 'get_ticket_price':
		nsru_price();
		break;
	case 'how_paypal_works':
		nsru_how_paypal_works();
		break;
}

/**
 * Return the year of the Round Up
 *
 * Will return either the first year of the Round Up or the current year of the Round Up
 *
 * @param string $type - Type of year to be returned. Accepts 'first', 'current'.
 *
 * @return int - Year of the Round Up
 */
function nsru_get_year( $type ) {

	$round_up_options = get_option( 'round_up_options' );

	$year = 0;

	if ('first' === $type) {
		$first_year = is_array( $round_up_options ) ? ( array_key_exists( 'first_year', $round_up_options ) ? $round_up_options['first_year'] : '' ) : '';
		$year = $first_year;
	} elseif ( 'current' === $type ) {
		$start_date = is_array( $round_up_options ) ? ( array_key_exists( 'start_date', $round_up_options ) ? $round_up_options['start_date'] : '' ) : '';
		date_default_timezone_set(get_option( 'timezone_string' ));
		$year = date( 'Y', strtotime( $start_date ) );
	} elseif ( 'now' === $type ) {
		date_default_timezone_set( get_option( 'timezone_string' ) );
		$year = date( 'Y' );
	}

	return $year;

} // nsru_get_year


/**
 * Create the date/time format
 * YYYY-MM-DD HH:MM:SS
 *
 * @param string $date Date for the Round Up.
 * @param string $time Time for the Round Up.
 *
 * @return string Date/Time string
 */
function nsru_create_date_time( $date, $time ) {

	$datetime = $date . ( ( $time < 10 ) ? ' 0' : ' ' ) . $time . ':00:00';

	return $datetime;
} // nsru_create_date_time

/**
 * Get a formatted date for the Round Up.
 * 
 * @param string $type   Type of date.        Default 'start'.  Accepts 'start', 'end'.
 * @param string $format Format for the date. Default 'F j, Y'. Accepts any PHP date format string.
 * 
 * @return string Formatted date string
 */
function nsru_get_date( $type = 'start', $format = 'F j, Y' ) {

	$round_up_options = get_option( 'round_up_options' );

	date_default_timezone_set( get_option( 'timezone_string' ) );

	if( 'start' === $type ) {
		$round_up_start = is_array( $round_up_options ) ? ( array_key_exists( 'start_date', $round_up_options ) ? $round_up_options['start_date'] : '' ) : '';
		$timestamp      = strtotime( $round_up_start );
	} elseif ( 'end' === $type ) {
		$round_up_end = is_array( $round_up_options ) ? ( array_key_exists( 'end_date', $round_up_options ) ? $round_up_options['end_date']   : '' ) : '';
		$timestamp    = strtotime( $round_up_end );
	} else {
		$timestamp = strtotime( 'now' );
	}

	return date( $format, $timestamp );

} // nsru_get_date

/**
 * AJAX call to return the Round Up year
 *
 * Will echo the year and then die
 *
 * @param string $type - Type of year to be returned 'first' or 'current'.
 */
function nsru_get_year_e( $type ) {

	header( 'Content-type: application/json' );
	echo json_encode( nsru_get_year( $type ) );

	die();

} // nsru_get_year

/**
 * AJAX handler to echo the Round Up date range
 *
 * Will echo
 *    StartDay - StopDay Month, Year
 *    StartDay MonthStart - StopDay MonthStop, Year
 *    StartDay MonthStart YearStart - StopDay MonthStop, YearStop
 */
function nsru_get_round_up_dates() {

	$start = nsru_get_date( 'start', 'Y F j' );
	$end   = nsru_get_date( 'end', 'Y F j' );

	$start_array = explode( ' ', $start );
	$end_array   = explode( ' ', $end );

	if ( $start_array[0] === $end_array[0] ) {
		// Same year.
		if ( $start_array[1] === $end_array[1] ) {
			// Same month.
			$retval = $start_array[2] . '-' . $end_array[2] . ' ' . $start_array[1] . ', ' . $start_array[0];
		} else {
			// Different months.
			$retval = $start_array[2] . ' ' . $start_array[1] . ' - ' . $end_array[2] . ' ' . $end_array[1] . ', ' . $start_array[0];
		}
	} else {
		// Different years.
		$retval = $start_array[2] . ' ' . $start_array[1] . ', ' . $start_array[0] . ' - ' . $end_array[2] . ' ' . $end_array[1] . ', ' . $end_array[0];
	}

	header( 'Content-type: application/json' );
	echo json_encode( $retval );

	die();

} // nsru_get_round_up_dates

/**
 * AJAX handler to echo the annual Round Up
 */
function nsru_get_annual() {

	$meta_keys = NSRU_PastChairs::GetMetaKey();
	$posts     = get_posts(
		array(
			'post_type'   => 'nsru_pastchairs',
			'post_status' => 'publish',
			'numberposts' => -1,
			'order'       => 'ASC',
		)
	);
	$num_cancelled = 0;
	foreach ( $posts as $post ) {
		$num_cancelled += 'on' === get_post_meta( $post->ID, $meta_keys['cancelled'], true) ? 1 : 0;
	}

	$number = nsru_get_year( 'current' ) - nsru_get_year( 'first' ) + 1;
	$number -= $num_cancelled;

	switch ( $number % 10 ) {
		case 1:
			$suffix =  __( 'st', 'nsru-options' );
			break;
		case 2:
			$suffix =  __( 'nd', 'nsru-options' );
			break;
		case 3:
			$suffix =  __( 'rd', 'nsru-options' );
			break;
		default:
			$suffix =  __( 'th', 'nsru-options' );
			break;
	}
	$suffix .= ' ';

	header( 'Content-type: application/json' );
	echo json_encode( $number . $suffix . __( 'Annual', 'nsru-options' ) );

	die();

} // nsru_get_annual

/**
 * AJAX handler to echo the number of days to the North Shore Round Up
 *
 * Will echo:
 *    starts in X days.
 *    starts TODAY!
 *    is on NOW!
 *    is sadly over. Thank you for making the Round Up a wonderful success. We hope to see you at next years Round Up which starts in 328 days.
 */
function nsru_days_to_round_up() {

	$round_up_options = get_option( 'round_up_options' );

	$round_up_start      = is_array( $round_up_options ) ? ( array_key_exists( 'start_date', $round_up_options ) ? $round_up_options['start_date'] : '' ) : '';
	$round_up_end        = is_array( $round_up_options ) ? ( array_key_exists( 'end_date', $round_up_options ) ? $round_up_options['end_date'] : '' ) : '';
	$round_up_start_time = is_array( $round_up_options ) ? ( array_key_exists( 'start_time', $round_up_options ) ? $round_up_options['start_time'] : '' ) : '';
	$round_up_end_time   = is_array( $round_up_options ) ? ( array_key_exists( 'end_time', $round_up_options ) ? $round_up_options['end_time'] : '' ) : '';

	date_default_timezone_set( get_option( 'timezone_string' ) );

	$start = nsru_create_date_time( $round_up_start, $round_up_start_time );
	$end   = nsru_create_date_time( $round_up_end, $round_up_end_time );

	$start_ts = strtotime( $start );
	$end_ts   = strtotime( $end );
	$now      = time();


	if ( $start_ts > $now ) {
		// Hasn't started yet.
		$start    = new DateTime( $round_up_start . ' 00:00:00' );
		$now      = new DateTime( date( 'Y-m-d 00:00:00' ) );
		$interval = $start->diff( $now );
		$days     = $interval->format( '%a' );
		if ( $days > 0 ) {
			$retval = __( 'starts in ', 'nsru-options' ) . $days . __( ' days.', 'nsru-options' );
		} else {
			$retval = __( 'starts TODAY!', 'nsru-options' );
		}
	} elseif ( $end_ts > $now) {
		// Started, but not ended.
		$retval = __( 'is on NOW!', 'nsru-options' );
	} else {
		$retval  = __( 'is sadly over.', 'nsru-options' ) . '<br />';
		$retval .= __( 'Thank you for making the Round Up a wonderful success.', 'nsru-options' ) . '<br />';

		/*
		* Calculate the date of the next year's Round Up (Easter Weekend)
		* This uses the 'Anonymous Gregorian algorithm' for calculating the dates of Easter
		* Reference: https://en.wikipedia.org/wiki/Computus
		*/
		$dash_pos = strpos( $round_up_start, '-' );
		if ( false !== $dash_pos ) {
			$year  = substr( $round_up_start, 0, $dash_pos ) + 1;
			$a     = $year % 19;
			$b     = intval( $year / 100 );
			$c     = $year % 100;
			$d     = intval( $b / 4 );
			$e     = $b % 4;
			$f     = intval( ( $b + 8 ) / 25 );
			$g     = intval( ( $b - $f + 1 ) / 3 );
			$h     = ( 19 * $a + $b - $d - $g + 15 ) % 30;
			$i     = intval($c / 4);
			$k     = $c % 4;
			$l     = (32 + 2 * $e +2 * $i - $h - $k) % 7;
			$m     = intval( ( $a + 11 * $h + 22 * $l ) / 451 );
			$month = intval( ( $h + $l - 7 * $m + 114 ) / 31 );
			$day   = ( ( $h + $l - 7 * $m + 114 ) % 31 ) + 1;
			if ( $month < 10 ) {
				$month = '0' . $month;
			}
			if ( $day < 10 ) {
				$day = '0' . $day;
			}
			$start    = new DateTime( $year . '-' . $month . '-' . $day . ' 00:00:00' );
			$now      = new DateTime( date( 'Y-m-d 00:00:00' ) );
			$interval = $start->diff( $now );
			$days     = $interval->format( '%a' ) - 2;
			$retval .= __( 'We hope to see you at next years Round Up which starts in ', 'nsru-options' ) . $days . __( ' days.', 'nsru-options' );
		}
	}

	header( 'Content-type: application/json' );
	echo json_encode( $retval );

	die();

} // nsru_days_to_round_up()

/**
 * AJAX handler to echo online ticket surcharge
 *
 * This will use the current date and time to see if the discounted prices are still in effect
 */
function nsru_surcharge() {

	date_default_timezone_set( get_option( 'timezone_string' ) );

	$round_up_options = get_option( 'round_up_options' );
	$end_date         = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

	if ( '' === $end_date ) {
		// If there is no end date then there is no discounted tickets.
		$end_date_ts = strtotime( 'now - 1 day' );
	} else {
		$end_date_ts = strtotime( $end_date . ' + 1 day' );
	}

	$now_ts = time();

	if ( $now_ts >= $end_date_ts ) {
		// Discounted prices have expired.
		$retval = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge', $round_up_options ) ? number_format( ( float ) $round_up_options['online_surcharge'], 2, '.', '' ) : '' ) : '';
	} else {
		// Discounted prices are in effect.
		$retval = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge_discount', $round_up_options ) ? number_format( ( float )$round_up_options['online_surcharge_discount'], 2, '.', '' ) : '' ) : '';
	}

	header( 'Content-type: application/json' );
	echo json_encode( $retval );

	die();

} // nsru_surcharge

/**
 * AJAX handler to echo PayPal code
 *
 * This will use the current date and time to see if the discounted prices are still in effect
 */
function nsru_paypal() {

	$round_up_options = get_option( 'round_up_options' );

	$enable_paypal = is_array( $round_up_options ) ? ( array_key_exists( 'paypal_enable', $round_up_options ) ? intval( $round_up_options['paypal_enable'] ) : 0 ) : 0;

	if ( 0 === $enable_paypal ) {
		echo '<p class="paypal_closed">PayPal purchase is now closed. Please pick up your tickets at the event.</p>';
		die();
	}

	date_default_timezone_set( get_option( 'timezone_string' ) );
	$end_date = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

	if ( '' === $end_date ) {
		// If there is no end date then there is no discounted tickets.
		$end_date_ts = strtotime( 'now - 1 day' );
	} else {
		$end_date_ts = strtotime( $end_date . ' + 1 day' );
	}

	$now_ts = time();

	if ( $now_ts >= $end_date_ts ) {
		// Discounted prices have expired.
		$surcharge  = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge', $round_up_options ) ? number_format( ( float ) $round_up_options['online_surcharge'], 2, '.', '' ) : '' ) : '';
		$paypalcode =  is_array( $round_up_options ) ? ( array_key_exists( 'paypal_code', $round_up_options ) ? $round_up_options['paypal_code'] : '' ) : '';
	} else {
		// Discounted pirces are in effect.
		$surcharge  = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge_discount', $round_up_options ) ? number_format( ( float ) $round_up_options['online_surcharge_discount'], 2, '.', '' ) : '' ) : '';
		$paypalcode =  is_array( $round_up_options ) ? ( array_key_exists( 'paypal_code_discount', $round_up_options ) ? $round_up_options['paypal_code_discount'] : '' ) : '';
	}

	if ( '' !== $surcharge ) {
		$surcharge = "<p class='ticket_surcharge'>Please note there is an additional service charge of $$surcharge when buying tickets on-line.</p>";
	}

	if ( '' !== $paypalcode ) {
		$retval  = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">';
		$retval .= '<input name="cmd" type="hidden" value="_s-xclick">';
		$retval .= '<input name="hosted_button_id" type="hidden" value="' . $paypalcode . '">';
		$retval .= '<input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" type="image">';
		$retval .= '<img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" border="0" />';
		$retval .= '</form>';
	}

	header( 'Content-type: application/json' );
	echo json_encode( $retval );

	die();

} // nsru_surcharge

/**
 * AJAX handler to echo Square code
 *
 * This will use the current date and time to see if the discounted prices are still in effect
 */
function nsru_square() {

	$round_up_options = get_option( 'round_up_options' );

	$enable_square = is_array( $round_up_options ) ? ( array_key_exists( 'square_enable', $round_up_options ) ? intval( $round_up_options['square_enable'] ) : 0 ) : 0;

	if ( 0 === $enable_square ) {
		echo '<p class="paypal_closed">Square purchase is now closed. Please pick up your tickets at the event.</p>';
		die();
	}

	date_default_timezone_set( get_option( 'timezone_string' ) );
	$end_date = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

	if ( '' === $end_date ) {
		// If there is no end date then there is no discounted tickets.
		$end_date_ts = strtotime( 'now - 1 day' );
	} else {
		$end_date_ts = strtotime( $end_date . ' + 1 day' );
	}

	$now_ts = time();

	if ( $now_ts >= $end_date_ts ) {
		// Discounted prices have expired.
		$surcharge  = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge', $round_up_options ) ? number_format( ( float ) $round_up_options['online_surcharge'], 2, '.', '' ) : '' ) : '';
		$squarecode =  is_array( $round_up_options ) ? ( array_key_exists( 'square_code', $round_up_options ) ? $round_up_options['square_code'] : '' ) : '';
	} else {
		// Discounted pirces are in effect.
		$surcharge  = is_array( $round_up_options ) ? ( array_key_exists( 'online_surcharge_discount', $round_up_options ) ? number_format( ( float ) $round_up_options['online_surcharge_discount'], 2, '.', '' ) : '' ) : '';
		$squarecode =  is_array( $round_up_options ) ? ( array_key_exists( 'square_code_discount', $round_up_options ) ? $round_up_options['square_code_discount'] : '' ) : '';
	}

	if ( '' !== $surcharge ) {
		$surcharge = "<p class='ticket_surcharge'>Please note there is an additional service charge of $$surcharge when buying tickets on-line.</p>";
	}

	if ( '' !== $squarecode ) {
		$retval  = '<div style="overflow: auto; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; width: 259px; background: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: -2px 10px 5px rgba(0, 0, 0, 0); border-radius: 10px; font-family: SQ Market, SQ Market, Helvetica, Arial, sans-serif;">';
		$retval .= '<a style="display: inline-block; font-size: 18px; line-height: 48px; height: 48px; color: #ffffff; min-width: 212px; background-color: #006aff; text-align: center; box-shadow: 0 0 0 1px rgba(0,0,0,.1) inset; border-radius: 0px;" href="https://square.link/u/' . $squarecode . '?src=embed" target="_blank" rel="noopener">';
		$retval .= 'Buy now';
		$retval .= '</a>';
		$retval .= '</div>';
	}

	header( 'Content-type: application/json' );
	echo json_encode( $retval );

	die();

} // nsru_square

/**
 * AJAX handler to echo Square Scholarship code
 */
function nsru_scholarship() {

	$round_up_options = get_option( 'round_up_options' );

	$enable_square = is_array( $round_up_options ) ? ( array_key_exists( 'square_enable', $round_up_options ) ? intval( $round_up_options['square_enable'] ) : 0 ) : 0;

	if ( 0 === $enable_square ) {
		echo '<p class="paypal_closed">Square purchase is now closed. Please pick up your tickets at the event.</p>';
		die();
	}

	$squarecode =  is_array( $round_up_options ) ? ( array_key_exists( 'square_code_scholarship', $round_up_options ) ? $round_up_options['square_code_scholarship'] : '' ) : '';

	if ( '' !== $squarecode ) {
		$retval  = '<div style="overflow: auto; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; width: 259px; background: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: -2px 10px 5px rgba(0, 0, 0, 0); border-radius: 10px; font-family: SQ Market, SQ Market, Helvetica, Arial, sans-serif;">';
		$retval .= '<a style="display: inline-block; font-size: 18px; line-height: 48px; height: 48px; color: #ffffff; min-width: 212px; background-color: #006aff; text-align: center; box-shadow: 0 0 0 1px rgba(0,0,0,.1) inset; border-radius: 0px;" href="https://square.link/u/' . $squarecode . '?src=embed" target="_blank" rel="noopener">';
		$retval .= 'Buy now';
		$retval .= '</a>';
		$retval .= '<p style="font-size: 18px; line-height: 20px;">Scholarship</p>';
		$retval .= '<p style="font-size: 18px; line-height: 20px; font-weight: 600">Variable</p>';
		$retval .= '</div>';
	}

	header( 'Content-type: application/json' );
	echo json_encode( $retval );

	die();

} // nsru_scholarship

/**
 * AJAX handler to echo ticket prices
 */
function nsru_price() {

	date_default_timezone_set( get_option( 'timezone_string' ) );

	$round_up_options = get_option( 'round_up_options' );
	$end_date = is_array( $round_up_options ) ? ( array_key_exists( 'discount_end_date', $round_up_options ) ? $round_up_options['discount_end_date'] : '' ) : '';

	if ( '' === $end_date ) {
		// If there is no end date then there is no discounted tickets.
		$end_date_ts = strtotime( 'now - 1 day' );
	} else {
		$end_date_ts = strtotime( $end_date );
	}

	$now_ts = time();

	$price  = is_array( $round_up_options ) ? ( array_key_exists( 'ticket_price', $round_up_options ) ? $round_up_options['ticket_price'] : '' ) : '';
	$retstr = '<div class="ticket-prices">In person ticket price for this (3) day event is $' . $price . '.';
	if ( $now_ts < $end_date_ts ) {
		// Discounted prices are in effect.
		$end_date = date( 'F j, Y', $end_date_ts );
		$price    = is_array( $round_up_options ) ? ( array_key_exists( 'ticket_price_discount', $round_up_options ) ? $round_up_options['ticket_price_discount'] : '' ) : '';
		$retstr  .= "<p class='ticket-discount'><span class='early-bird'>EARLY</span> Bird pricing is $$price <span class='early-only'>ONLY</span> until $end_date.</p>";
	}
	$retstr .= '</div>';

	header( 'Content-type: application/json' );
	echo json_encode( $retstr );

	die();

} // nsru_surcharge

/**
 * Get the How PayPal Works Button.
 *
 * This will only be displayed if PayPal is enabled.
 *
 * @return string
 */
function nsru_how_paypal_works() {

	$round_up_options = get_option( 'round_up_options' );

	$enable_paypal = is_array( $round_up_options ) ? ( array_key_exists( 'paypal_enable', $round_up_options ) ? intval( $round_up_options['paypal_enable'] ) : 0 ) : 0;

	if ( 0 === $enable_paypal ) {
		header( 'Content-type: application/json' );
		echo json_encode( '' );
	} else {
		$retval  = '<a title="How PayPal Works" href="https://www.paypal.com/webapps/mpp/paypal-popup">';
		$retval .= '<img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_74x46.jpg" alt="PayPal Logo" border="0" />';
		$retval .= '</a>';

		header( 'Content-type: application/json' );
		echo json_encode( $retval );
	}

	die();

} // nsru_how_paypal_works
