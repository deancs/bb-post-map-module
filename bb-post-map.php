<?php
/*
Plugin Name: Beaver Builder Posts Map
Description: Display posts on a google map
Version: 1.0
Author: Dean Cleave-Smith
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'RDP_MAP_LISTING_VERSION', '1.0' );
define( 'RDP_MAP_LISTING_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define( 'RDP_MAP_LISTING_URL', plugin_dir_url(__FILE__) );
	
/**
 * @class RDPMAPListingsModule FLPostGridModule
 */
class RDPMAPListingsModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Posts on a Map', 'fl-builder'),
			'description'   	=> __('Display Posts on a map.', 'fl-builder'),
			'category'      	=> __('', 'fl-builder'),
			'editor_export' 	=> false,
			'partial_refresh'	=> true
		));

		//$this->add_js('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3&key='.$this->settings->apikey);

		add_filter('fl_builder_render_settings_field', array($this, 'rdp_settings_filters'), 10, 3);

		wp_enqueue_script('rdp-google-maps-api');
		wp_enqueue_script( 'rdp-goolge-maps-spidify');

	}

	public function enqueue_scripts()
	{
		#$this->add_js('google-maps', '//maps.googleapis.com/maps/api/js?signed_in=true&amp;key=AIzaSyB1iv0PPVqhLLKqaegQRPcBaiqrls1xZbI',array(),'',false);
		$this->add_js('markerclusterer',  $this->url .'js/markerclusterer.js',array(),'',false);
	}

	public function rdp_settings_filters( $field, $name, $settings ) {
		if($name == 'map_style' && $settings->map_style) {
			$settings->map_style = trim(stripslashes(json_encode($settings->map_style)), '"');
		}
		return $field;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('RDPMapListingsModule', array(
	'layout'        => array(
		'title'         => __('Layout', 'fl-builder'),
		'sections'      => array(
			'content'       => array(
				'title'         => __( 'Content', 'fl-builder' ),
				'fields'        => array(
					'show_content'  => array(
						'type'          => 'select',
						'label'         => __('Content', 'fl-builder'),
						'default'       => '1',
						'options'       => array(
							'1'             => __('Show', 'fl-builder'),
							'0'             => __('Hide', 'fl-builder')
						)
					),
					'show_more_link' => array(
						'type'          => 'select',
						'label'         => __('More Link', 'fl-builder'),
						'default'       => '0',
						'options'       => array(
							'1'             => __('Show', 'fl-builder'),
							'0'             => __('Hide', 'fl-builder')
						)
					),
					'more_link_text' => array(
						'type'          => 'text',
						'label'         => __('More Link Text', 'fl-builder'),
						'default'       => __('Read More', 'fl-builder'),
					),
					'posts_per_page' => array(
						'type'          => 'text',
						'label'         => __('Posts per Page', 'fl-builder'),
						'default'       => __('-1', 'fl-builder'),
						'size'		=> '6',
						'maxlength'	=> '4',
					),
				)
			),
			'map_settings'    => array(
				'title'         => __('Map Settings', 'fl-builder'),
				'fields'        => array(
					'apikey' => array(
						'type'          => 'text',
						'label'         => __('Google Maps API Key', 'fl-builder'),
						'default'       => '',
						'maxlength'     => '50',
						'size'          => '40',
						'description'   => ''
					),
					'map_height'  => array(
						'type'          => 'text',
						'label'         => __('Height', 'fl-builder'),
						'default'       => '150',
						'maxlength'     => '4',
						'size'          => '5',
						'description'   => 'px'
					),					
					'map_zoom'  => array(
						'type'          => 'select',
						'label'         => __('Zoom', 'fl-builder'),
						'default'       => 'auto',
						'options'       => array(
							'auto'      => __('auto', 'fl-builder'),
							'1'			=> __('1', 'fl-builder'),
							'2'			=> __('2', 'fl-builder'),
							'3'			=> __('3', 'fl-builder'),
							'4'			=> __('4', 'fl-builder'),
							'5'			=> __('5', 'fl-builder'),
							'6'			=> __('6', 'fl-builder'),
							'7'			=> __('7', 'fl-builder'),
							'8'			=> __('8', 'fl-builder'),
							'9'			=> __('9', 'fl-builder'),
							'10'		=> __('10', 'fl-builder'),
							'11'		=> __('11', 'fl-builder'),
							'12'		=> __('12', 'fl-builder'),
							'13'		=> __('13', 'fl-builder'),
							'14'		=> __('14', 'fl-builder'),
							'15'		=> __('16', 'fl-builder'),
							'16'		=> __('15', 'fl-builder')
						),
						'description'   => '1 = zoomed out, 16 = zoomed in'
					),
					'map_clusters' => array(
						'type'          => 'select',
						'label'         => __('Map Clusters', 'fl-builder'),
						'default'       => 'true',
						'options'       => array(
							'true'             => __('Show', 'fl-builder'),
							'false'            => __('Hide', 'fl-builder')
						)
					),										
					'map_pipcolor'  => array(
						'type'          => 'color',
						'label'         => __('PIP Colour', 'fl-builder'),
						'default'       => 'ffffff',
						'show_reset'    => true,						
						'description'   => ''
					),					
					'map_type' => array(
						'type'          => 'select',
						'label'         => __('Map Type', 'fl-builder'),
						'default'       => 'hybrid',
						'options'       => array(
							'hybrid'        => __('Hybrid', 'fl-builder'),
							'roadmap'          => __('Road', 'fl-builder'),
							'satellite'     => __('Satellite', 'fl-builder'),
							'terrain'       => __('Terrain', 'fl-builder')
						)
					),
					'kml_file'  => array(
						'type'          => 'select',
						'label'         => __('KML/KMZ Source', 'fl-builder'),
						'default'       => 'library',
						'options'       => array(
							'library'       => __('Media Library', 'fl-builder'),
							'url'           => __('URL', 'fl-builder')
						),
						'toggle'        => array(
							'library'       => array(
								'fields'        => array('photo')
							),
							'url'           => array(
								'fields'        => array('photo_url', 'caption')
							)
						)
					),
					'kml_media'         => array(
						'type'          => 'photo',
						'label'         => __('KML/KMZ File', 'fl-builder')
					),
					'kml_url'     => array(
						'type'          => 'text',
						'label'         => __('KML/KMZ Url', 'fl-builder'),
						'placeholder'   => __( 'http://www.example.com/my-map.jpg', 'fl-builder' )
					),


				)
			),			
		)
	),
	'style'         => array( // Tab
		'title'         => __('Style', 'fl-builder'), // Tab title
		'sections'      => array( // Tab Sections
			'text_style'    => array(
				'title'         => __('Colors', 'fl-builder'),
				'fields'        => array(
					'text_color'    => array(
						'type'          => 'color',
						'label'         => __('Text Color', 'fl-builder'),
						'default'       => 'ffffff',
						'show_reset'    => true
					),
					'text_bg_color' => array(
						'type'          => 'color',
						'label'         => __('Text Background Color', 'fl-builder'),
						'default'       => '333333',
						'help'          => __('The color applies to the overlay behind text over the background selections.', 'fl-builder'),
						'show_reset'    => true
					),
					'text_bg_opacity' => array(
						'type'          => 'text',
						'label'         => __('Text Background Opacity', 'fl-builder'),
						'default'       => '50',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => '%'
					),
				)
			),
		)
	),
	'content'   => array(
		'title'         => __('Content', 'fl-builder'),
		'file'          => FL_BUILDER_DIR . 'includes/loop-settings.php',
	)
));
