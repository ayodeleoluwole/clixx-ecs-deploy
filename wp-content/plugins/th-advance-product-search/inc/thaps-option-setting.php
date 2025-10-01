<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TH_Advancde_Product_Search_Options' ) ):

	class TH_Advancde_Product_Search_Options {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
        /**
		 * Constructor
		 */
		public function __construct(){
           add_action( 'init', array( $this,'thaps_option_settings'), 2 );
		}

		public function post_type_option(){
          
                    if(class_exists( 'WooCommerce' )){    

                            $pst_ary =     array(

									'post_srch'   => esc_html__( 'Post', 'th-advance-product-search' ),
									'product_srch' => esc_html__( 'Product', 'th-advance-product-search' ),
									'page_srch'  => esc_html__( 'Page', 'th-advance-product-search' )
								);

                        }else{
                              

                              $pst_ary =     array(

									'post_srch'   => esc_html__( 'Post', 'th-advance-product-search' ),
									'page_srch'  => esc_html__( 'Page', 'th-advance-product-search' )
								);

                        }


                           return $pst_ary;


		}

		public function post_type_option_default(){
                       
            if(class_exists( 'WooCommerce' )){

                        $option_default = 'product_srch';

                      }else{

                        $option_default = 'post_srch';

                      } 

                      return $option_default;


		}

          
		public function thaps_option_settings(){

            th_advance_product_search()->add_setting(
			'integration', esc_html__( 'Integration', 'th-advance-product-search' ), apply_filters(
			'thaps_integration_settings_section', array(
				array(
					'title'  => esc_html__( 'Integration Methods', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_integration_setting_fields', array(
							array(
								'id'      => 'how-to-integrate',
								'type'    => 'html',
								'title'   => esc_html__( 'How To Add', 'th-advance-product-search' )
							)
						)
					)
				 )
			  )
		    ), apply_filters( 'thaps_integration_settings_default_active', true )
		  );

          th_advance_product_search()->add_setting(
			'search-bar', esc_html__( 'Basic Setting', 'th-advance-product-search' ), apply_filters(
			'thaps_search_bar_settings_section', array(
				array(
					'title'  => esc_html__( 'General Search Settings', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_search_bar_setting_fields', array(
							array(
								'id'      => 'set_autocomplete_length',
								'type'    => 'number',
								'title'   => esc_html__( 'Minimum Character', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'Search results will start appearing after the user types this many characters.', 'th-advance-product-search' ),
								'default' => 1,
								'min'     => 1,
								'max'     => 10
							),
							array(
								'id'      => 'set_form_width',
								'type'    => 'number',
								'title'   => esc_html__( 'Search Bar Width', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'Set a specific width or leave empty for 100% fluid width.', 'th-advance-product-search' ),
								'default' => 550,
								'min'     => 1,
								'max'     => 2400,
								'suffix'  => 'px'
							),	
							array(
								'id'      => 'show_submit',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Submit Button', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'Show a dedicated submit button next to the search field', 'th-advance-product-search' ),
								'default' => true
							),

							array(
								'id'      => 'level_submit',
								'type'    => 'text',
								'title'   => esc_html__( 'Button Label', 'th-advance-product-search' ),
								'default' => 'Search'
								
							),
							array(
								'id'      => 'placeholder_text',
								'type'    => 'text',
								'title'   => esc_html__( 'Placeholder Text', 'th-advance-product-search' ),
								'default' => esc_html__('Search for products...','th-advance-product-search')
								
							)
						)
					)
				 ),
				array(
					'title'  => esc_html__( 'Loader', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_search_bar_setting_fields', array(
							array(
								'id'      => 'show_loader',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Visual Loader', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'Disable the loading animation during search queries', 'th-advance-product-search' ),
								'default' => false
							    )
						
						)
					)
				 ),

				array(
					'title'  => esc_html__( 'Interface Overlay', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'tapsp_search_bar_setting_fields', array(
							array(
								'id'      => 'tapsp_show_body_overlay',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Focus Overlay', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'Dim the background when search suggestions are active', 'th-advance-product-search-pro' ),
								'default' => false
							    ),
						)
					)
				 ),

			   )
		     )
		   );
          th_advance_product_search()->add_setting(
			'autosetting', esc_html__( 'Advance Setting', 'th-advance-product-search' ), apply_filters(
			'thaps_autosetting_section', array(
				array(
					'title'  => esc_html__( 'Search Autocomplete Settings', 'th-advance-product-search' ),
	
					'fields' => apply_filters(
						'thaps_autosetting_fields', array(
							array(
								'id'      => 'select_srch_type',
								'type'    => 'select',
								'title'   => esc_html__( 'Select Search Type', 'th-advance-product-search' ),
								'default' => $this->post_type_option_default(),
								'options' => $this->post_type_option(),
								'desc'    => esc_html__( 'This setting define what you want to search, For example if you select "Product" then search will display olny products in search result.', 'th-advance-product-search' )
								
							),
							array(
								'id'      => 'result_length',
								'type'    => 'number',
								'title'   => esc_html__( 'Limit', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'Show Search Result', 'th-advance-product-search' ),
								'default' => 5,
								'min'     => 1,
								'max'     => 100,
							),
							array(
								'id'      => 'no_reult_label',
								'type'    => 'text',
								'title'   => esc_html__( 'No Result Label', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'This text will display at the search result dropdown.', 'th-advance-product-search' ),
								'default' => esc_html__( 'No Result Found', 'th-advance-product-search' )
								
							),
							array(
								'id'      => 'more_reult_label',
								'type'    => 'text',
								'title'   => esc_html__( 'More Result Label', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'This text will display at the search result dropdown.', 'th-advance-product-search' ),
								'default' => esc_html__( 'See All Results ', 'th-advance-product-search' )
								
							),
						  array(
								'id'      => 'enable_group_heading',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Group Heading', 'th-advance-product-search' ),

								'desc'    => esc_html__( 'It\'s a main heading, Display in the search result dropdown.', 'th-advance-product-search' ),
								'default' => true
							    ),	
						   
						   array(
								'id'      => 'desc_excpt_length',
								'type'    => 'number',
								'title'   => esc_html__( 'Description Length', 'th-advance-product-search' ),
								
								'desc'    => esc_html__( 'This option limit searched item description length. Count value in words.', 'th-advance-product-search' ),
								'default' => 60,
								'min'     => 1,
								'max'     => 500
							),
						   
						   array(
								'id'      => 'tapsp_enable_voice_search',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Voice Search', 'th-advance-product-search-pro' ),
								'desc'    => esc_html__( 'Show a microphone button so users can search by voice', 'th-advance-product-search-pro' ),
								'default' => false,
							    ),
							
						)
					)
				 ),
                  array(
					'title'  => esc_html__( 'Search Category', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_cat_setting_fields', array(
							 array(
								'id'      => 'show_category_in',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Show Category', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'Check to display categories in the search result dropdown.', 'th-advance-product-search' ),
								'default' => false
							    ),
								array(
								'id'      => 'enable_cat_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Category Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    )
							
							
							
						)
					)
				 ),

				array(
					'title'  => esc_html__( 'Product', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_product_setting_fields', array(
								array(
								'id'      => 'enable_product_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							array(
								'id'      => 'enable_product_price',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product price', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							array(
								'id'      => 'enable_product_desc',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product Description', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							array(
								'id'      => 'enable_product_sku',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Product SKU', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							array(
								'id'      => 'exclude_product_sku',
								'type'    => 'text',
								'title'   => esc_html__( 'Exclude Product', 'th-advance-product-search' ),
								'desc'    => esc_html__( 'Exclude Product by SKU ID seperated by " , "', 'th-advance-product-search' ),
								'default' =>  false
								
							)
							
						)
					)
				 ),
				 array(
					'title'  => esc_html__( 'Post', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_post_setting_fields', array(
								array(
								'id'      => 'enable_post_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Post Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							
							array(
								'id'      => 'enable_post_desc',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Post Description', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							
							
						)
					)
				 ),
				 array(
					'title'  => esc_html__( 'Pages', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_pages_setting_fields', array(
								array(
								'id'      => 'enable_page_image',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Page Image', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => true
							    ),
							
							array(
								'id'      => 'enable_page_desc',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Page Description', 'th-advance-product-search' ),
								'desc'    => '',
								'default' => false
							    ),
							
							
						)
					)
				 )
			  )
		    )
		  );

		 th_advance_product_search()->add_setting(
					'style', esc_html__( 'Style', 'th-advance-product-search' ), apply_filters(
					'thaps_style_settings_section', array(
						array(
							'title'  => esc_html__( 'Search Bar', 'th-advance-product-search' ),
							'fields' => apply_filters(
								'thaps_style_settings_fields', array(
									array(
										'id'      => 'bar_bg_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Bar Background Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_brdr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Border Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'bar_text_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Text Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									// array(
									// 	'id'      => 'icon_clr',
									// 	'type'    => 'color',
									// 	'title'   => esc_html__( 'Icon Color', 'th-advance-product-search' ),
										
									// 	'alpha'   => true,
									// ),
									array(
										'id'      => 'bar_button_bg_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Submit Button BG Color ', 'th-advance-product-search' ),
										'alpha'   => true,
										'default'      => '#155dfc',
									),
									array(
										'id'      => 'bar_button_txt_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Submit Button Text / Icon Color', 'th-advance-product-search' ),
										'alpha'   => true,
										'default'      => '#FFF',
									),
									array(
										'id'      => 'bar_button_hvr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Submit Button BG Hover Color', 'th-advance-product-search' ),
										'alpha'   => true,
										'default'      => '#155dfc',
									),
									array(
										'id'      => 'bar_button_txt_hvr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Submit Button Text Hover Color', 'th-advance-product-search' ),
										'alpha'   => true,
										'default'      => '#FFF',
									)
								)
							)
						 ),
						array(
							'title'  => esc_html__( 'Suggestion Box', 'th-advance-product-search' ),
							'fields' => apply_filters(
								'thaps_style_settings_fields', array(
									array(
										'id'      => 'sus_bg_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Background Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_hglt_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Highlight Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_slect_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Selected Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_brdr_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Border Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_grphd_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Group Title Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),
									array(
										'id'      => 'sus_title_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Title Color', 'th-advance-product-search' ),
										'alpha'   => true,
									),

									array(
										'id'      => 'sus_text_clr',
										'type'    => 'color',
										'title'   => esc_html__( 'Text Color', 'th-advance-product-search' ),
										'alpha'   => true
									)
									
								)
							)
						 )
					  )
				)
			);

		 th_advance_product_search()->add_setting(
			'analytics', esc_html__( 'Search Analytics', 'th-advance-product-search' ), apply_filters(
			'thaps_analytics_settings_section', array(
				array(
					'title'  => esc_html__( 'ADD SEARCH ANALYTICS IN YOUR THEME', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_analytics_setting_fields', array(
							array(
								'id'      => 'how-to-integrate-analytics',
								'type'    => 'analytics-html',
								'title'   => esc_html__( 'How To Add', 'th-advance-product-search' )
							)	
						)
					)
				 )
			  )
		    )
		  );

		 th_advance_product_search()->add_setting(
			'search-configure', esc_html__( 'Search Configure (Pro)', 'th-advance-product-search-pro' ), apply_filters(
			'tapsp_search_configure_settings_section', array(
				array(
					'title'  => esc_html__( 'Search Scope in Product', 'th-advance-product-search-pro' ),
					'fields' => apply_filters(
						'tapsp_search_configure_setting_fields', array(
							array(
								'id'      => 'tapsp_search-in-category',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Search in Category', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),	
							array(
								'id'      => 'tapsp_search-in-tag',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Search in Tags', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),
							array(
								'id'      => 'tapsp_search-in-brand',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Search in Brand', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),	
							array(
								'id'      => 'tapsp_search-in-attribute',
								'type'    => 'checkbox',
								'title'   => esc_html__('Search in Attributes', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),
							array(
								'id'      => 'tapsp_search-in-description',
								'type'    => 'checkbox',
								'title'   => esc_html__('Search in Description', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),	
							array(
								'id'      => 'tapsp_search-in-short-description',
								'type'    => 'checkbox',
								'title'   => esc_html__('Search in Short Description', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),	
							array(
								'id'      => 'tapsp_search_in_product_sku',
								'type'    => 'checkbox',
								'title'   => esc_html__('Search in SKU', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),	
							array(
								'id'      => 'tapsp_search_in_custom_fld',
								'type'    => 'selectize',
								'title'   => esc_html__( 'Search in Custom Field', 'th-advance-product-search-pro' ),
								'options' => '',
								
							),
							array(
								'id'      => 'tapsp_search_in_custom_post_type',
								'type'    => 'text',
								'title'   => esc_html__( 'Search in Custom Post Type', 'th-advance-product-search-pro' ),
								'desc'    => esc_html__( 'Custom posts are generated using plugins. Include slugs separated by commas.','th-advance-product-search-pro' ),

								
							)
						)
					 ),
					
				  ),

				// search trend
				
				array(
					'title'  => esc_html__( 'Suggested/Trending searches', 'th-advance-product-search-pro' ),
					'fields' => apply_filters(
						'tapsp_trending_setting_fields', array(
							array(
								'id'      => 'tapsp_trending_enable',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Suggested/Trending Enable', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),
							array(
									'id'      => 'tapsp_specific_key_search',
									'type'    => 'select',
									'title'   => esc_html__( 'Searches to suggest', 'th-advance-product-search-pro' ),
									'default' => 'normal',
									'options' => array(
										'specific' => esc_html__( 'Specific', 'th-advance-product-search-pro' ),
										'popular'  => esc_html__( 'Popular', 'th-advance-product-search-pro'),	
									),

								),
							array(
								'id'      => 'tapsp_trending_search',
								'type'    => 'text',
								'title'   => esc_html__( 'Searches to suggest Keyword', 'th-advance-product-search-pro' ),
								'desc'    => esc_html__( 'Enter the keywords, comma separated, for the suggested searches.','th-advance-product-search-pro' ),
     							'default' => 'Vintage dress, Black dress, Black boots, Red dress'
							),
							array(
								'id'      => 'tapsp_trending_limit',
								'type'    => 'number',
								'title'   => esc_html__( 'Limit', 'th-advance-product-search-pro' ),
								'desc'    => esc_html__( 'Show Search Result', 'th-advance-product-search-pro' ),
								'default' => 3,
								'min'     => 1,
								'max'     => 20,
							),
						)
					 )
				),
				array(
					'title'  => esc_html__( 'Highlight in Product', 'th-advance-product-search-pro' ),
					'fields' => apply_filters(
						'tapsp_highlight_setting_fields', array(
							array(
								'id'      => 'tapsp_highlight-sale',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Sale', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),
							array(
								'id'      => 'tapsp_highlight-featured',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Featured', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),
							array(
								'id'      => 'tapsp_stock-availablity',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Stock Availablity', 'th-advance-product-search-pro' ),
								'desc'    => '',
								'default' => false
							),	
							
						)
					 )
				  ),
			   )
		    )
		  );

 th_advance_product_search()->add_setting(
			'thaps_index_builder',
			esc_html__( 'Boost Search (Pro)', 'th-advance-product-search-pro' ),
			apply_filters(
				'tapsp_index_builder_settings_section',
				array(
					array(
						'title'  => esc_html__( 'Search Optimization', 'th-advance-product-search-pro' ),
						'subtitle'  => esc_html__( 'Manage and rebuild your search index to ensure your product catalog is always up-to-date and performing at its peak.', 'th-advance-product-search-pro' ),
						'fields' => apply_filters(
							'tapsp_index_builder_setting_fields',
							array(
								array(
									'id'    => 'thaps_build_search_index',
									'type'  => 'index-builder-html',
									'title' => esc_html__( 'Product Index', 'th-advance-product-search' ),
									'desc'    => esc_html__( 'Click below to build or rebuild the product search index table. This process ensures all your latest products are searchable.', 'th-advance-product-search' ),
								),

								array(
							        'id'      => 'thaps_index_batch_limit',
							        'type'    => 'number',
							        'title'   => __('Index Batch Limit', 'th-advance-product-search-pro'),
							        'default' => 300,
							        'attrs'   => array(
							            'min'  => 50,
							            'step' => 50,
							        ),
							        'desc'    => __('Default is 300. Increase for faster indexing, but be aware it may increase server load during the process.', 'th-advance-product-search-pro'),
							    ),
							)
						),
					),
				)
			)
		);
		th_advance_product_search()->add_setting(
	'thaps_fuzzy_settings',
	esc_html__( 'Fuzzy Strings & Synonyms (Pro)', 'th-advance-product-search-pro' ),
	apply_filters(
		'thaps_fuzzy_settings_section',
		array(
			array(
				'title'  => esc_html__( 'Fuzzy Search & Synonyms', 'th-advance-product-search-pro' ),
				'subtitle'  => esc_html__( 'Enhance search accuracy by handling misspellings and mapping related terms for a more intuitive user experience.', 'th-advance-product-search-pro' ),
				'fields' => apply_filters(
					'thaps_fuzzy_setting_fields',
					array(
						array(
							'id'      => 'thaps_enable_fuzzy',
							'type'    => 'checkbox',
							'title'   => esc_html__( 'Enable Fuzzy Strings Matching', 'th-advance-product-search-pro' ),
							'desc'    => esc_html__( 'Help users find results even with typos (e.g., "skrit" → "skirt").', 'th-advance-product-search-pro' ),
							'default' => false,
						),
						array(
							'id'       => 'thaps_fuzzy_level',
							'type'     => 'number',
							'title'    => esc_html__( 'Matching Sensitivity', 'th-advance-product-search-pro' ),
							'desc'     => esc_html__( 'Recommended: 50%. Higher values (80%+) may return more results but increase false positives.', 'th-advance-product-search-pro' ),
							'default'  => 50,
							'min'      => 10,
							'max'      => 100,
							'suffix'   => '%',
						),
						array(
							'id'       => 'thaps_synonym_list',
							'type'     => 'textarea',
							'title'    => esc_html__( 'Define Synonyms', 'th-advance-product-search-pro' ),
							'desc'     => esc_html__( 'Use commas (,) to separate synonyms within a group and pipe (|) to separate groups. Example: trousers, pants | denim, jeans | belt, waistband', 'th-advance-product-search-pro' ),
							'placeholder'  => 'trousers, pants | denim, jeans | belt, waistband',
							'default'  => '',
							'rows'     => 3,
						),
					)
				),
			),
		)
	)
     );

		th_advance_product_search()->add_setting(
			'reset', esc_html__( 'Reset All Setting', 'th-advance-product-search' ), apply_filters(
			'thaps_reset_settings_section', array(
				array(
					'title'  => esc_html__( 'Reset All Your Custom Settings.', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_reset_setting_fields', array(
							
						)
					)
				 )
			  )
		    )
		  );

	  	th_advance_product_search()->add_setting(
			'help', esc_html__( 'Help', 'th-advance-product-search' ), apply_filters(
			'thaps_help_settings_section', array(
				array(
					'title'  => esc_html__( 'Help', 'th-advance-product-search' ),
					'fields' => apply_filters(
						'thaps_help_setting_fields', array(
							
						)
					)
				 )
			  )
		    )
		  );

		}

	}
endif;	
TH_Advancde_Product_Search_Options::get_instance();