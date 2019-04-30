<?php
/**
 * Plugin Name: NSRU Options Plugin
 * Plugin URI:  https://northshoreroundup.com
 * Description: North Shore Round Up Options
 * Version:     1.0.0
 * Author:      martin.wedepohl@shaw.ca
 * Author URI:  http://wedepohlengineering.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nsru-options
 */
defined('ABSPATH') or die('');

class NSRU_Options_Plugin {

    /**
     * Constructor for the plugin class.
     * 
     * Performs all the enqueing, add actions and registration for the class.
     * Since this plugin uses the jQuery DatePicker that needs to be enqueued.
     */
    public function __construct() {

        wp_enqueue_style('nsru-options-css', plugin_dir_url(__FILE__) . 'dist/css/style.min.css');
        wp_enqueue_style('jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_script('nsru-options-js', plugin_dir_url(__FILE__) . 'dist/js/script.min.js', array('jquery'), '', true);

        // Required enqeue scripts for the Date Picker.
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');

        // Required javascript code for the Date Picker.
        add_action('admin_footer', array($this, 'add_admin_footer_js'), 10);

        // Hook into the admin menu.
        add_action('admin_menu', array($this, 'create_options_page'));

        // Add Settings and Fields.
        add_action('admin_init', array($this, 'setup_sections'));
        add_action('admin_init', array($this, 'setup_fields'));

        // Register the options for the database.
        register_setting('nsru-options', 'round_up_options');

        // Add Google Analytics to head
        add_action('wp_head', array($this, 'add_analytics_in_header'), 100);
    }

    /**
     * Create and add the plugin options page.
     */
    public function create_options_page() {

        // Add the menu item and page
        $page_title = __('North Shore Round Up Options', 'nsru-options');
        $menu_title = __('NSRU Options', 'nsru-options');
        $capability = 'manage_options';
        $slug = 'nsru-options';
        $callback = array($this, 'settings_page_content');
        $icon = 'dashicons-admin-generic';
        $position = 100;

        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
    }

    /**
     * Add the sections used on the options page.
     */
    public function setup_sections() {

        add_settings_section('dates_section', __('Round Up Dates', 'nsru-options'), array($this, 'section_callback'), 'nsru-options');
        add_settings_section('prices_section', __('Round Up Prices', 'nsru-options'), array($this, 'section_callback'), 'nsru-options');
        add_settings_section('discounts_section', __('Round Up Discounts', 'nsru-options'), array($this, 'section_callback'), 'nsru-options');
        add_settings_section('hotel_section', __('Round Up Hotel', 'nsru-options'), array($this, 'section_callback'), 'nsru-options');
        add_settings_section('enable_section', __('Enable NSRU Options', 'nsru-options'), array($this, 'section_callback'), 'nsru-options');
        add_settings_section('tracking_section', __('Tracking Codes', 'nsru-options'), array($this, 'section_callback'), 'nsru-options');
    }

    /**
     * Create the fields and assign them to a specific section.
     */
    public function setup_fields() {

        $fields = array(
            array(
                'uid' => 'first_year',
                'label' => __('Round Up First Year', 'nsru-options'),
                'section' => 'dates_section',
                'type' => 'number',
                'min' => 1935,
                'max' => 2200,
                'step' => 1,
                'class' => 'regular-text',
                'helper' => __('(first year of Round Up)', 'nsru-options'),
            ),
            array(
                'uid' => 'start_date',
                'label' => __('Start Date for Round Up', 'nsru-options'),
                'section' => 'dates_section',
                'type' => 'text',
                'class' => 'nsrudate regular-text',
                'placeholder' => __('Start Date', 'nsru-options'),
            ),
            array(
                'uid' => 'start_time',
                'label' => __('Start Time for Round Up', 'nsru-options'),
                'section' => 'dates_section',
                'type' => 'number',
                'min' => 0,
                'max' => 23,
                'step' => 1,
                'class' => 'regular-text',
                'helper' => __('(start hour of Round Up)', 'nsru-options'),
            ),
            array(
                'uid' => 'end_date',
                'label' => __('End Date for Round Up', 'nsru-options'),
                'section' => 'dates_section',
                'type' => 'text',
                'class' => 'nsrudate regular-text',
                'placeholder' => __('End Date', 'nsru-options'),
            ),
            array(
                'uid' => 'end_time',
                'label' => __('End Time for Round Up', 'nsru-options'),
                'section' => 'dates_section',
                'type' => 'number',
                'min' => 0,
                'max' => 23,
                'step' => 1,
                'class' => 'regular-text',
                'helper' => __('(end hour of Round Up)', 'nsru-options'),
            ),
            array(
                'uid' => 'ticket_price',
                'label' => __('Ticket price for Round Up ($)', 'nsru-options'),
                'section' => 'prices_section',
                'type' => 'number',
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'class' => 'regular-text',
                'helper' => __('(Actual ticket price)', 'nsru-options'),
            ),
            array(
                'uid' => 'online_surcharge',
                'label' => __('PayPal Surcharge ($)', 'nsru-options'),
                'section' => 'prices_section',
                'type' => 'number',
                'min' => 0,
                'max' => 5,
                'step' => 0.01,
                'class' => 'regular-text',
            ),
            array(
                'uid' => 'paypal_code',
                'label' => __('PayPal Purchase Code', 'nsru-options'),
                'section' => 'prices_section',
                'type' => 'text',
                'class' => 'regular-text',
                'supplimental' => __('Found in the hosted_button_id value of the PayPal Buy Now button'),
            ),
            array(
                'uid' => 'discount_end_date',
                'label' => __('End Date for discounted tickets', 'nsru-options'),
                'section' => 'discounts_section',
                'type' => 'text',
                'class' => 'nsrudate regular-text',
            ),
            array(
                'uid' => 'ticket_price_discount',
                'label' => __('Discounted ticket price for Round Up ($)', 'nsru-options'),
                'section' => 'discounts_section',
                'type' => 'number',
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'class' => 'regular-text',
                'helper' => __('(Discounted ticket price)', 'nsru-options'),
            ),
            array(
                'uid' => 'online_surcharge_discount',
                'label' => __('Discount Ticket PayPal Surcharge ($)', 'nsru-options'),
                'section' => 'discounts_section',
                'type' => 'number',
                'min' => 0,
                'max' => 5,
                'step' => 0.01,
                'class' => 'regular-text',
            ),
            array(
                'uid' => 'paypal_code_discount',
                'label' => __('PayPal Discount Purchase Code', 'nsru-options'),
                'section' => 'discounts_section',
                'type' => 'text',
                'class' => 'regular-text',
                'supplimental' => __('Found in the hosted_button_id value of the PayPal Buy Now button'),
            ),
            array(
                'uid' => 'hotel_booking_code',
                'label' => __('Hotel Booking Code', 'nsru-options'),
                'section' => 'hotel_section',
                'type' => 'text',
                'class' => 'regular-text',
            ),
            array(
                'uid' => 'hotel_booking_website',
                'label' => __('Hotel Booking Website', 'nsru-options'),
                'section' => 'hotel_section',
                'type' => 'text',
                'class' => 'regular-text',
            ),
            array(
                'uid' => 'hotel_special_price',
                'label' => __('Hotel Special Price ($)', 'nsru-options'),
                'section' => 'hotel_section',
                'type' => 'number',
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'class' => 'regular-text',
            ),
            array(
                'uid' => 'hotel_harbour_special_price',
                'label' => __('Hotel Harbour View Special Price ($)', 'nsru-options'),
                'section' => 'hotel_section',
                'type' => 'number',
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'class' => 'regular-text',
            ),
            array(
                'uid' => 'paypal_enable',
                'label' => __('Enable PayPal Button', 'nsru-options'),
                'section' => 'enable_section',
                'type' => 'checkbox',
                'options' => array(1 => 'Yes'),
            ),
            array(
                'uid' => 'hotel_enable',
                'label' => __('Enable Hotel Room Rate', 'nsru-options'),
                'section' => 'enable_section',
                'type' => 'checkbox',
                'options' => array(1 => 'Yes'),
            ),
            array(
                'uid' => 'hotel_harbour_enable',
                'label' => __('Enable Harbour View Room Rate', 'nsru-options'),
                'section' => 'enable_section',
                'type' => 'checkbox',
                'options' => array(1 => 'Yes'),
            ),
            array(
                'uid' => 'analytics_code',
                'label' => __('Google Analytics Code', 'nsru-options'),
                'section' => 'tracking_section',
                'type' => 'text',
                'class' => 'regular-text',
            ),
        );

        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), 'nsru-options', $field['section'], $field);
        }
    }

// End setup_fields

    /**
     * Display a specific field.
     * 
     * Options are stored in serialized format in the database.
     * The value is extracted and used if it exists already in the database.
     * 
     * Based on the type of field print the appropriate field.
     * 
     * @param array $arguments Arguments for the field.
     */
    public function field_callback($arguments) {
        $value = '';
        $options = get_option('round_up_options');
        if (is_array($options)) {
            if (array_key_exists($arguments['uid'], $options)) {
                $value = $options[$arguments['uid']];
            }
        }

        switch ($arguments['type']) {
            case 'text':
            case 'password':
                printf('<input name="round_up_options[%1$s]" id="%1$s" class="%5$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $arguments['class']);
                break;
            case 'number':
                printf('<input name="round_up_options[%1$s]" id="%1$s" class="%5$s" min="%6$f" max="%7$f" step="%8$f" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $arguments['class'], $arguments['min'], $arguments['max'], $arguments['step']);
                break;
            case 'textarea':
                printf('<textarea name="round_up_options[%1$s]" id="%1$s" class="%4$s" minplaceholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value, $arguments['class']);
                break;
            case 'select':
            case 'multiselect':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $attributes = '';
                    $options_markup = '';
                    foreach ($arguments['options'] as $key => $label) {
                        $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value[array_search($key, $value, true)], $key, false), $label);
                    }
                    if ($arguments['type'] === 'multiselect') {
                        $attributes = ' multiple="multiple" ';
                    }
                    printf('<select name="round_up_options[%1$s]" id="%1$s" class="%4$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup, $arguments['class']);
                }
                break;
            case 'radio':
            case 'checkbox':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $options_markup = '';
                    $iterator = 0;
                    foreach ($arguments['options'] as $key => $label) {
                        $iterator++;
                        $options_markup .= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="round_up_options[%1$s]" class="%7$s" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked($value, $key, false), $label, $iterator, $arguments['class']);
                    }
                    printf('<fieldset>%s</fieldset>', $options_markup);
                }
                break;
        }
        if ($helper = $arguments['helper']) {
            printf('<span class="helper"> %s</span>', $helper);
        }
        if ($supplimental = $arguments['supplimental']) {
            printf('<p class="description">%s</p>', $supplimental);
        }
    }

// End field_callback

    /**
     * HTML page used to display the options.
     */
    public function settings_page_content() {
        require_once 'includes/nsru-options-settings.php';
    }

    /**
     * Callback to display the section.
     * 
     * Not used since an HTML page (above is used)
     * 
     * @param array $arguments
     */
    public function section_callback($arguments) {
        
    }

    /**
     * Add the datepicker script to the footer.
     */
    function add_admin_footer_js() {
        ?>
        <script>
            /* <![CDATA[ */
            jQuery(document).ready(function($) {
                $('.nsrudate').datepicker({dateFormat:'yy-mm-dd'});
            });
            /* ]]> */
        </script>
        <?php
    }

    /**
     * Add the script to the footer.
     */
    function add_footer_js() {
        ?>
        <script>
//            /* <![CDATA[ */
//            jQuery(document).ready(function ($) {
//                $.ajax({
//                    url: 'ajax/round_up_dates.php', 
//                    data: {action: 'days_to_round_up'}, 
//                    type: 'POST', 
//                    success: function ($result) {
//                        if ($result) {
//                            $('.days_to_round_up').html($result);
//                            $( document.body ).trigger( 'post-load' );
//                        }
//                    }
//                });
//                
//                $.ajax({
//                    url: 'ajax/speakers.php', 
//                    data: {action: 'get_speakers'}, 
//                    type: 'POST', 
//                    success: function ($result) {
//                        if ($result) {
//                            $('.speakers').html($result);
//                            $( document.body ).trigger( 'post-load' );
//                        }
//                    }
//                });
//                
//                $.ajax({
//                    url: 'ajax/round_up_dates.php', 
//                    data: {action: 'get_annual'}, 
//                    type: 'POST', 
//                    success: function ($result) {
//                        if ($result) {
//                            $('.annual').html($result);
//                            $( document.body ).trigger( 'post-load' );
//                        }
//                    }
//                });
//                
//                $.ajax({
//                    url: 'ajax/round_up_dates.php', 
//                    data: {action: 'get_round_up_dates'}, 
//                    type: 'POST', 
//                    success: function ($result) {
//                        if ($result) {
//                            $('.round_up_dates').html($result);
//                            $( document.body ).trigger( 'post-load' );
//                        }
//                    }
//                });
//                
//                $.ajax({
//                    url: '/wp-admin/meetings.php', 
//                    data: {action: 'get_meetings'}, 
//                    type: 'POST', 
//                    success: function ($result) {
//                        if ($result) {
//                            $('.meetings').html($result);
//                            $( document.body ).trigger( 'post-load' );
//                        }
//                    }
//                });
//                
//            });
//            /* ]]> */
        </script>
        <?php
    }

    /**
     * Add the Google Analytics Script into the header
     */
    function add_analytics_in_header() {

        $round_up_options = get_option('round_up_options');
        $analytics_code = is_array($round_up_options) ? ( array_key_exists('analytics_code', $round_up_options) ? $round_up_options['analytics_code'] : '' ) : '';
        if ('' !== $analytics_code) {
            ?>
            <!-- Google Analytics -->
            <script>
                /* <![CDATA[ */
                var analytics_code =<?php echo json_encode($analytics_code); ?>;
                window.ga = window.ga || function () {
                    (ga.q = ga.q || []).push(arguments)
                };
                ga.l = +new Date;ga('create', analytics_code, 'auto');
                ga('send', 'pageview');
                /* ]]> */
            </script>
            <script async src='https://www.google-analytics.com/analytics.js'></script>
            <!-- End Google Analytics -->
            <?php
        }
    }

    
}

// End class NSRU_Options_Plugin

new NSRU_Options_Plugin();

// All the reuqired files used by this plugin.
require_once 'includes/shortcodes.php';
require_once 'includes/cpt/committee_cpt.php';
require_once 'includes/cpt/pastchairs_cpt.php';
require_once 'includes/cpt/meetings_cpt.php';
require_once 'includes/cpt/speakers_cpt.php';
require_once 'includes/cpt/rooms_cpt.php';
require_once 'includes/ajax/round_up_dates.php';
require_once 'includes/ajax/speakers.php';
require_once 'includes/ajax/meetings.php';
