<?php

defined('ABSPATH') or die('');

/**
 * Custom Post Type for the North Shore Round Up Past Chairs
 * 
 * @package NSRU_Options
 */

/**
 * Class for the North Shore Round Up Past Chairs.
 */
class NSRU_PastChairs {

    private static $META_DATA_KEY = '_meta_pastchairs_data';
    private static $META_BOX_DATA = 'nsru_pastchairs_save_meta_box_data';
    private static $META_BOX_NONCE = 'nsru_pastchairs_meta_box_nonce';

    /**
     * Constructor for the class.
     * 
     * Adds all the actions and filters used by the custom post type.
     */
    public function __construct() {
        
        add_action('init', array($this, 'create_cpt'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_filter('manage_nsru_pastchairs_posts_columns', array($this, 'set_custom_edit_columns'));
        add_action('manage_nsru_pastchairs_posts_custom_column', array($this, 'custom_column'), 10, 2);
        
    }

    /**
     * Returns the meta data key for the custom post type.
     * 
     * @return string
     */
    public static function GetMetaKey() {
        return NSRU_PastChairs::$META_DATA_KEY;
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
            'name' => __('Past Chairs', 'nsru-options'),
            'singular_name' => __('Past Chair', 'nsru-options'),
            'add_new' => __('Add New Past Chair', 'nsru-options'),
            'add_new_item' => __('Add New Past Chair', 'nsru-options'),
            'edit_item' => __('Edit Past Chair', 'nsru-options'),
            'new_item' => __('New Past Chair', 'nsru-options'),
            'view_item' => __('View Past Chair', 'nsru-options'),
            'view_items' => __('View Past Chairs', 'nsru-options'),
            'search_items' => __('Search Past Chairs', 'nsru-options'),
            'not_found' => __('No Past Chairs Found', 'nsru-options'),
            'not_found_in_trash' => __('No Past Chairs Found In Trash', 'nsru-options'),
            'all_items' => __('All Past Chairs', 'nsru-options'),
            'archives' => __('Past Chair Archives', 'nsru-options'),
            'attributes' => __('Past Chair Attributes', 'nsru-options'),
        );
        if (version_compare($wp_version, '5.0', '>=')) {
            $labels['item_published'] = __('Past Chair published', 'nsru-options', 'nsru-options');
            $labels['item_published_privately'] = __('Past Chair published privately', 'nsru-options');
            $labels['item_reverted_to_draft'] = __('Past Chair reverted to draft', 'nsru-options');
            $labels['item_scheduled'] = __('Past Chair scheduled', 'nsru-options');
            $labels['item_updated'] = __('Past Chair updated', 'nsru-options');
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
            'menu_icon' => 'dashicons-businessman',
            'query_var' => false,
            'rewrite' => false,
            'has_archive' => false,
            'hierachical' => false,
            'show_in_rest' => false,
        );

        register_post_type('nsru_pastchairs', $args);
    }// End create_cpt

    /**
     * Add a meta box for custom arguments.
     */
    public function add_meta_box() {
        add_meta_box('pastchairs_section', __('Past Chairs Details', 'nsru-options'), array($this, 'meta_box_callback'), 'nsru_pastchairs');
    }

    /**
     * Prints the meta box content.
     *
     * @param WP_Post $post The object for the current post/page.
     */
    public function meta_box_callback($post) {

        // Set up nonce for later verification.
        wp_nonce_field(NSRU_PastChairs::$META_BOX_DATA, NSRU_PastChairs::$META_BOX_NONCE);

        // Get Options
        $round_up_options = get_option( 'round_up_options' );
        $first_year = is_array( $round_up_options ) ? ( array_key_exists( 'first_year', $round_up_options ) ? $round_up_options['first_year'] : 0 ) : 0;
        
        // Get meta data
        $meta = get_post_meta($post->ID, NSRU_PastChairs::$META_DATA_KEY, false);
        $year = $meta[0];

        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php echo __('Year:', 'nsru-options'); ?></th>
                <td>
                    <input type="number" id="year" name="year" class="regular-text" min="<?php echo $first_year; ?>" max="2200" step="1" value="<?php echo esc_attr($year); ?>" />
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

        if (!isset($_POST[NSRU_PastChairs::$META_BOX_NONCE])) {
            return;
        }

        if (!wp_verify_nonce($_POST[NSRU_PastChairs::$META_BOX_NONCE], NSRU_PastChairs::$META_BOX_DATA)) {
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

        $my_data = sanitize_text_field($_POST['year']);

        update_post_meta($post_id, NSRU_PastChairs::$META_DATA_KEY, $my_data);
        
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

        $columns['year'] = __('Year', 'nsru-options');
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

        $meta = get_post_meta($post_id, NSRU_PastChairs::$META_DATA_KEY, false);
        $year = $meta[0];
        switch ($column) {
            case 'year': echo $year;
                break;
        }
    }

}// End class NSRU_PastChairs

new NSRU_PastChairs();
