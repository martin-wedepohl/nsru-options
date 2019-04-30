<?php

defined('ABSPATH') or die('');

$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

switch($action) {
    case 'get_speakers':
        nsru_speakers();
        break;
}

function nsru_speakers() {
    
    $posts = get_posts(array(
        'post_type'   => 'nsru_speakers',
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'       => 'ASC',
    ));
    
    $retstr = '';
    
    foreach($posts as $post) {
        $meta = get_post_meta( $post->ID, NSRU_Speakers::GetMetaKey() );
        
        if( null !== $meta[0]['name'] && '' !== $meta[0]['name'] ) {
            switch( $meta[0]['org'] ) {
                case NSRU_Speakers::GetAAValue():     $org = '(AA)';     break;
                case NSRU_Speakers::GetAlAnonValue(): $org = '(AlAnon)'; break;
                default:                              $org = '';
            }

            $retstr .= $meta[0]['name'] . ', ' . $meta[0]['city'] . ', ' . $meta[0]['province'] . ' ' . $org . '<br />';
        }
    }
    
    echo $retstr;
    
    die();
}
