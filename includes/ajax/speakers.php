<?php

/**
 * All the AJAX calls for the speakers used in the Round Up
 *
 * This is required due to the caching plugins used in WordPress
 *
 * Entry to these functions will be from the POST request to the file with the input 'action'
 *    get_speakers - Return the Round Up Speakers
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */
defined('ABSPATH') or die('');

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'get_speakers':
        nsru_speakers();
        break;
}

/**
 * AJAX handler to echo the speakers at the Round Up
 */
function nsru_speakers() {

    // Get the speaker posts
    $posts = get_posts(array(
        'post_type'   => 'nsru_speakers',
        'post_status' => 'publish',
        'numberposts' => -1,
    ));

    $retstr = '';

    // Loop through all of the posts
    foreach ($posts as $post) {
        $meta = get_post_meta($post->ID, NSRU_Speakers::GetMetaKey());

        if (null !== $meta[0]['name'] && '' !== $meta[0]['name']) {
            switch ($meta[0]['org']) {
                case NSRU_Speakers::GetAAValue():
                    $org = __('(AA)', 'nsru-options');
                    break;
                case NSRU_Speakers::GetAlAnonValue():
                    $org = __('(Al-Anon)', 'nsru-options');
                    break;
                default:
                    $org = '';
            }

            $retstr .= $meta[0]['name'] . ', ' . $meta[0]['city'] . ', ' . $meta[0]['province'] . ' ' . $org . '<br />';
        }
    }

    if ('' === $retstr) {
        $retstr = 'We are currently planning the speakers<br>Please check back later for a list of speakers';
    }

    header('Content-type: application/json');
    echo json_encode($retstr);

    die();
    
} // nsru_speakers
