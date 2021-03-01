<?php

/**
 * All the AJAX calls for the past chairs used in the Round Up
 *
 * This is required due to the caching plugins used in WordPress
 *
 * Entry to these functions will be from the POST request to the file with the input 'action'
 *    get_committee - Return the Round Up Committee
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */
defined('ABSPATH') or die('');

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'get_past_chairs':
        nsru_past_chairs();
        break;
}

/**
 * AJAX handler to echo the past_chairs at the Round Up
 */
function nsru_past_chairs() {

    $round_up_options = get_option( 'round_up_options' );
    $start_year = is_array( $round_up_options ) ? ( array_key_exists( 'first_year', $round_up_options ) ? intval($round_up_options['first_year']) : 0 ) : 0;

    $meta_keys     = NSRU_PastChairs::GetMetaKey();
    $num_cancelled = 0;

    $posts = get_posts(array(
        'post_type'   => 'nsru_pastchairs',
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'       => 'ASC',
    ));

    $retstr = '<div class="pastchairstitle">Past Chairs of the North Shore Round Up:</div><div class="pastchairs"><table><tbody>';
    foreach($posts as $post) {
        $title     = get_the_title($post->ID);
        $year      = intval(get_post_meta( $post->ID, $meta_keys['year'], true));
        $cancelled = 'on' === get_post_meta( $post->ID, $meta_keys['cancelled'], true) ? 1 : 0;
        $num       = $year - $start_year + 1;
        if($num > 1 && 1 == $num % 4) {
            $retstr .= '</tr><tr>';
        } elseif (1 === $num) {
            $retstr .= '<tr>';
        }
        $num_cancelled += $cancelled;
        $num           -= $num_cancelled;
        if (1 === $cancelled) {
            $retstr .= "<td>$year - CANCELLED</td>";
        } else {
            $retstr .= "<td>$num: $year - $title</td>";
        }
    }
    $retstr .= '</tr></tbody></table></div><!-- /.pastchairs -->';

    header('Content-type: application/json');
    echo json_encode($retstr);

    die();
    
} // nsru_speakers
