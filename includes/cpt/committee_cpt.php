<?php

defined('ABSPATH') or die('');

/**
 * Custom Post Type for the North Shore Round Up Committee Members
 * 
 * @package NSRU_Options
 */

/**
 * Class for the North Shore Round Up Committee Members.
 */
class NSRU_Committee {

    private static $META_DATA_KEY = '_meta_committee_data';
    private static $META_BOX_DATA = 'nsru_committee_save_meta_box_data';
    private static $META_BOX_NONCE = 'nsru_committee_meta_box_nonce';

    /**
     * Constructor for the class.
     * 
     * Adds all the actions and filters used by the custom post type.
     */
    public function __construct() {
        
        add_action('init', array($this, 'create_cpt'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_filter('manage_nsru_committee_posts_columns', array($this, 'set_custom_edit_columns'));
        add_action('manage_nsru_committee_posts_custom_column', array($this, 'custom_column'), 10, 2);
        
    }

    /**
     * Returns the meta data key for the custom post type.
     * 
     * @return string
     */
    public static function GetMetaKey() {
        return NSRU_Committee::$META_DATA_KEY;
    }

    /**
     * Creates Past Chairs Custom Post Type.
     *
     * Past Chairs are not searchable as they are only included on certain pages.
     *
     * @global type $wp_version Used to get the Word Press version.
     */
    public function create_cpt() {

        global $wp_version;

        $supports = array('title');

        $labels = array(
            'name' => __('Committee Members', 'nsru-options'),
            'singular_name' => __('Committee Member', 'nsru-options'),
            'add_new' => __('Add New Committee Member', 'nsru-options'),
            'add_new_item' => __('Add New Committee Member', 'nsru-options'),
            'edit_item' => __('Edit Committee Member', 'nsru-options'),
            'new_item' => __('New Committee Member', 'nsru-options'),
            'view_item' => __('View Committee Member', 'nsru-options'),
            'view_items' => __('View Committee Members', 'nsru-options'),
            'search_items' => __('Search Committee Members', 'nsru-options'),
            'not_found' => __('No Committee Members Found', 'nsru-options'),
            'not_found_in_trash' => __('No Committee Members Found In Trash', 'nsru-options'),
            'all_items' => __('All Committee Members', 'nsru-options'),
            'archives' => __('Committee Member Archives', 'nsru-options'),
            'attributes' => __('Committee Member Attributes', 'nsru-options'),
        );
        if (version_compare($wp_version, '5.0', '>=')) {
            $labels['item_published'] = __('Committee Member published', 'nsru-options', 'nsru-options');
            $labels['item_published_privately'] = __('Committee Member published privately', 'nsru-options');
            $labels['item_reverted_to_draft'] = __('Committee Member reverted to draft', 'nsru-options');
            $labels['item_scheduled'] = __('Committee Member scheduled', 'nsru-options');
            $labels['item_updated'] = __('Committee Member updated', 'nsru-options');
        }

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 200,
            'menu_icon' => 'dashicons-nametag',
            'query_var' => false,
            'rewrite' => false,
            'has_archive' => false,
            'hierachical' => false,
            'show_in_rest' => false,
        );

        register_post_type('nsru_committee', $args);
    }// End create_cpt

    /**
     * Add a meta box for custom arguments.
     */
    public function add_meta_box() {
        add_meta_box('committee_section', __('Committee Members Details', 'nsru-options'), array($this, 'meta_box_callback'), 'nsru_committee');
    }

    /**
     * Prints the meta box content.
     *
     * Data for the speaker is serialized in the database.
     *
     * @param WP_Post $post The object for the current post/page.
     */
    public function meta_box_callback($post) {

        // Set up nonce for later verification.
        wp_nonce_field(NSRU_Committee::$META_BOX_DATA, NSRU_Committee::$META_BOX_NONCE);

        // Get and unserialize the meta data
        $data = get_post_meta($post->ID, NSRU_Committee::$META_DATA_KEY, true);

        // Check for data and set to a rational value if it doesn't exist
        $name = '';
        $group = '';

        // Serialized data will be in an array
        if (is_array($data)) {
            $name = (array_key_exists('name', $data)) ? $data['name'] : '';
            $group = (array_key_exists('group', $data)) ? $data['group'] : '';
        }
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php echo __('Name:', 'nsru-options'); ?></th>
                <td>
                    <input type="text" id="name" name="name" class="regular-text" value="<?php echo esc_attr($name); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Group:', 'nsru-options'); ?></th>
                <td>
                    <input type="text" id="group" name="group" class="regular-text" value="<?php echo esc_attr($group); ?>" />
                </td>
            </tr>
        </table>

        <?php
    }// End meta_box_callback

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta_box_data($post_id) {

        if (!isset($_POST[NSRU_Committee::$META_BOX_NONCE])) {
            return;
        }

        if (!wp_verify_nonce($_POST[NSRU_Committee::$META_BOX_NONCE], NSRU_Committee::$META_BOX_DATA)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        $my_data = array(
            'name' => sanitize_text_field($_POST['name']),
            'group' => sanitize_text_field($_POST['group']),
        );

        update_post_meta($post_id, NSRU_Committee::$META_DATA_KEY, $my_data);
        
    }// End save_meta_box_data

    /**
     * Sets the column headers for the custom post type.
     *
     * @param array $columns Current columns.
     *
     * @return array Modified columns.
     */
    public function set_custom_edit_columns($columns) {

        // Moving published date to the end of the list.
        unset($columns['date']);

        $columns['name'] = __('Name', 'nsru-options');
        $columns['group'] = __('Group', 'nsru-options');
        $columns['date'] = __('Date', 'nsru-options');

        return $columns;
        
    }

    /**
     * Echos the meta data for the appropriate column.
     *
     * @param string $column Column name.
     * @param int $post_id   Post ID.
     */
    public function custom_column($column, $post_id) {

        $meta = get_post_meta($post_id, NSRU_Committee::$META_DATA_KEY);
        switch ($column) {
            case 'name': 
                $name = $meta[0]['name'];
                if(0 === strlen($name)) {
                    $name = "<strong>POSITION OPEN</strong>";
                }
                echo $name; 
                break;
            case 'group':
                $group = $meta[0]['group'];
                if(0 === strlen($group)) {
                    $group = "-----";
                }
                echo $group; 
                break;
        }
    }

}// End class NSRU_Committee

new NSRU_Committee();
