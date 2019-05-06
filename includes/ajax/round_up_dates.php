<?php

defined('ABSPATH') or die('');

$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

switch($action) {
    case 'get_first_year':
        nsru_get_year_e( 'first' );
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
}

function nsru_get_year( $type ) {
    $round_up_options = get_option( 'round_up_options' );
    $first_year = is_array( $round_up_options ) ? ( array_key_exists( 'first_year', $round_up_options ) ? $round_up_options['first_year'] : '' ) : '';
    $start_date = is_array( $round_up_options ) ? ( array_key_exists( 'start_date', $round_up_options ) ? $round_up_options['start_date'] : '' ) : '';
    
    date_default_timezone_set( get_option('timezone_string') );

    if( 'first' === $type ) {
        return $first_year;
    } elseif ( 'current' === $type ) {
        return date( 'Y', strtotime( $start_date ) );
    }
    
    return 0;
}

function nsru_get_year_e( $type ) {
    echo nsru_get_year($type);

    die();
}

function nsru_get_round_up_dates() {
    
    $start = do_shortcode( '[nsru_get_date format="Y F j" type=start]' );
    $end   = do_shortcode( '[nsru_get_date format="Y F j" type="end"]' );
    $start_array = explode( ' ', $start );
    $end_array   = explode( ' ', $end );
    if( $start_array[1] === $end_array[1] ) {
        $retval = $start_array[2] . '-' . $end_array[2] . ' ' . $start_array[1] . ', ' . $start_array[0];
    } else {
        $retval = $start_array[2] . ' ' . $start_array[1] . ' - ' . $end_array[2] . ' ' . $end_array[1] . ', ' . $start_array[0];
    }
    
    echo $retval;
    
    die();
    
}

function nsru_get_annual() {
    
    $number = nsru_get_year( 'current' ) - nsru_get_year( 'first' ) + 1;
    
    switch( $number % 10 ) {
        case 1:  $suffix = 'st'; break;
        case 2:  $suffix = 'nd'; break;
        case 3:  $suffix = 'rd'; break;
        default: $suffix = 'th'; break;
    }
    
    echo $number . $suffix . ' Annual';
    
    die();
}

/**
 * AJAX handler to echo the number of days to the North Shore Round Up
 * 
 * Will echo:
 *    starts in X days.
 *    starts TODAY!
 *    is on NOW!
 *    is sadly over ... we hope to see you next year.
 */
function nsru_days_to_round_up() {
    
    // Get the Round Up Options for the start/stop date/time
    $round_up_options    = get_option('round_up_options');
    $round_up_start      = is_array($round_up_options) ? ( array_key_exists('start_date', $round_up_options) ? $round_up_options['start_date'] : '' ) : '';
    $round_up_end        = is_array($round_up_options) ? ( array_key_exists('end_date',   $round_up_options) ? $round_up_options['end_date']   : '' ) : '';
    $round_up_start_time = is_array($round_up_options) ? ( array_key_exists('start_time', $round_up_options) ? $round_up_options['start_time'] : '' ) : '';
    $round_up_end_time   = is_array($round_up_options) ? ( array_key_exists('end_time',   $round_up_options) ? $round_up_options['end_time']   : '' ) : '';

    date_default_timezone_set(get_option('timezone_string'));
    
    $start = nsru_create_date_time($round_up_start, $round_up_start_time);
    $end   = nsru_create_date_time($round_up_end, $round_up_end_time);

    $start_ts = strtotime($start);
    $end_ts   = strtotime($end);
    $now      = time();

    $retval = 'is sadly over ... we hope see you next year.';

    if ($start_ts > $now) {
        // Hasn't started yet
        $start    = new DateTime($round_up_start . ' 00:00:00');
        $now      = new DateTime(date('Y-m-d 00:00:00'));
        $interval = $start->diff($now);
        $days     = $interval->format('%a');
        if ($days > 0) {
            $retval = "starts in $days days.";
        } else {
            $retval = 'starts TODAY!';
        }
    } elseif ($end_ts > $now) {
        // Started, but not ended
        $retval = 'is on NOW!';
    }

    echo $retval;
    
    die();
    
}// nsru_days_to_round_up

/**
 * Create the date/time format
 * YYYY-MM-DD HH:MM:SS
 * 
 * @param string $date Date for the Round Up
 * @param string $time Time for the Round Up
 * 
 * @return string Date/Time string
 */
function nsru_create_date_time($date, $time) {
    
    $datetime  = $date . (($time < 10) ? ' 0' : ' ') . $time . ':00:00';
    
    return $datetime;
    
}// nsru_create_date_time