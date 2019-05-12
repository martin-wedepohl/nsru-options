<?php

/**
 * Custom Post Type for the North Shore Round Up Meetings
 *
 * @package   NSRU_Options
 * @author    Original Author <martin.wedepohl@shaw.ca>
 * @copyright 2019 Wedepohl Engineering
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('ABSPATH') or die('');

/**
 * Class for the North Shore Round Up Meetings.
 */
class NSRU_Meetings {

    private static $META_DATA_KEY  = '_meta_meetings_data';
    private static $META_BOX_DATA  = 'nsru_meetings_save_meta_box_data';
    private static $META_BOX_NONCE = 'nsru_meetings_meta_box_nonce';

    /**
     * Constructor for the class.
     *
     * Adds all the actions and filters used by the custom post type.
     */
    public function __construct() {

        add_action('init', array($this, 'create_cpt'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_filter('manage_nsru_meetings_posts_columns', array($this, 'set_custom_edit_columns'));
        add_action('manage_nsru_meetings_posts_custom_column', array($this, 'custom_column'), 10, 2);

    } // __construct

    /**
     * Returns the meta data key for the custom post type.
     *
     * @return string
     */
    public static function GetMetaKey() {

        return NSRU_Meetings::$META_DATA_KEY;

    } // GetMetaKey

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
            'name'               => __('Meetings', 'nsru-options'),
            'singular_name'      => __('Meeting', 'nsru-options'),
            'add_new'            => __('Add New Meeting', 'nsru-options'),
            'add_new_item'       => __('Add New Meeting', 'nsru-options'),
            'edit_item'          => __('Edit Meeting', 'nsru-options'),
            'new_item'           => __('New Meeting', 'nsru-options'),
            'view_item'          => __('View Meeting', 'nsru-options'),
            'view_items'         => __('View Meetings', 'nsru-options'),
            'search_items'       => __('Search Meetings', 'nsru-options'),
            'not_found'          => __('No Meetings Found', 'nsru-options'),
            'not_found_in_trash' => __('No Meetings Found In Trash', 'nsru-options'),
            'all_items'          => __('All Meetings', 'nsru-options'),
            'archives'           => __('Meeting Archives', 'nsru-options'),
            'attributes'         => __('Meeting Attributes', 'nsru-options'),
        );
        if (version_compare($wp_version, '5.0', '>=')) {
            $labels['item_published']           = __('Meeting published', 'nsru-options', 'nsru-options');
            $labels['item_published_privately'] = __('Meeting published privately', 'nsru-options');
            $labels['item_reverted_to_draft']   = __('Meeting reverted to draft', 'nsru-options');
            $labels['item_scheduled']           = __('Meeting scheduled', 'nsru-options');
            $labels['item_updated']             = __('Meeting updated', 'nsru-options');
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
            'menu_icon'           => 'dashicons-calendar-alt',
            'query_var'           => false,
            'rewrite'             => false,
            'has_archive'         => false,
            'hierachical'         => false,
            'show_in_rest'        => false,
        );

        register_post_type('nsru_meetings', $args);

    } // create_cpt

    /**
     * Add a meta box for custom arguments.
     */
    public function add_meta_box() {

        add_meta_box('meetings_section', __('Meetings Details', 'nsru-options'), array($this, 'meta_box_callback'), 'nsru_meetings');

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
        wp_nonce_field(NSRU_Meetings::$META_BOX_DATA, NSRU_Meetings::$META_BOX_NONCE);

        // Get and unserialize the meta data
        $data = get_post_meta($post->ID, NSRU_Meetings::$META_DATA_KEY, true);

        // Check for data and set to a rational value if it doesn't exist
        $meeting_date    = '';
        $start_time_hrs  = '';
        $start_time_mins = '';
        $stop_time_hrs   = '';
        $stop_time_mins  = '';
        $topic           = '';
        $speakers        = '';
        $hosted_by       = '';
        $room            = '';

        // Serialized data will be in an array
        if (is_array($data)) {
            $meeting_date = (array_key_exists('meeting_date', $data)) ? $data['meeting_date'] : '';
            $start_time   = (array_key_exists('start_time', $data))   ? $data['start_time']   : '';
            if ('' !== $start_time) {
                $parts           = preg_split("/[:]+/", $start_time);
                $start_time_hrs  = $parts[0];
                $start_time_mins = $parts[1];
            }
            $stop_time = (array_key_exists('stop_time', $data)) ? $data['stop_time'] : '';
            if ('' !== $stop_time) {
                $parts          = preg_split("/[:]+/", $stop_time);
                $stop_time_hrs  = $parts[0];
                $stop_time_mins = $parts[1];
            }
            $topic     = (array_key_exists('topic', $data))     ? $data['topic']        : '';
            $speakers  = (array_key_exists('speakers', $data))  ? $data['speakers']     : '';
            $hosted_by = (array_key_exists('hosted_by', $data)) ? $data['hosted_by']    : '';
            $room      = (array_key_exists('room', $data))      ? intval($data['room']) : '';
        }

        // Get Meeting Rooms
        $posts = get_posts(array(
            'post_type'   => 'nsru_rooms',
            'post_status' => 'publish',
            'numberposts' => -1,
            'order'       => 'DESC',
        ));

        $rooms_select = '<select id="room" name="room" class="regular-text"><option value="">Select a room</option>';
        foreach ($posts as $post) {
            $selected      = ($post->ID === $room) ? 'selected' : '';
            $rooms_select .= '<option value="' . $post->ID . '"' . ' ' . $selected . '>' . get_the_title($post->ID) . '</option>';
        }
        $rooms_select .= '</select>';

        // Get Main Speakers
        $posts = get_posts(array(
            'post_type'   => 'nsru_speakers',
            'post_status' => 'publish',
            'numberposts' => -1,
            'order'       => 'ASC',
        ));

        $main_speakers = '<h2>Main Speakers</h2><p>';
        foreach ($posts as $post) {
            $meta = get_post_meta($post->ID, NSRU_Speakers::GetMetaKey());
            if (null !== $meta[0]['name'] && '' !== $meta[0]['name']) {
                switch ($meta[0]['org']) {
                    case NSRU_Speakers::GetAAValue():
                        $org = __('(AA)', 'nsru-options');
                        break;
                    case NSRU_Speakers::GetAlAnonValue():
                        $org = __('(AlAnon)', 'nsru-options');
                        break;
                    default:
                        $org = '';
                }

                $main_speakers .= $meta[0]['name'] . ', ' . $meta[0]['city'] . ', ' . $meta[0]['province'] . ' ' . $org . '<br />';
            }
        }
        $main_speakers .= '</p>';
        echo $main_speakers;
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php echo __('Date:', 'nsru-options'); ?></th>
                <td>
                    <input type="text" id="meeting_date" name="meeting_date" class="nsrudate" value="<?php echo esc_attr($meeting_date); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Start Time:', 'nsru-options'); ?></th>
                <td>
                    <?php echo $this->create_time_select('hrs', 'start_time_hrs', esc_attr($start_time_hrs)); ?>
                    :
        <?php echo $this->create_time_select('mins', 'start_time_mins', esc_attr($start_time_mins)); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Stop Time:', 'nsru-options'); ?></th>
                <td>
                    <?php echo $this->create_time_select('hrs', 'stop_time_hrs', esc_attr($stop_time_hrs)); ?>
                    :
        <?php echo $this->create_time_select('mins', 'stop_time_mins', esc_attr($stop_time_mins)); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Topic:', 'nsru-options'); ?></th>
                <td>
                    <input type="text" id="topic" name="topic" class="width-100" value="<?php echo esc_attr($topic); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Speakers:', 'nsru-options'); ?></th>
                <td>
                    <input type="text" id="speakers" name="speakers" class="width-100" value="<?php echo esc_attr($speakers); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Hosted By:', 'nsru-options'); ?></th>
                <td>
                    <input type="text" id="hosted_by" name="hosted_by" class="width-100" value="<?php echo esc_attr($hosted_by); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __('Rooms:', 'nsru-options'); ?></th>
                <td>
        <?php echo $rooms_select; ?>
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

        if (!isset($_POST[NSRU_Meetings::$META_BOX_NONCE])) {
            return;
        }

        if (!wp_verify_nonce($_POST[NSRU_Meetings::$META_BOX_NONCE], NSRU_Meetings::$META_BOX_DATA)) {
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

        $hrs        = trim(sanitize_text_field($_POST['start_time_hrs']));
        $mins       = trim(sanitize_text_field($_POST['start_time_mins']));
        $hrs        = (2 === strlen($hrs))  ? $hrs  : (1 === strlen($hrs))  ? '0' . $hrs : '';
        $mins       = (2 === strlen($mins)) ? $mins : (1 === strlen($mins)) ? '0' . $mins : '';
        $start_time = $hrs . ':' . $mins;

        $hrs  = trim(sanitize_text_field($_POST['stop_time_hrs']));
        $mins = trim(sanitize_text_field($_POST['stop_time_mins']));
        $hrs  = (2 === strlen($hrs))  ? $hrs  : (1 === strlen($hrs))  ? '0' . $hrs : '';
        $mins = (2 === strlen($mins)) ? $mins : (1 === strlen($mins)) ? '0' . $mins : '';
        $stop_time = $hrs . ':' . $mins;
        if (':' === $stop_time) {
            $stop_time = '';
        }

        $my_data = array(
            'meeting_date' => sanitize_text_field($_POST['meeting_date']),
            'start_time'   => sanitize_text_field($_POST['start_time_hrs']) . ':' . sanitize_text_field($_POST['start_time_mins']),
            'stop_time'    => sanitize_text_field($_POST['stop_time_hrs'])  . ':' . sanitize_text_field($_POST['stop_time_mins']),
            'topic'        => sanitize_text_field($_POST['topic']),
            'speakers'     => sanitize_text_field($_POST['speakers']),
            'hosted_by'    => sanitize_text_field($_POST['hosted_by']),
            'room'         => sanitize_text_field($_POST['room']),
        );

        update_post_meta($post_id, NSRU_Meetings::$META_DATA_KEY, $my_data);

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

        $columns['meeting_date'] = __('Meeting Date', 'nsru-options');
        $columns['start_time']   = __('Start Time', 'nsru-options');
        $columns['stop_time']    = __('Stop Time', 'nsru-options');
        $columns['topic']        = __('Topic', 'nsru-options');
        $columns['speaker']      = __('Speakers', 'nsru-options');
        $columns['hosted_by']    = __('Hosted By', 'nsru-options');
        $columns['room']         = __('Room', 'nsru-options');
        $columns['date']         = __('Date', 'nsru-options');

        return $columns;

    } // set_custom_edit_columns

    /**
     * Echos the meta data for the appropriate column.
     *
     * @param string $column Column name.
     * @param int $post_id   Post ID.
     */
    public function custom_column($column, $post_id) {

        $meta = get_post_meta($post_id, NSRU_Meetings::$META_DATA_KEY);

        switch ($column) {
            case 'meeting_date':
                $meeting_date = strtotime($meta[0]['meeting_date']);
                if (false === $meeting_date) {
                    $meeting_date = '';
                } else {
                    $meeting_date = date('F j, Y', $meeting_date);
                }
                echo $meeting_date;
                break;
            case 'start_time':
                $start_time = strtotime($meta[0]['start_time'] . ':00');
                if (false === $start_time) {
                    $start_time = '';
                } else {
                    $start_time = date('g:i a', $start_time);
                }
                echo $start_time;
                break;
            case 'stop_time':
                $stop_time = strtotime($meta[0]['stop_time'] . ':00');
                if (false === $stop_time) {
                    $stop_time = '';
                } else {
                    $stop_time = date('g:i a', $stop_time);
                    if ('12:00 am' === $stop_time) {
                        $stop_time = 'midnight';
                    }
                }
                echo $stop_time;
                break;
            case 'topic':
                echo $meta[0]['topic'];
                break;
            case 'speaker':
                echo $meta[0]['speakers'];
                break;
            case 'hosted_by':
                echo $meta[0]['hosted_by'];
                break;
            case 'room':
                $room = ('' === $meta[0]['room']) ? '' : get_the_title($meta[0]['room']);
                echo $room;
                break;
        }

    } // custom_column

    private function create_time_select($type, $id, $time) {
        if ('' !== $time) {
            $time = intval($time);
        }
        $stop = 24;
        $inc  = 1;
        if ('mins' === $type) {
            $stop = 60;
            $inc  = 5;
        }
        $options .= '<option value=""></option>';
        for ($i = 0; $i < $stop; $i += $inc) {
            if ($i < 10) {
                $t = '0' . $i;
            } else {
                $t = $i;
            }
            $selected = '';
            if ($time === $i) {
                $selected = 'selected';
            }
            $options .= '<option value="' . $t . '" ' . $selected . ' >' . $t . '</option>';
        }
        return '<select id="' . $id . '" name="' . $id . '">' . $options . '</select>';

    } // create_time_select

} // class NSRU_Meetings

new NSRU_Meetings();
