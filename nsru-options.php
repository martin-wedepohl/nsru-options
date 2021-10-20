<?php
/**
 * Plugin Name: NSRU Options Plugin
 * Plugin URI:  https://github.com/martin-wedepohl/nsru-options
 * Description: North Shore Round Up Options
 * Version:     1.0.10
 * Author:      martin.wedepohl@shaw.ca
 * Author URI:  http://wedepohlengineering.com
 * License:     GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: nsru-options
 */

defined('ABSPATH') || die('');

/**
 * North Shore Round Up Options Plugin
 */
class NSRU_Options_Plugin {

	/**
	 * Constructor for the plugin class.
	 *
	 * Performs all the enqueing, add actions and registration for the class.
	 * Since this plugin uses the jQuery DatePicker that needs to be enqueued.
	 */
	public function __construct() {

		// Enqueue all the scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add a settings link.
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_link' ) );

		// Required javascript code for the Date Picker.
		add_action( 'admin_footer', array( $this, 'add_admin_footer_js' ), 10 );

		// Hook into the admin menu.
		add_action( 'admin_menu', array( $this, 'create_options_page' ) );

		// Custom submenu order.
		add_filter( 'custom_menu_order', array( $this, 'reorder_submenu' ) );

		// Add Settings and Fields.
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );

		// Register the options for the database.
		register_setting( 'nsru-options', 'round_up_options' );

		// Add Google Analytics to head.
		add_action( 'wp_head', array( $this, 'add_analytics_in_header' ), 0 );

		// Add custom CSS to head.
		add_action( 'wp_head', array( $this, 'add_custom_css_in_header' ), 100 );

	} // __construct

	/**
	 * Enqueue all the required styles and scripts for the admin side
	 */
	public function enqueue_admin_scripts() {

		wp_enqueue_style('nsru-options-css', plugin_dir_url(__FILE__) . 'dist/css/style.min.css');
		wp_enqueue_style('jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

		// Required enqeue scripts for the Date Picker.
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');

	} // enqueue_admin_scripts

	/**
	 * Enqueue all the required styles and scripts for the client side
	 */
	public function enqueue_scripts() {

		wp_enqueue_script('nsru-options-js', plugin_dir_url(__FILE__) . 'dist/js/script.min.js', array('jquery'), '', true);

	} // enqueue_scripts

	public function plugin_action_link($links) {

		$links[] = '<a href="' . admin_url('admin.php?page=nsru-options') . '">' . __('Settings', 'nsru-options') . '</a>';
		$links[] = '<a href="' . admin_url('admin.php?page=nsru-instructions') . '">' . __('Instructions', 'nsru-options') . '</a>';

		return $links;

	} // plugin_action_link

	/**
	 * Create and add the plugin options page.
	 */
	public function create_options_page() {

		// Add the menu item and page.
		$page_title = __('North Shore Round Up Settings', 'nsru-options');
		$menu_title = __('NSRU Options', 'nsru-options');
		$slug       = 'nsru-options';
		$callback   = array($this, 'settings_page_content');
		$icon       = 'dashicons-admin-generic';
		$position   = 100;
		add_menu_page($page_title, $menu_title, 'edit_others_posts', $slug, $callback, $icon, $position);

		$page_title = __('North Shore Round Up Settings', 'nsru-options');
		$menu_title = __('Settings', 'nsru-options');
		add_submenu_page($slug, $page_title, $menu_title, 'manage_options', $slug, $callback);

		$page_title = __('North Shore Round Up Instructions', 'nsru-options');
		$menu_title = __('Instructions', 'nsru-options');
		$callback   = array($this, 'instructions_page_content');
		add_submenu_page($slug, $page_title, $menu_title, 'edit_others_posts', 'nsru-instructions', $callback);
		
	} // create_options_page

	/**
	 * Reorder the submenu so that the NSRU Options is the first item in the submenu
	 *
	 * @global array $submenu The submenu
	 *
	 * @param type $menu_order
	 */
	public function reorder_submenu( $menu_order ) {
		
		global $submenu;

		$custom      = null;
		$search_item = __('Settings', 'nsru-options');

		if ( !empty( $submenu ) ) {
			foreach ( $submenu['nsru-options'] as $index => $item ) {
				if ( $search_item === $item[0] ) {
					$custom = $item;
					unset( $submenu['nsru-options'][$index] );
					break;
				}
			}

			if ( null !== $custom ) {
				// Push to beginning of array
				array_unshift( $submenu['nsru-options'], $custom );
			}
		}

		return $menu_order;
		
	} // reorder_submenu

	/**
	 * Add the sections used on the options page.
	 */
	public function setup_sections() {

		add_settings_section(
			'color_section',
			__( 'NSRU Colors', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'enable_section',
			__( 'Enable NSRU Options', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'dates_section',
			__( 'Round Up Dates', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'prices_section',
			__( 'Round Up Prices', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'discounts_section',
			__( 'Round Up Discounts', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'location_section',
			__( 'Round Up Location', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'hotel_section',
			__( 'Round Up Hotel', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'special_rate_section',
			__( 'Round Up Special Room Rates', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		add_settings_section(
			'tracking_section',
			__( 'Tracking Codes', 'nsru-options' ),
			array( $this, 'section_callback' ),
			'nsru-options'
		);
		
	} // setup_sections

	/**
	 * Create the fields and assign them to a specific section.
	 */
	public function setup_fields() {

		$fields = array(
			array(
				'uid'         => 'main_color',
				'label'       => __( 'Main Color', 'nsru-options' ),
				'section'     => 'color_section',
				'type'        => 'text',
				'class'       => 'nsru-color-field',
				'placeholder' => 'Enter Color',
			),
			array(
				'uid'     => 'paypal_enable',
				'label'   => __( 'Enable PayPal Button', 'nsru-options' ),
				'section' => 'enable_section',
				'type'    => 'checkbox',
				'options' => array( 1 => 'Yes' ),
			),
			array(
				'uid' => 'hotel_enable',
				'label' => __('Enable Deluxe Hotel Rooms', 'nsru-options'),
				'section' => 'enable_section',
				'type' => 'checkbox',
				'options' => array(1 => 'Yes'),
			),
			array(
				'uid'     => 'hotel_harbour_enable',
				'label'   => __( 'Enable Deluxe Harbour View Rooms', 'nsru-options' ),
				'section' => 'enable_section',
				'type'    => 'checkbox',
				'options' => array( 1 => 'Yes' ),
			),
			array(
				'uid'     => 'first_year',
				'label'   => __( 'Round Up First Year', 'nsru-options' ),
				'section' => 'dates_section',
				'type'    => 'number',
				'min'     => 1935,
				'max'     => 2200,
				'step'    => 1,
				'class'   => 'regular-text',
				'helper'  => __( '(first year of Round Up)', 'nsru-options' ),
			),
			array(
				'uid'         => 'start_date',
				'label'       => __( 'Start Date for Round Up', 'nsru-options' ),
				'section'     => 'dates_section',
				'type'        => 'text',
				'class'       => 'nsrudate regular-text',
				'placeholder' => __( 'Start Date', 'nsru-options' ),
			),
			array(
				'uid'     => 'start_time',
				'label'   => __( 'Start Time for Round Up', 'nsru-options' ),
				'section' => 'dates_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 23,
				'step'    => 1,
				'class'   => 'regular-text',
				'helper'  => __( '(start hour of Round Up)', 'nsru-options' ),
			),
			array(
				'uid'         => 'end_date',
				'label'       => __( 'End Date for Round Up', 'nsru-options' ),
				'section'     => 'dates_section',
				'type'        => 'text',
				'class'       => 'nsrudate regular-text',
				'placeholder' => __( 'End Date', 'nsru-options' ),
			),
			array(
				'uid'     => 'end_time',
				'label'   => __( 'End Time for Round Up', 'nsru-options' ),
				'section' => 'dates_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 23,
				'step'    => 1,
				'class'   => 'regular-text',
				'helper'  => __( '(end hour of Round Up)', 'nsru-options' ),
			),
			array(
				'uid'     => 'ticket_price',
				'label'   => __( 'Ticket price for Round Up ($)', 'nsru-options' ),
				'section' => 'prices_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 200,
				'step'    => 1,
				'class'   => 'regular-text',
				'helper'  => __( '(Actual ticket price)', 'nsru-options' ),
			),
			array(
				'uid'     => 'online_surcharge',
				'label'   => __( 'PayPal Surcharge ($)', 'nsru-options' ),
				'section' => 'prices_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 5,
				'step'    => 0.01,
				'class'   => 'regular-text',
			),
			array(
				'uid'          => 'paypal_code',
				'label'        => __( 'PayPal Purchase Code', 'nsru-options' ),
				'section'      => 'prices_section',
				'type'         => 'text',
				'class'        => 'regular-text',
				'supplimental' => __( 'Found in the hosted_button_id value of the PayPal Buy Now button' ),
			),
			array(
				'uid'     => 'discount_end_date',
				'label'   => __( 'End Date for discounted tickets', 'nsru-options' ),
				'section' => 'discounts_section',
				'type'    => 'text',
				'class'   => 'nsrudate regular-text',
			),
			array(
				'uid'     => 'ticket_price_discount',
				'label'   => __( 'Discounted ticket price for Round Up ($)', 'nsru-options' ),
				'section' => 'discounts_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 200,
				'step'    => 1,
				'class'   => 'regular-text',
				'helper'  => __( '(Discounted ticket price)', 'nsru-options' ),
			),
			array(
				'uid'     => 'online_surcharge_discount',
				'label'   => __( 'Discount Ticket PayPal Surcharge ($)', 'nsru-options' ),
				'section' => 'discounts_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 5,
				'step'    => 0.01,
				'class'   => 'regular-text',
			),
			array(
				'uid'          => 'paypal_code_discount',
				'label'        => __( 'PayPal Discount Purchase Code', 'nsru-options' ),
				'section'      => 'discounts_section',
				'type'         => 'text',
				'class'        => 'regular-text',
				'supplimental' => __( 'Found in the hosted_button_id value of the PayPal Buy Now button' ),
			),
			array(
				'uid'     => 'location_name',
				'label'   => __( 'Round Up Location Name', 'nsru-options' ),
				'section' => 'location_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'location_address',
				'label'   => __( 'Round Up Location Address', 'nsru-options' ),
				'section' => 'location_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'location_website',
				'label'   => __( 'Round Up Location Website', 'nsru-options' ),
				'section' => 'location_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_name',
				'label'   => __( 'Hotel Name', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_address',
				'label'   => __( 'Hotel Address', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_website',
				'label'   => __( 'Hotel Website', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_local_phone',
				'label'   => __( 'Hotel Local Phone Number', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_canadian_toll_free',
				'label'   => __( 'Hotel Toll Free (Canadian) Number', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_us_toll_free',
				'label'   => __( 'Hotel Toll Free (USA) Number', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_fax',
				'label'   => __( 'Hotel Fax Number', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_email',
				'label'   => __( 'Hotel Email', 'nsru-options' ),
				'section' => 'hotel_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_booking_code',
				'label'   => __( 'Hotel Booking Code', 'nsru-options' ),
				'section' => 'special_rate_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_booking_website',
				'label'   => __( 'Hotel Booking Website', 'nsru-options' ),
				'section' => 'special_rate_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_special_price',
				'label'   => __( 'Hotel Special Price ($)', 'nsru-options' ),
				'section' => 'special_rate_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 2500,
				'step'    => 1,
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'hotel_harbour_special_price',
				'label'   => __( 'Hotel Harbour View Special Price ($)', 'nsru-options' ),
				'section' => 'special_rate_section',
				'type'    => 'number',
				'min'     => 0,
				'max'     => 2500,
				'step'    => 1,
				'class'   => 'regular-text',
			),
			array(
				'uid'     => 'analytics_code',
				'label'   => __( 'Google Analytics Code', 'nsru-options' ),
				'section' => 'tracking_section',
				'type'    => 'text',
				'class'   => 'regular-text',
			),
		);

		foreach ( $fields as $field ) {
			add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'nsru-options', $field['section'], $field );
		}
		
	} // setup_fields

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
		if ( is_array( $options ) ) {
			if ( array_key_exists( $arguments['uid'], $options ) ) {
				$value = $options[$arguments['uid']];
			}
		}

		if ( !isset( $arguments['class'] ) ) {
			$arguments['class'] = '';
		}

		if ( !isset( $arguments['placeholder'] ) ) {
			$arguments['placeholder'] = '';
		}

		if ( !isset( $arguments['helper'] ) ) {
			$arguments['helper'] = false;
		}

		if ( !isset( $arguments['supplimental'] ) ) {
			$arguments['supplimental'] = false;
		}

		switch ( $arguments['type'] ) {
			case 'text':
			case 'password':
				printf( '<input name="round_up_options[%1$s]" id="%1$s" class="%5$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $arguments['class'] );
				break;
			case 'number':
				printf( '<input name="round_up_options[%1$s]" id="%1$s" class="%5$s" min="%6$f" max="%7$f" step="%8$f" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $arguments['class'], $arguments['min'], $arguments['max'], $arguments['step'] );
				break;
			case 'textarea':
				printf( '<textarea name="round_up_options[%1$s]" id="%1$s" class="%4$s" minplaceholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value, $arguments['class'] );
				break;
			case 'select':
			case 'multiselect':
				if ( !empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
					$attributes = '';
					$options_markup = '';
					foreach ( $arguments['options'] as $key => $label ) {
						$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[array_search( $key, $value, true )], $key, false ), $label );
					}
					if ( $arguments['type'] === 'multiselect' ) {
						$attributes = ' multiple="multiple" ';
					}
					printf( '<select name="round_up_options[%1$s]" id="%1$s" class="%4$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup, $arguments['class'] );
				}
				break;
			case 'radio':
			case 'checkbox':
				if ( !empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
					$options_markup = '';
					$iterator = 0;
					foreach ( $arguments['options'] as $key => $label ) {
						$iterator++;
						$options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="round_up_options[%1$s]" class="%7$s" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked( $value, $key, false ), $label, $iterator, $arguments['class'] );
					}
					printf( '<fieldset>%s</fieldset>', $options_markup );
				}
				break;
		}
		if ( $helper = $arguments['helper'] ) {
			printf( '<span class="helper"> %s</span>', $helper );
		}
		if ( $supplimental = $arguments['supplimental'] ) {
			printf( '<p class="description">%s</p>', $supplimental );
		}
		
	} // field_callback

	/**
	 * HTML page used to display the options.
	 */
	public function settings_page_content() {

		require_once 'includes/nsru-options-settings.php';
		
	} // settings_page_content

	/**
	 * HTML page used to display the instructions.
	 */
	public function instructions_page_content() {

		require_once 'includes/nsru-options-instructions.php';
		
	} // settings_page_content

	/**
	 * Callback to display the section.
	 *
	 * Not used since an HTML page (above is used)
	 *
	 * @param array $arguments
	 */
	public function section_callback($arguments) {

	} // section_callback

	/**
	 * Add the datepicker script to the footer.
	 */
	function add_admin_footer_js() {
		
		?>
		<script>
			/* <![CDATA[ */
			jQuery(document).ready(function ($) {
				$('.nsrudate').datepicker({dateFormat: 'yy-mm-dd'});
			});
			/* ]]> */
		</script>
		<?php
		
	} // add_admin_footer_js

	/**
	 * Add the Google Analytics Script into the header
	 */
	function add_analytics_in_header() {

		$round_up_options = get_option('round_up_options');
		$analytics_code = is_array( $round_up_options ) ? ( array_key_exists( 'analytics_code', $round_up_options ) ? $round_up_options['analytics_code'] : '' ) : '';
		if ( '' !== $analytics_code ) {
			?>
			<!-- Google Analytics -->
			<script>
				/* <![CDATA[ */
				var analytics_code =<?php echo json_encode( $analytics_code ); ?>;
				window.ga = window.ga || function () {
					(ga.q = ga.q || []).push(arguments)
				};
				ga.ls = +new Date;
				ga('create', analytics_code, 'auto');
				ga('send', 'pageview');
				/* ]]> */
			</script>
			<script async src='https://www.google-analytics.com/analytics.js'></script>
			<!-- End Google Analytics -->
			<?php
		}
		
	} // add_analytics_in_header

	/**
	 * Add the Custom CSS into the header
	 */
	public function add_custom_css_in_header() {

		$round_up_options = get_option( 'round_up_options' );
		$main_color = $round_up_options['main_color'];
		?>
<style>
.header-site,.header-site.header-sticky{background-color:<?php echo $main_color;?>;}
a{color:<?php echo $main_color; ?>;}
#breadcrumbs{text-align:right;padding-right:20px;padding-bottom:10px;background-color:<?php echo $main_color; ?>;color:white;}
#breadcrumbs a{color:white;}
#breadcrumbs a:hover{color:rgba(255,255,255,.7);}
@media only screen and (min-width:768px){#breadcrumbs{padding-right:30px;}}
</style>
		<?php
	} // add_custom_css_in_header
	
} // class NSRU_Options_Plugin

new NSRU_Options_Plugin();

// All the required files used by this plugin.
require_once 'includes/shortcodes.php';
require_once 'includes/cpt/committee_cpt.php';
require_once 'includes/cpt/pastchairs_cpt.php';
require_once 'includes/cpt/meetings_cpt.php';
require_once 'includes/cpt/speakers_cpt.php';
require_once 'includes/cpt/rooms_cpt.php';
require_once 'includes/ajax/round_up_dates.php';
require_once 'includes/ajax/speakers.php';
require_once 'includes/ajax/meetings.php';
require_once 'includes/ajax/committee.php';
require_once 'includes/ajax/past_chairs.php';
require_once 'includes/ajax/prices.php';
