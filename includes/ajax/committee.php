<?php

/**
 * All the AJAX calls for the committee used in the Round Up
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
    case 'get_committee':
        nsru_committee();
        break;
}

/**
 * AJAX handler to echo the committee members at the Round Up
 */
function nsru_committee() {

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
        $meta  = get_post_meta( $post->ID, NSRU_Committee::GetMetaKey(), true );
        $name  = (0 === strlen($meta['name'])) ? '<strong>POSITION OPEN</strong>' : $meta['name'];
        $group = (0 === strlen($meta['group'])) ? '-----' : $meta['group'];
        if (true === $first) {
            $retstr .= '<tr>';
            $first   = false;
        } else {
            $retstr .= '</tr><tr>';
        }
        $retstr .= "<td>$title</td><td>$name</td><td>$group</td>";
    }
    $retstr .= '</tr></tbody></table></div><!-- /.committee -->';

    header('Content-type: application/json');
    echo json_encode($retstr);

    die();
    
} // nsru_speakers
