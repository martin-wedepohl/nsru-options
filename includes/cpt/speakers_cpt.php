<?php

/**
 * Custom Post Type for the North Shore Round Up Speakers
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('ABSPATH') or die('');

/**
 * Class for the North Shore Round Up Speakers.
 */
class NSRU_Speakers {

    private static $META_DATA_KEY  = '_meta_speaker_data';
    private static $META_BOX_DATA  = 'nsru_speaker_save_meta_box_data';
    private static $META_BOX_NONCE = 'nsru_speaker_meta_box_nonce';
    private static $AA_ORG         = 1;
    private static $ALANON_ORG     = 2;
    private static $NO_ORG         = 3;

    /**
     * Constructor for the class.
     *
     * Adds all the actions and filters used by the custom post type.
     */
    public function __construct() {

        add_action('init', array($this, 'create_cpt'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_filter('manage_nsru_speakers_posts_columns', array($this, 'set_custom_edit_columns'));
        add_action('manage_nsru_speakers_posts_custom_column', array($this, 'custom_column'), 10, 2);

    } // __construct

    /**
     * Returns the meta data key for the custom post type.
     *
     * @return string
     */
    public static function GetMetaKey() {

        return NSRU_Speakers::$META_DATA_KEY;

    } // GetMetaKey

    /**
     * Returns the value for the AA Organization.
     *
     * @return int
     */
    public static function GetAAValue() {

        return NSRU_Speakers::$AA_ORG;

    } // GetAAValue

    /**
     * Returns the value for the AlAnon Organization.
     *
     * @return int
     */
    public static function GetAlAnonValue() {

        return NSRU_Speakers::$ALANON_ORG;

    } // GetAlAnonValue

    /**
     * Creates Speaker Custom Post Type.
     *
     * Speakers only needs a title with all the other parameters added through a custom meta box.
     * Speakers are not searchable as they are only included on certain pages.
     *
     * @global type $wp_version Used to get the Word Press version.
     */
    public function create_cpt() {

        global $wp_version;

        $supports = array('title');

        $labels = array(
            'name'               => __('Speakers',                   'nsru-options'),
            'singular_name'      => __('Speaker',                    'nsru-options'),
            'add_new'            => __('Add New Speaker',            'nsru-options'),
            'add_new_item'       => __('Add New Speaker',            'nsru-options'),
            'edit_item'          => __('Edit Speaker',               'nsru-options'),
            'new_item'           => __('New Speaker',                'nsru-options'),
            'view_item'          => __('View Speaker',               'nsru-options'),
            'view_items'         => __('View Speakers',              'nsru-options'),
            'search_items'       => __('Search Speakers',            'nsru-options'),
            'not_found'          => __('No Speakers Found',          'nsru-options'),
            'not_found_in_trash' => __('No Speakers Found In Trash', 'nsru-options'),
            'all_items'          => __('All Speakers',               'nsru-options'),
            'archives'           => __('Speaker Archives',           'nsru-options'),
            'attributes'         => __('Speaker Attributes',         'nsru-options'),
        );
        if (version_compare($wp_version, '5.0', '>=')) {
            $labels['item_published']           = __('Speaker published',           'nsru-options');
            $labels['item_published_privately'] = __('Speaker published privately', 'nsru-options');
            $labels['item_reverted_to_draft']   = __('Speaker reverted to draft',   'nsru-options');
            $labels['item_scheduled']           = __('Speaker scheduled',           'nsru-options');
            $labels['item_updated']             = __('Speaker updated',             'nsru-options');
        }

        $args = array(
            'supports'            => $supports,
            'labels'              => $labels,
            'public'              => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 200,
            'menu_icon'           => 'dashicons-megaphone',
            'query_var'           => false,
            'rewrite'             => false,
            'has_archive'         => false,
            'hierachical'         => false,
            'show_in_rest'        => false,
        );

        register_post_type('nsru_speakers', $args);

    } // create_cpt

    /**
     * Add a meta box for custom arguments.
     */
    public function add_meta_box() {

        add_meta_box('speakers_section', __('Speakers Details', 'nsru-options'), array($this, 'meta_box_callback'), 'nsru_speakers');

    } // add_meta_box

    /**
     * Prints the meta box content.
     *
     * Data for the speaker is serialized in the database.
     *
     * @param WP_Post $post The object for the current post/page.
     */
    public function meta_box_callback($post) {

        // Set up nonce for later verification.
        wp_nonce_field(NSRU_Speakers::$META_BOX_DATA, NSRU_Speakers::$META_BOX_NONCE);

        // Get and unserialize the meta data
        $data = get_post_meta($post->ID, NSRU_Speakers::$META_DATA_KEY, true);

        // Check for data and set to a rational value if it doesn't exist
        $name     = '';
        $city     = '';
        $province = '';
        $org      = NSRU_Speakers::$NO_ORG;

        // Serialized data will be in an array
        if (is_array($data)) {
            $name     = (array_key_exists('name', $data))     ? $data['name']        : '';
            $city     = (array_key_exists('city', $data))     ? $data['city']        : '';
            $province = (array_key_exists('province', $data)) ? $data['province']    : '';
            $org      = (array_key_exists('org', $data))      ? intval($data['org']) : NSRU_Speakers::$NO_ORG;
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
                <th scope="row"><?php echo __('City:', 'nsru-options'); ?></th>
                <td><input type="text" id="city" name="city" class="regular-text" value="<?php echo esc_attr($city); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Province/State:', 'nsru-options'); ?></th>
                <td><input type="text" id="province" name="province" class="regular-text" value="<?php echo esc_attr($province); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Organization:', 'nsru-options'); ?></th>
                <td>
                    <select id="speaker_org" name="org" class="regular-text">
                        <option value="<?php echo NSRU_Speakers::$NO_ORG; ?>"     <?php echo (NSRU_Speakers::$NO_ORG === $org)     ? 'selected' : '' ?>><?php echo __('Select an organization', 'nsru-options'); ?></option>
                        <option value="<?php echo NSRU_Speakers::$AA_ORG; ?>"     <?php echo (NSRU_Speakers::$AA_ORG === $org)     ? 'selected' : '' ?>><?php echo __('AA', 'nsru-options'); ?></option>
                        <option value="<?php echo NSRU_Speakers::$ALANON_ORG; ?>" <?php echo (NSRU_Speakers::$ALANON_ORG === $org) ? 'selected' : '' ?>><?php echo __('AlAnon', 'nsru-options'); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <?php
    } // meta_box_callback

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta_box_data($post_id) {

        if (!isset($_POST[NSRU_Speakers::$META_BOX_NONCE])) {
            return;
        }

        if (!wp_verify_nonce($_POST[NSRU_Speakers::$META_BOX_NONCE], NSRU_Speakers::$META_BOX_DATA)) {
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
            'name'     => sanitize_text_field($_POST['name']),
            'city'     => sanitize_text_field($_POST['city']),
            'province' => sanitize_text_field($_POST['province']),
            'org'      => sanitize_text_field($_POST['org']),
        );

        update_post_meta($post_id, NSRU_Speakers::$META_DATA_KEY, $my_data);

    } // save_meta_box_data

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

        $columns['name']     = __('Name', 'nsru-options');
        $columns['city']     = __('City', 'nsru-options');
        $columns['province'] = __('Province', 'nsru-options');
        $columns['org']      = __('Organization', 'nsru-options');
        $columns['date']     = __('Date', 'nsru-options');

        return $columns;

    } // set_custom_edit_columns

    /**
     * Echos the meta data for the appropriate column.
     *
     * @param string $column Column name.
     * @param int $post_id   Post ID.
     */
    public function custom_column($column, $post_id) {

        $meta = get_post_meta($post_id, NSRU_Speakers::$META_DATA_KEY);
        switch ($column) {
            case 'name':
                echo $meta[0]['name'];
                break;
            case 'city':
                echo $meta[0]['city'];
                break;
            case 'province':
                echo $meta[0]['province'];
                break;
            case 'org':
                $org = intval($meta[0]['org']);
                if (NSRU_Speakers::$AA_ORG === $org) {
                    echo __('AA', 'nsru-options');
                } elseif (NSRU_Speakers::$ALANON_ORG === $org) {
                    echo __('AlAnon', 'nsru-options');
                } else {
                    echo '';
                }
        }

    } // custom_column

} // class NSRU_Speakers

new NSRU_Speakers();
