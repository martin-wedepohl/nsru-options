<?php

defined('ABSPATH') or die('');

$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

switch($action) {
    case 'get_meetings':
        nsru_meetings();
        break;
}

function nsru_meetings() {

    $round_up_options = get_option( 'round_up_options' );
    
    $posts = get_posts(array(
        'post_type'   => 'nsru_meetings',
        'post_status' => 'publish',
        'numberposts' => -1,
        'order'       => 'ASC',
    ));

    $meetings_array = array();
    foreach($posts as $post) {
        $title = get_the_title($post->ID);
        $meta = get_post_meta( $post->ID, NSRU_Meetings::GetMetaKey(), true );
        $stop_time = $meta['stop_time'];
        if(':' === $stop_time) {
            $stop_time = 0;
        } else {
            $stop_time = strtotime($stop_time);
        }
        $room = $meta['room'];
        if('' !== $room) {
            $room = get_the_title($meta['room']);
        }
        $meetings_array[strtotime($meta['meeting_date'])][] = array(
            'start_time' => strtotime($meta['start_time']),
            'stop_time' => $stop_time,
            'title' => $title,
            'topic' => $meta['topic'],
            'speakers' => $meta['speakers'],
            'hosted_by' => $meta['hosted_by'],
            'room' => $room,
        );
    }
    
    ksort($meetings_array);
    
    function cmp($a, $b) {
        $retval = $a['start_time'] - $b['start_time'];
        if(0 == $retval) {
            $retval = $a['stop_time'] - $b['stop_time'];
            if(0 == $retval) {
                $retval = strcmp($a['room'], $b['room']);
            }
        }
        return $retval;
    }
    
    foreach($meetings_array as $key => $meetings) {
        usort($meetings, 'cmp');
        $meetings_array[$key] = $meetings;
    }

    $retstr = '';
    foreach($meetings_array as $key => $meetings) {
        $retstr .= '<table><thead><tr><th colspan="3">' . date('l F jS', $key) . '</th></tr></thead><tbody>';
        
        foreach($meetings as $meeting) {
            $start_time = date('g:i a', $meeting['start_time']);
            if($meeting['stop_time'] > 0) {
                $stop_time = date('g:i a', $meeting['stop_time']);
                if('12:00 am' === $stop_time) {
                    $stop_time = ' - Midnight';
                } else {
                    $stop_time = ' - ' . $stop_time;
                }
            } else {
                $stop_time = '';
            }
            $info = $meeting['title'];
            $info .= ('' === $meeting['topic']) ? '' : '<br />' . $meeting['topic'];
            $info .= ('' === $meeting['speakers']) ? '' : '<br />' . $meeting['speakers'];
            $info .= ('' === $meeting['hosted_by']) ? '' : '<br />' . $meeting['hosted_by'];
            $retstr .= '<tr><td>' . $start_time . $stop_time . '</td>';
            $retstr .= '<td>' . $info . '</td>';
            $retstr .= '<td>' . $meeting['room'] . '</td></tr>';            
        }
        $retstr .= '</tbody></table>';
    }
    
    echo $retstr;
    
    die();
    
}