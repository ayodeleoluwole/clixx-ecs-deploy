<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Taiowc_Set' ) ):

	class Taiowc_Set {

        private $setting_name = 'taiowc';
		private $setting_reset_name = 'reset';
		private $theme_feature_name = 'taiowc';
		private $slug;
		private $plugin_class;
		private $defaults = array();
		private $fields = array();
		private $reserved_key = '';
		private $reserved_fields = array();
		private $settings_name;
		
             public function __construct() {
             $this->settings_name   = apply_filters( 'taiowc_settings_name', $this->setting_name );
             $this->fields          = apply_filters( 'taiowc_settings', $this->fields );
             $this->reserved_key    = sprintf( '%s_reserved', $this->settings_name );
		     $this->reserved_fields = apply_filters( 'taiowc_reserved_fields', array() );
 
             add_action( 'admin_menu', array( $this, 'add_menu' ) );
             add_action( 'init', array( $this, 'set_defaults' ), 8 );
             add_action( 'admin_init', array( $this, 'settings_init' ), 90 );
             add_action( 'admin_enqueue_scripts', array( $this, 'script_enqueue' ) );

             add_action('wp_ajax_taiowc_form_setting', array($this, 'taiowc_form_setting'));

            }
        

        public function add_menu(){

		$page_title = esc_html__( 'AIO Woo Cart', 'th-all-in-one-woo-cart');
		
		add_submenu_page( 'themehunk-plugins', $page_title,$page_title, 'manage_options', 'taiowc', array($this, 'settings_form'),10 );


		 }

		
		public function settings_form() {

			if ( ! current_user_can( 'manage_options' ) ) {

				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.','th-all-in-one-woo-cart' ) );

			}

			if( ! class_exists( 'WooCommerce' ) ){

				   printf('<h2 class="requirement-notice">%s</h2>',esc_html__('TH All In One Woo Cart Pro requires WooCommerce to work. Make sure that you have installed and activated WooCommerce Plugin.','th-all-in-one-woo-cart' ) );

             return;

			}
		
			?>
			<div id="taiowc" class="settings-wrap thwc-plugin-common-wrap">
				
				 <?php $this->options_tabs(); ?>

                   <div class="setting-wrap">
                   <div class="setting-content">
					<div class="top-header">
                <h2 class="tabheading"><?php esc_html_e("Integration", 'th-all-in-one-woo-cart'); ?></h2>
               
           <a href="<?php echo esc_url( 'https://themehunk.com/th-all-in-one-woo-cart/' ); ?>"
			   title="<?php esc_attr_e( 'Get Premium Version', 'th-all-in-one-woo-cart' ); ?>"
			   target="_blank">
				<?php esc_html_e( 'Get Premium Version', 'th-all-in-one-woo-cart' ); ?>
			</a>
					<p class="submit taiowc-button-wrapper th-save-btn">
						 <button disabled id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'th-all-in-one-woo-cart' ) ?>"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save transition-transform group-hover:scale-110" aria-hidden="true"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path><path d="M7 3v4a1 1 0 0 0 1 1h7"></path></svg></span><span><?php esc_html_e( 'Save All Changes', 'th-all-in-one-woo-cart' ) ?></span>
						 </button>
					</p> 
					</div>
					
				   <form method="post" action="" enctype="multipart/form-data" class="taiowc-setting-form">
                   <input type="hidden" name="action" value="taiowc_form_setting">
					<div id="settings-tabs">
						<?php foreach ( $this->fields as $tab ):
							if ( ! isset( $tab['active'] ) ) {
								$tab['active'] = false;
							}
							$is_active = ( $this->get_last_active_tab() == $tab['id'] );
							?>
							<div id="<?php echo esc_attr($tab['id']); ?>"
								 class="settings-tab taiowc-setting-tab"
								 style="<?php echo ! esc_attr($is_active) ? 'display: none' : '' ?>">
								 
								<?php foreach ( $tab['sections'] as $section ):

					        	$this->do_settings_sections( $tab['id'] . $section['id'] );

								endforeach; ?>

							</div>

						<?php endforeach; ?>

					</div>
					
					<?php

					$this->last_tab_input();
					
					?>
					<?php 
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowcp-live-menu-cart.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowcp-live-fixed-cart.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowcp-live-cart-panel.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowcp-live-mobile.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowcp-reset.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowc-help.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowcp-live-cart-analytics.php';
					require_once TAIOWC_PLUGIN_PATH . '/inc/taiowc-premium.php';
					?> 
					
					</form>
			</div> 

			
            
            </div>        
				
			</div>
			<?php
			
		}
	    public function taiowc_form_setting(){  

	    	if ( ! current_user_can( 'manage_options' ) ) {

		            wp_die( - 1, 403 );

		      }
		      check_ajax_referer( 'taiowc_plugin_nonce','_wpnonce');
			  $sanitize_data_array = array();
			  if ( isset( $_POST['taiowc'] ) && is_array( $_POST['taiowc'] ) ) {
	          	$sanitize_data_array = $this->taiowc_form_sanitize( wp_unslash( $_POST['taiowc'] ) );
			  }
	          update_option('taiowc', $sanitize_data_array);         
		      die();  
	    }
        
	    public function taiowc_form_sanitize( $input ){
				foreach ( $input as $key => $val ){
					$new_input[ $key ] = ( isset( $input[ $key ] ) ) ? sanitize_text_field( $val ) :'';
		   }
		   return $new_input;

	    }
		public function options_tabs() {
			?>

			<div class="nav-tab-wrapper wp-clearfix">
				<div class="top-wrap"><div id="logo"><img src='<?php echo esc_url(TAIOWC_IMAGES_URI.'/th-logo.png') ?>' alt="th-logo"/></div>
				  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			     </div>
				<?php foreach ( $this->fields as $tabs ): ?>
					<a data-target="<?php echo esc_attr( $tabs['id'] ); ?>" class="taiowc-setting-nav-tab nav-tab <?php echo esc_attr( $this->get_options_tab_css_classes( $tabs ) ); ?>" href="#<?php echo esc_attr( $tabs['id'] ); ?>">
			    <span><?php echo wp_kses( $this->icon_list($tabs['id']), $this->allowed_svg_tags() ); ?></span>
			    <?php echo esc_html( $tabs['title'] ); ?>
</a>
				<?php endforeach; ?>
			</div>
			<?php
		}

		function allowed_svg_tags() {

        return [
          'svg' => [
    'xmlns'            => true,
    'width'            => true,
    'height'           => true,
    'viewbox'          => true, // ← lowercase!
    'fill'             => true,
    'stroke'           => true,
    'stroke-width'     => true,
    'stroke-linecap'   => true,
    'stroke-linejoin'  => true,
    'class'            => true,
],

            'g' => [
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
            ],
            'path' => [
                'd'               => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
            ],
            'circle' => [
                'cx'              => true,
                'cy'              => true,
                'r'               => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
            ],
            'rect' => [
                'width'           => true,
                'height'          => true,
                'x'               => true,
                'y'               => true,
                'rx'              => true,
                'ry'              => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
            ],
            'line' => [
                'x1'              => true,
                'x2'              => true,
                'y1'              => true,
                'y2'              => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
            ],
        ];
    }

		function icon_list($id ='dashicons-menu'){
			$icon = array(
			'taiowc_integration'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml" aria-hidden="true"><path d="m18 16 4-4-4-4"></path><path d="m6 8-4 4 4 4"></path><path d="m14.5 4-5 16"></path></svg>',

			'taiowc_general' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings" aria-hidden="true"><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"></path><circle cx="12" cy="12" r="3"></circle></svg>',

			'taiowc_menu_cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag" aria-hidden="true"><path d="M16 10a4 4 0 0 1-8 0"></path><path d="M3.103 6.034h17.794"></path><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"></path></svg>',

			'taiowc_fixed_cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mouse-pointer2 lucide-mouse-pointer-2" aria-hidden="true"><path d="M4.037 4.688a.495.495 0 0 1 .651-.651l16 6.5a.5.5 0 0 1-.063.947l-6.124 1.58a2 2 0 0 0-1.438 1.435l-1.579 6.126a.5.5 0 0 1-.947.063z"></path></svg>',

			'taiowc-cart_style_set'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-panels-top-left" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18"></path><path d="M9 21V9"></path></svg>',

			'taiowc_cart'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text" aria-hidden="true"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"></path><path d="M14 2v5a1 1 0 0 0 1 1h5"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>',

			
			'taiowc_mobile_cart'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone" aria-hidden="true"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect><path d="M12 18h.01"></path></svg>',

			'taiowc_hide_cart'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off" aria-hidden="true"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"></path><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"></path><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"></path><path d="m2 2 20 20"></path></svg>',

			'taiowc_cart_analyst'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-column" aria-hidden="true"><path d="M3 3v16a2 2 0 0 0 2 2h16"></path><path d="M18 17V9"></path><path d="M13 17V5"></path><path d="M8 17v-3"></path></svg>',

			'taiowc_reset'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw" aria-hidden="true"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path><path d="M16 16h5v5"></path></svg>',

			'taiowc_help'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <path d="M12 17h.01"></path>
            </svg>',
		);

			return $icon[$id];

		}

		private function get_last_active_tab() {
			$last_option_tab = '';
			$last_tab        = $last_option_tab;

			if ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
				$last_tab = trim( sanitize_key($_GET['tab']) );
			}

			if ( $last_option_tab ) {
				$last_tab = $last_option_tab;
			}

			$default_tab = '';
			foreach ( $this->fields as $tabs ) {
				if ( isset( $tabs['active'] ) && $tabs['active'] ) {
					$default_tab = sanitize_key($tabs['id']);
					break;
				}
			}

			return ! empty( $last_tab ) ? esc_html( $last_tab ) : esc_html( $default_tab );

		}

		private function do_settings_sections( $page ) {

			global $wp_settings_sections, $wp_settings_fields;

			if ( ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_sections[ $page ] as $section ) {

				
                
				if ( $section['callback'] ) {
					call_user_func( $section['callback'], $section );
				}

				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
					continue;
				}

				echo '<div class="form-table taiowc-form-table" id="'.esc_attr($section['id']).'" data-layout="form-table">';

				if ( $section['title'] ) {

					echo "<h2 class=".esc_attr($section['id']).">".esc_html($section['title'])."</h2>";

				}

				$this->do_settings_fields( $page, $section['id'] );
				echo '</div>';
			}
		}

		private function last_tab_input() {
    printf(
        '<input type="hidden" id="_last_active_tab" name="%s[_last_active_tab]" value="%s">',
        esc_attr( $this->settings_name ),
        esc_attr( $this->get_last_active_tab() )
    );
}

		private function get_options_tab_css_classes( $tabs ) {
			$classes = array();

			$classes[] = ( $this->get_last_active_tab() == $tabs['id'] ) ? 'nav-tab-active' : '';

			return implode( ' ', array_unique( apply_filters( 'get_options_tab_css_classes', $classes ) ) );
		}

		private function do_settings_fields( $page, $section ) {
			global $wp_settings_fields;

			if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
				
				// $custom_attributes = $this->array2html_attr( isset( $field['args']['attributes'] ) ? $field['args']['attributes'] : array() );

				$attributes = isset( $field['args']['attributes'] ) ? $field['args']['attributes'] : array();

				// Always add taiowc-settings-row class
				if ( isset( $attributes['class'] ) ) {
				    $attributes['class'] .= ' taiowc-settings-row';
				} else {
				    $attributes['class'] = 'taiowc-settings-row';
				}

				$custom_attributes = $this->array2html_attr( $attributes );


				$wrapper_id = ! empty( $field['args']['id'] ) ? esc_attr( $field['args']['id'] ) . '-wrapper' : '';
				$dependency = ! empty( $field['args']['require'] ) ? $this->build_dependency( $field['args']['require'] ) : '';

							printf(
				'<div id="%s" %s %s>',
				esc_attr( $wrapper_id ),
				wp_kses( $custom_attributes, array(
					'class' => array(),
					'data' => array(),
					'data-*' => array(), 
					'style' => array(),
				) ),
				esc_attr( $dependency )
			);

                if ( isset( $field['args']['usefull'] ) ) {
					echo '<div colspan="2" style="padding: 0; margin: 0">';
					$this->usefullplugin_field_callback( $field['args'] );
					echo '</div>';
			  	}else{
				
					echo '<div scope="row" class="taiowc-settings-label">';
					if ( ! empty( $field['args']['label_for'] ) ) {
						echo '<label for="' . esc_attr( $field['args']['label_for'] ) . '">' . esc_html($field['title']). '</label>';
					} else {
						echo esc_html($field['title']);
					}

					echo '</div>';
					echo '<div class="taiowc-settings-field-content">';
					call_user_func( $field['callback'], $field['args'] );
					echo '</div>';
				}
				   echo '</div>';
			}
		}

        public function array2html_attr( $attributes, $do_not_add = array() ) {

			$attributes = wp_parse_args( $attributes, array() );

			if ( ! empty( $do_not_add ) and is_array( $do_not_add ) ) {
				foreach ( $do_not_add as $att_name ) {
					unset( $attributes[ $att_name ] );
				}
			}


			$attributes_array = array();

			foreach ( $attributes as $key => $value ) {

				if ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === true ) {
					return $attributes[ $key ] ? $key : '';
				} elseif ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === false ) {
					$attributes_array[] = '';
				} else {
					$attributes_array[] = $key . '="' . esc_attr($value) . '"';
				}
			}

			return implode( ' ', $attributes_array );
		}

		 private function build_dependency( $require_array ) {
			$b_array = array();
			foreach ( $require_array as $k => $v ) {
				$b_array[ '#' . $k . '-field' ] = $v;
			}

			return 'data-taiowcdepends="[' . esc_attr( wp_json_encode( $b_array ) ) . ']"';
		}

		 public function make_implode_html_attributes( $attributes, $except = array( 'type', 'id', 'name', 'value' ) ) {
			$attrs = array();
			foreach ( $attributes as $name => $value ) {
				if ( in_array( $name, $except, true ) ) {
					continue;
				}
				$attrs[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
			}

			return implode( ' ', array_unique( $attrs ) );
		}

		/***************/
		// Field call back function
		/***************/

		public function field_callback( $field ) {

			switch ( $field['type'] ) {

				case 'checkbox':
					$this->checkbox_field_callback( $field );
					break;

				case 'select':
					$this->select_field_callback( $field );
					break;

				case 'number':
					$this->number_field_callback( $field );
					break;

			    case 'colorpkr':
					$this->colorpkr_field_callback( $field );
					break;		

				case 'html':
					$this->html_field_callback( $field );
					break;	

				case 'radio-image':
					$this->radio_image_field_callback( $field );
					break;
			    case 'textarea':
					$this->textarea_field_callback( $field );
					break;
				case 'usefullplugin':
					$this->usefullplugin_field_callback( $field );
					break;	

				case 'premium':
					$this->premium_field_callback( $field );
					break;					

				default:
					$this->text_field_callback( $field );
					break;
			}
			do_action( 'taiowc_settings_field_callback', $field );
		}

     
      public function checkbox_field_callback( $args ) {
               
			$value = (bool)( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';?>
            <fieldset>
            	<label class="th-toggle">
            		<input <?php echo esc_attr($attrs); ?> type="checkbox" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="1" <?php echo esc_attr(checked( $value, true, false ));?>> <?php if ( ! empty( $args['desc'] ) ) {  echo esc_html($args['desc']); } ?>
            	</label>     
            </fieldset>

		<?php }

		public function select_field_callback( $args ) {

			$options = apply_filters( "taiowc_settings_{$args[ 'id' ]}_select_options", $args['options'] );

			$valuee   = esc_attr( $this->get_option( $args['id'] ) );

		
			$size    = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';
			?>

			<select <?php echo esc_attr($attrs); ?> class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]">

				<?php foreach($options as $key => $value){ ?>

                <option <?php echo esc_attr(selected( $key, $valuee, false )) ;?> value="<?php echo esc_attr($key);?>">
                	
                	<?php echo esc_html($value);?> 	

                </option> 

               <?php } ?>

			</select>

			<?php if ( ! empty( $args['desc'] ) ) { ?>
            <p class="description"><?php echo esc_html($args['desc']);?></p>
		    <?php } }

		
		public function get_field_description( $args ) {

			$desc = '';

			if ( ! empty( $args['desc'] ) ) {
				$desc .= sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc .= '';
			}

			return ( ( $args['type'] === 'checkbox' ) ) ? '' : $desc;
		}

		public function text_field_callback( $args ) {
			$value = esc_attr( $this->get_option( $args['id'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';?>
            <input type="text" class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="<?php echo esc_attr($value); ?>"/>

            <?php if ( ! empty( $args['desc'] ) ) { ?>
            <p class="description"><?php echo esc_html($args['desc']);?></p>
	        <?php 
	           }
				
		}

		public function textarea_field_callback( $args ) {
			$value = esc_attr( $this->get_option( $args['id'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';?>
           <textarea class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]"><?php echo esc_attr($value); ?></textarea>

          <?php if ( ! empty( $args['desc'] ) ) { ?>
           <p class="description"><?php echo esc_html($args['desc']);?></p>
	      <?php 
	           }
				
		}

		public function premium_field_callback( $args ) {
			if($args[ 'id' ]=='taiowc-premium-badge'){ ?>

				<div class="th-premium-box">


    <h2 class="th-premium-title">
        <?php esc_html_e( 'Unlock This Feature with Premium', 'th-all-in-one-woo-cart' ); ?>
    </h2>

    <span class="th-premium-badge">
        <?php esc_html_e( 'Premium Feature', 'th-all-in-one-woo-cart' ); ?>
    </span>

    <p class="th-premium-desc">
        <?php esc_html_e( 'These options are available for preview purposes only. You can explore and test them here, but they will not be applied to the live frontend of the website. To activate these advanced features on your site, please upgrade to the Pro version.', 'th-all-in-one-woo-cart' ); ?>
    </p>

    <div class="th-premium-actions">
        <a href="<?php echo esc_url( 'https://themehunk.com/th-all-in-one-woo-cart/' ); ?>"
           class="th-premium-btn"
           target="_blank"
           rel="noopener noreferrer">
            <?php esc_html_e( 'Go Premium', 'th-all-in-one-woo-cart' ); ?>
        </a>

        <a href="<?php echo esc_url( 'https://themehunk.com/th-all-in-one-woo-cart/' ); ?>"
           class="th-premium-link"
           target="_blank"
           rel="noopener noreferrer">
            <?php esc_html_e( 'Learn more', 'th-all-in-one-woo-cart' ); ?>
        </a>
    </div>

</div>

		<?php	}
		}
		
		public function html_field_callback( $args ) {

        	if($args[ 'id' ]=='taiowc-how-to-integrate'):
   
				$taiowc_karr = array( 
			   'br' => array(),
			   'strong' => array(),
			   'code' => array(),
			   'a' => array( 
					  'href' => array(),
					  'target' => array(),
					 )
			   );
   
			   ?>
			   
			 <div class="taiowc-guide">

  <!-- STEP 1 -->
  <div class="taiowc-card">
    <div class="taiowc-step">
      <span class="step-number">1</span>
      <div class="step-header">
        <h3><?php esc_html_e('Display cart in the header menu','th-all-in-one-woo-cart'); ?></h3>
        <span class="badge pro"><?php esc_html_e('PRO FEATURE','th-all-in-one-woo-cart'); ?></span>
        <span class="badge success"><?php esc_html_e('VISUAL EDITOR READY','th-all-in-one-woo-cart'); ?></span>
      </div>
    </div>

    <p class="step-desc">
      <?php esc_html_e('Recommended for standard WordPress themes.','th-all-in-one-woo-cart'); ?>
    </p>

    <p class="step-text">
      <?php esc_html_e('Navigate to your dashboard','th-all-in-one-woo-cart'); ?> <b><?php esc_html_e('Appearance → Menus','th-all-in-one-woo-cart'); ?></b>.  
       <b><?php esc_html_e('Locate the TH All In One Woo Cart','th-all-in-one-woo-cart'); ?></b> <?php esc_html_e('item in the left panel and click','th-all-in-one-woo-cart'); ?>
      <b><?php esc_html_e('Add to Menu','th-all-in-one-woo-cart'); ?></b>.
    </p>

    <div class="note-box">
      <strong><?php esc_html_e('NOTE','th-all-in-one-woo-cart'); ?></strong><br>
      <?php esc_html_e('Position the cart element as the last item in your primary navigation for optimal UI balance.','th-all-in-one-woo-cart'); ?>
    </div>
  </div>

  <!-- STEP 2 -->
  <div class="taiowc-card">
    <div class="taiowc-step">
      <span class="step-number">2</span>
      <div class="step-header">
        <h3><?php esc_html_e('Use Shortcode Component','th-all-in-one-woo-cart'); ?></h3>
        <span class="badge universal"><?php esc_html_e('UNIVERSAL','th-all-in-one-woo-cart'); ?></span>
      </div>
    </div>

    <p class="step-text">
      <?php esc_html_e('Inject the cart into any page builder (Elementor, Divi, Gutenberg) or standard text block.','th-all-in-one-woo-cart'); ?>
    </p>

    <div class="shortcode-box">
     <code><?php echo esc_html_e( '[taiowc]' ); ?></code>
		<button type="button"
		        class="taiowc-copy-btn"
		        aria-label="<?php esc_attr_e( 'Copy shortcode', 'th-all-in-one-woo-cart' ); ?>"
		        data-copy-target="code">
		    <?php esc_html_e( 'Copy', 'th-all-in-one-woo-cart' ); ?>
		</button>
      <span class="shortcode-label"><?php esc_html_e('SHORTCODE','th-all-in-one-woo-cart'); ?></span>
    </div>

    <div class="supported">
      <span><?php esc_html_e('✔ Widgets','th-all-in-one-woo-cart'); ?></span>
      <span><?php esc_html_e('✔ Blocks','th-all-in-one-woo-cart'); ?></span>
      <span><?php esc_html_e('✔ Elementor','th-all-in-one-woo-cart'); ?></span>
    </div>
  </div>

  <!-- STEP 3 -->
<div class="taiowc-card">
  <div class="taiowc-step">
    <span class="step-number">3</span>
    <div class="step-header">
      <h3><?php esc_html_e('Direct Template Integration','th-all-in-one-woo-cart'); ?></h3>
      <span class="badge advanced"><?php esc_html_e('ADVANCED','th-all-in-one-woo-cart'); ?></span>
    </div>
  </div>

  <p class="step-desc">
      <span><?php esc_html_e('For theme developers. Paste this snippet directly into your header.php or custom template files.','th-all-in-one-woo-cart'); ?></span>
 
  </p>

  <div class="shortcode-box php">
    <code>&lt;?php echo do_shortcode('[taiowc]'); ?&gt;</code>
     <button type="button"
          class="taiowc-copy-btn"
          aria-label="<?php esc_attr_e( 'Copy PHP code', 'th-all-in-one-woo-cart' ); ?>"
          data-copy-target="code">
      <span><?php esc_html_e('Copy','th-all-in-one-woo-cart'); ?></span>
    
  </button>
    <span class="shortcode-label"><?php esc_html_e('PHP','th-all-in-one-woo-cart'); ?></span>
  </div>

</div>


</div>

   
   
		   <?php 	
   
			   endif;
		}

		public function number_field_callback( $args ) {

			$value = esc_attr( $this->get_option( $args['id'] ) );

			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'small';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';
            ?>

			<input type="number"  <?php echo esc_attr($attrs); ?> class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="<?php echo esc_attr($value); ?>"  min="<?php echo esc_attr($args['min']); ?>" max="<?php echo esc_attr($args['max']); ?>" step="<?php  if ( ! empty($args['step']) ) { 
				echo esc_attr($args['step']); } ?>" />

              <?php if(isset( $args['suffix'] ) && ! is_null( $args['suffix'] ) ){ ?>

			<span><?php echo esc_attr($args['suffix']); ?></span>
         
             <?php

               }

           if ( ! empty( $args['desc'] ) ) { ?>

           <p class="description"><?php echo esc_html($args['desc']);?></p>    

		<?php 	

	         } 
		}
	      /**
	     * Print a colorpicker
	     *
	     * @since 1.0
	     * @param string $key
	     * @param string $value
	     */
	     public function colorpkr_field_callback( $args ){

			$value = esc_attr( $this->get_option( $args['id'] ) );
			
			?>

		  <input type="text" class="color_picker" id="<?php echo esc_attr($args['id']);?>" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="<?php echo esc_attr($value); ?>" style="background:<?php echo esc_attr($value); ?>" />
          
          <?php if ( ! empty( $args['desc'] ) ) { ?>

           <p class="description"><?php echo esc_html($args['desc']);?></p>      

		<?php
	        }

		}

	     public function radio_image_field_callback( $args ) {

			$options = apply_filters( "taiowc_settings_{$args[ 'id' ]}_radio_options", $args['options'] );
			$value   = esc_attr( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';

			return implode( '', array_map( function ( $key, $option ) use ( $attrs, $args, $value ) {
				echo sprintf( '<label class="radio-image"><input id="%2$s" %1$s type="radio"  name="%4$s[%2$s]" value="%3$s" %5$s/> <img src="%6$s"/> </label>', esc_attr($attrs), esc_attr($args['id']), esc_attr($key), esc_attr($this->settings_name), checked( esc_attr($value), esc_attr($key), false ), esc_attr($option));
			}, array_keys( $options ), $options ) );

		}

		public function usefullplugin_field_callback( $args ) {

			$is_html = isset( $args['html'] );

			if ( $is_html ) {

				$html = $args['html'];

			  } else {
				$plugin_image  = esc_url( $args['plugin_image'] );
				$plugin_title  = $args['plugin_title'];
				$plugin_link   = $args['plugin_link'];
				
			}?>


			<div class="taiowc-use-plugin"><img src="<?php echo esc_url($plugin_image);?>" /><a target="_blank" href="<?php echo esc_url($plugin_link);?>"><?php echo esc_html($plugin_title);?></a>
			</div>

		<?php }

	//*********************************/	
    // add ,delete ,get , reset, option
    /**********************************/

    public function set_defaults() {
			foreach ( $this->fields as $tab_key => $tab ) {
				$tab = apply_filters( 'taiowc_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'taiowc_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section' : $section['id'];

					$section['fields'] = apply_filters( 'taiowc_settings_fields', $section['fields'], $section, $tab );

					foreach ( $section['fields'] as $field ) {
						if ( isset( $field['pro'] ) ) {
							continue;
						}
						$field['default'] = isset( $field['default'] ) ? $field['default'] : null;
						$this->set_default( $field['id'], $field['type'], $field['default'] );
					}
				}
			}
		}


		public function sanitize_callback( $options ) {

			foreach ( $this->get_defaults() as $opt ) {
				if ( $opt['type'] === 'checkbox' && ! isset( $options[ $opt['id'] ] ) ){
					$options[ $opt['id'] ] = 0;
				}
			}

			return $options;
		}

		public function settings_init() {

			if ( $this->is_reset_all() ) {
				 $this->delete_settings();
				 wp_safe_redirect(esc_url($this->settings_url()));
				 exit;
			}
              
		  register_setting( $this->settings_name, $this->settings_name, array( $this, 'sanitize_callback' ) );

			foreach ( $this->fields as $tab_key => $tab ) {

				$tab = apply_filters( 'taiowc_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'taiowc_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section-' . $section_key : $section['id'];

					// Adding Settings section id
					$this->fields[ $tab_key ]['sections'][ $section_key ]['id'] = $section['id'];

					add_settings_section( $tab['id'] . $section['id'], $section['title'], function () use ( $section ) {
						if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
							echo '<div class="inside">' . esc_html($section['desc']) . '</div>';
						}
					}, $tab['id'] . $section['id'] );

					$section['fields'] = apply_filters( 'taiowc_settings_fields', $section['fields'], $section, $tab );

					foreach ( $section['fields'] as $field ) {

						$field['label_for'] = $field['id'] . '-field';
						$field['default']   = isset( $field['default'] ) ? $field['default'] : null;

						if ( $field['type'] == 'checkbox' || $field['type'] == 'radio' ) {
							unset( $field['label_for'] );
						}

						add_settings_field( $this->settings_name . '[' . $field['id'] . ']', $field['title'], array(
							$this,
							'field_callback'
						), $tab['id'] . $section['id'], $tab['id'] . $section['id'], $field );

					}
				}
			}
		}

		public function reset_url() {
			return add_query_arg( 
				array( 
					'page' => 'taiowc', 
					'reset' => 'reset',
					'delete_wpnonce' => wp_create_nonce('delete_nonce')
					 ),
					 admin_url( 'admin.php' ) 
					);
		}

		public function settings_url(){
			return add_query_arg( 
				array( 
					'page' => 'taiowc',
					'_wpnonce' => wp_create_nonce('_nonce')
					 ), admin_url( 'admin.php' ) );
		}

        private function set_default( $key, $type, $value ) {
		$this->defaults[ $key ] = array( 'id' => $key, 'type' => $type, 'value' => $value );
		}

		private function get_default( $key ) {
			return isset( $this->defaults[ $key ] ) ? $this->defaults[ $key ] : null;
		}

		public function get_defaults() {
			return $this->defaults;
		}


        public function is_reset_all() {
			return isset( $_GET['page'] ) && ( sanitize_text_field( wp_unslash( $_GET['page'] ) ) == 'taiowc' ) && isset( $_GET[ $this->setting_reset_name ] );
		}  

        public function delete_settings() {

        	if ( ! current_user_can( 'manage_options' ) ) {

            wp_die( - 1, 403 );

            }

            if ( isset( $_REQUEST['delete_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['delete_wpnonce'] ) ), 'delete_nonce' ) ) {

			do_action( sprintf( 'delete_%s_settings', $this->settings_name ), $this );

			// license_key should not updated

			return delete_option( $this->settings_name );

		   }

		}

		public function get_option( $option ) {
			$default = $this->get_default( $option );
			$options = get_option( $this->settings_name );
			$is_new = ( ! is_array( $options ) && is_bool( $options ) );

			// Theme Support
			if ( current_theme_supports( $this->theme_feature_name ) ) {
				$theme_support    = get_theme_support( $this->theme_feature_name );
				$default['value'] = isset( $theme_support[0][ $option ] ) ? $theme_support[0][ $option ] : $default['value'];
			}

			$default_value = isset( $default['value'] ) ? $default['value'] : null;

			if ( ! is_null( $this->get_reserved( $option ) ) ) {
				$default_value = $this->get_reserved( $option );
			}

			if ( $is_new ) {
			
				return $default_value;
			} else {
			
				return isset( $options[ $option ] ) ? $options[ $option ] : $default_value;
			}
		}

		public function get_options(){
			return get_option( $this->settings_name );
		}

		public function get_reserved( $key = false ){

			$data = (array) get_option( $this->reserved_key );
			if ( $key ) {
				return isset( $data[ $key ] ) ? $data[ $key ] : null;
			} else {
				return $data;
			}
		}
		
        public function script_enqueue(){
			//get_current_screen()->base
        	if (isset($_GET['page']) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) == 'taiowc') {

				// STYEL
				wp_enqueue_style( 'taiowc-admin', TAIOWC_PLUGIN_URI. 'assets/css/admin.css', array(), TAIOWC_VERSION );
				// Add inline styles
                wp_add_inline_style('taiowc-admin', taiowc_admin_style() );  
				wp_enqueue_style( 'taiowc-pickr-nano-css', TAIOWC_PLUGIN_URI. 'assets/css/nano.min.css', array(), TAIOWC_VERSION );

				//SCRIPT
				wp_enqueue_script( 'taiowc-pickr-script', TAIOWC_PLUGIN_URI. 'assets/js/pickr.min.js', array('jquery'),TAIOWC_VERSION, true);
				wp_enqueue_script( 'taiowc-setting-script', TAIOWC_PLUGIN_URI. 'assets/js/taiowc-setting.js', array('jquery'),TAIOWC_VERSION, true);
				wp_enqueue_media();

				wp_localize_script(
					'taiowc-setting-script', 'taiowcluginObject', array(
						'media_title'   => esc_html__( 'Choose an Image', 'th-all-in-one-woo-cart' ),
						'button_title'  => esc_html__( 'Use Image', 'th-all-in-one-woo-cart' ),
						'add_media'     => esc_html__( 'Add Media', 'th-all-in-one-woo-cart' ),
						'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
						'nonce'         => wp_create_nonce( 'taiowc_plugin_nonce' ),
					)
				);
				
			}
		}

}

endif;