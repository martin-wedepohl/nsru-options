<?php

/**
 * All the AJAX calls for the meetings used in the Round Up
 *
 * This is required due to the caching plugins used in WordPress
 *
 * Entry to these functions will be from the POST request to the file with the input 'action'
 *    get_meetings - Return the Round Up Meetings
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('ABSPATH') or die('');

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'get_meetings':
        nsru_meetings();
        break;
}

/**
 * Helper function for sorting the meetings based on start/stop time and room
 *
 * @param array $a - First Meeting
 * @param array $b - Second meeting
 *
 * @return int - Negative, 0 or Positive
 */
function nsru_meeting_cmp($a, $b) {

    $retval = $a['start_time'] - $b['start_time'];
    if (0 == $retval) {
        // Start times are the same check stop time
        $retval = $a['stop_time'] - $b['stop_time'];
        if (0 == $retval) {
            // Stop times are the same check rooms
            $retval = strcmp($a['room'], $b['room']);
        }
    }

    return $retval;

} // nsru_meeting_cmp

/**
 * AJAX handler to echo the meetings at the Round Up
 *
 * Meetings will be sorted by date and time order in case they are not entered that way
 */
function nsru_meetings() {

    $round_up_options = get_option('round_up_options');

    // Get all the meeting posts
    $posts = get_posts(
        array(
            'post_type'   => 'nsru_meetings',
            'post_status' => 'publish',
            'numberposts' => -1,
            'order'       => 'ASC',
        )
    );

    $meetings_array = array();

    // Loop through all of the posts
    foreach ($posts as $post) {
        $title     = get_the_title($post->ID);
        $meta      = get_post_meta($post->ID, NSRU_Meetings::GetMetaKey(), true);
        $stop_time = $meta['stop_time'];
        if (':' === $stop_time) {
            $stop_time = 0;
        } else {
            $stop_time = strtotime($stop_time);
        }
        $room = $meta['room'];
        if ('' !== $room) {
            $room = get_the_title($meta['room']);
        }
        $meetings_array[strtotime($meta['meeting_date'])][] = array(
            'start_time' => strtotime($meta['start_time']),
            'stop_time'  => $stop_time,
            'title'      => $title,
            'topic'      => $meta['topic'],
            'speakers'   => $meta['speakers'],
            'hosted_by'  => $meta['hosted_by'],
            'room'       => $room,
        );
    }

    ksort($meetings_array);                 // Sort array based on meeting date

    // Sort each day basaed on start/stop/rooms
    foreach ($meetings_array as $key => $meetings) {
        usort($meetings, 'nsru_meeting_cmp');
        $meetings_array[$key] = $meetings;
    }

    /**
     * Create a table for each of the days of the Round Up
     * Column 1 will be the time of the meeting
     * Column 2 will be the meeting information
     * Column 3 will be the room of the meeting
     */
    $retstr = '';
    foreach ($meetings_array as $key => $meetings) {
        $retstr .= '<table><thead><tr><th colspan="3">' . date('l F jS', $key) . '</th></tr></thead><tbody>';

        foreach ($meetings as $meeting) {
            $start_time = date('g:i a', $meeting['start_time']);
            if ($meeting['stop_time'] > 0) {
                $stop_time = date('g:i a', $meeting['stop_time']);
                if ('12:00 am' === $stop_time) {
                    $stop_time = ' - ' . __('Midnight', 'nsru-options');
                } else {
                    $stop_time = ' - ' . $stop_time;
                }
            } else {
                $stop_time = '';
            }
            $info    = $meeting['title'];
            $info   .= ('' === $meeting['topic'])     ? '' : '<br />' . $meeting['topic'];
            $info   .= ('' === $meeting['speakers'])  ? '' : '<br />' . $meeting['speakers'];
            $info   .= ('' === $meeting['hosted_by']) ? '' : '<br />' . $meeting['hosted_by'];

            $retstr .= '<tr><td>' . $start_time . $stop_time . '</td>';
            $retstr .= '<td>'     . $info                    . '</td>';
            $retstr .= '<td>'     . $meeting['room']         . '</td></tr>';
        }
        $retstr .= '</tbody></table>';
    }

    echo $retstr;

    die();

} // nsru_meetings