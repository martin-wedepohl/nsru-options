<?php

/**
 * Custom Post Type for the North Shore Round Up Meeting Rooms
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('ABSPATH') or die('');

/**
 * Class for the North Shore Round Up Meeting Rooms.
 */
class NSRU_Rooms {

    /**
     * Constructor for the class.
     *
     * Adds all the actions and filters used by the custom post type.
     */
    public function __construct() {

        add_action('init', array($this, 'create_cpt'));

    } // __construct

    /**
     * Creates Meeting Rooms Custom Post Type.
     *
     * Meeting Rooms are not searchable as they are only included on certain pages.
     *
     * @global type $wp_version Used to get the Word Press version.
     */
    public function create_cpt() {

        global $wp_version;

        $supports = array('title');

        $labels = array(
            'name'               => __('Rooms', 'nsru-options'),
            'singular_name'      => __('Room', 'nsru-options'),
            'add_new'            => __('Add New Room', 'nsru-options'),
            'add_new_item'       => __('Add New Room', 'nsru-options'),
            'edit_item'          => __('Edit Room', 'nsru-options'),
            'new_item'           => __('New Room', 'nsru-options'),
            'view_item'          => __('View Room', 'nsru-options'),
            'view_items'         => __('View Rooms', 'nsru-options'),
            'search_items'       => __('Search Rooms', 'nsru-options'),
            'not_found'          => __('No Rooms Found', 'nsru-options'),
            'not_found_in_trash' => __('No Rooms Found In Trash', 'nsru-options'),
            'all_items'          => __('All Rooms', 'nsru-options'),
            'archives'           => __('Room Archives', 'nsru-options'),
            'attributes'         => __('Room Attributes', 'nsru-options'),
        );
        if (version_compare($wp_version, '5.0', '>=')) {
            $labels['item_published']           = __('Room published', 'nsru-options', 'nsru-options');
            $labels['item_published_privately'] = __('Room published privately', 'nsru-options');
            $labels['item_reverted_to_draft']   = __('Room reverted to draft', 'nsru-options');
            $labels['item_scheduled']           = __('Room scheduled', 'nsru-options');
            $labels['item_updated']             = __('Room updated', 'nsru-options');
        }

        $args = array(
            'supports'            => $supports,
            'labels'              => $labels,
            'public'              => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => 'nsru-options',
            'menu_position'       => 200,
            'menu_icon'           => 'dashicons-admin-home',
            'query_var'           => false,
            'rewrite'             => false,
            'has_archive'         => false,
            'hierachical'         => false,
            'show_in_rest'        => false,
        );

        register_post_type('nsru_rooms', $args);

    } // create_cpt

} // class NSRU_Rooms

new NSRU_Rooms();
