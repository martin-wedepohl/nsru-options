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
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('ABSPATH') or die('');

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'get_first_year':
        nsru_get_year_e('first');
        break;
    case 'get_year':
        nsru_get_year_e('current');
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

/**
 * Return the year of the Round Up
 *
 * Will return either the first year of the Round Up or the current year of the Round Up
 *
 * @param string $type - Type of year to be returned. Accepts 'first', 'current'.
 *
 * @return int - Year of the Round Up
 */
function nsru_get_year($type) {

    $round_up_options = get_option('round_up_options');

    $year = 0;

    if ('first' === $type) {
        $first_year = is_array($round_up_options) ? ( array_key_exists('first_year', $round_up_options) ? $round_up_options['first_year'] : '' ) : '';
        $year = $first_year;
    } elseif ('current' === $type) {
        $start_date = is_array($round_up_options) ? ( array_key_exists('start_date', $round_up_options) ? $round_up_options['start_date'] : '' ) : '';
        date_default_timezone_set(get_option('timezone_string'));
        $year = date('Y', strtotime($start_date));
    }

    return $year;

} // nsru_get_year


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

    $datetime = $date . (($time < 10) ? ' 0' : ' ') . $time . ':00:00';

    return $datetime;
} // nsru_create_date_time

/**
 * AJAX call to return the Round Up year
 *
 * Will echo the year and then die
 *
 * @param string $type - Type of year to be returned 'first' or 'current'
 */
function nsru_get_year_e($type) {

    echo nsru_get_year($type);

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

    $start = do_shortcode('[nsru_get_date format="Y F j" type=start]');
    $end   = do_shortcode('[nsru_get_date format="Y F j" type="end"]');

    $start_array = explode(' ', $start);
    $end_array   = explode(' ', $end);

    if ($start_array[0] === $end_array[0]) {
        // Same year
        if ($start_array[1] === $end_array[1]) {
            // Same month
            $retval = $start_array[2] . '-' . $end_array[2] . ' ' . $start_array[1] . ', ' . $start_array[0];
        } else {
            // Different months
            $retval = $start_array[2] . ' ' . $start_array[1] . ' - ' . $end_array[2] . ' ' . $end_array[1] . ', ' . $start_array[0];
        }
    } else {
        // Different years
        $retval = $start_array[2] . ' ' . $start_array[1] . ', ' . $start_array[0] . ' - ' . $end_array[2] . ' ' . $end_array[1] . ', ' . $end_array[0];
    }

    echo $retval;

    die();

} // nsru_get_round_up_dates

/**
 * AJAX handler to echo the annual Round Up
 */
function nsru_get_annual() {

    $number = nsru_get_year('current') - nsru_get_year('first') + 1;

    switch ($number % 10) {
        case 1:
            $suffix =  __('st', 'nsru-options');
            break;
        case 2:
            $suffix =  __('nd', 'nsru-options');
            break;
        case 3:
            $suffix =  __('rd', 'nsru-options');
            break;
        default:
            $suffix =  __('th', 'nsru-options');
            break;
    }
    $suffix .= ' ';

    echo $number . $suffix . __('Annual', 'nsru-options');

    die();

} // nsru_get_annual

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

    $round_up_options = get_option('round_up_options');

    $round_up_start      = is_array($round_up_options) ? ( array_key_exists('start_date', $round_up_options) ? $round_up_options['start_date'] : '' ) : '';
    $round_up_end        = is_array($round_up_options) ? ( array_key_exists('end_date', $round_up_options)   ? $round_up_options['end_date']   : '' ) : '';
    $round_up_start_time = is_array($round_up_options) ? ( array_key_exists('start_time', $round_up_options) ? $round_up_options['start_time'] : '' ) : '';
    $round_up_end_time   = is_array($round_up_options) ? ( array_key_exists('end_time', $round_up_options)   ? $round_up_options['end_time']   : '' ) : '';

    date_default_timezone_set(get_option('timezone_string'));

    $start = nsru_create_date_time($round_up_start, $round_up_start_time);
    $end   = nsru_create_date_time($round_up_end, $round_up_end_time);

    $start_ts = strtotime($start);
    $end_ts   = strtotime($end);
    $now      = time();

    $retval = __('is sadly over ... we hope see you next year.', 'nsru-options');

    if ($start_ts > $now) {
        // Hasn't started yet
        $start    = new DateTime($round_up_start . ' 00:00:00');
        $now      = new DateTime(date('Y-m-d 00:00:00'));
        $interval = $start->diff($now);
        $days     = $interval->format('%a');
        if ($days > 0) {
            $retval = __('starts in', 'nsru-options') . $days . __('days.', 'nsru-options');
        } else {
            $retval = __('starts TODAY!', 'nsru-options');
        }
    } elseif ($end_ts > $now) {
        // Started, but not ended
        $retval = __('is on NOW!', 'nsru-options');
    }

    echo $retval;

    die();

} // nsru_days_to_round_up()
