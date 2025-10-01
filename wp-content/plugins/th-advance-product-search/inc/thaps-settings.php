<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'TH_Advancde_Product_Search_Set' ) ):

	class TH_Advancde_Product_Search_Set {

		private $setting_name = 'th_advance_product_search';
		private $setting_reset_name = 'reset';
		private $theme_feature_name = 'th-advance-product-search';
		private $slug;
		private $plugin_class;
		private $defaults = array();
		private $fields = array();
		private $reserved_key = '';
		private $reserved_fields = array();
		private $settings_name;
	
		public function __construct() {
			$this->settings_name = apply_filters('thaps_settings_name', $this->setting_name);
             $this->fields          = apply_filters( 'thaps_settings', $this->fields );
             $this->reserved_key    = sprintf( '%s_reserved', $this->settings_name );
		     $this->reserved_fields = apply_filters( 'thaps_reserved_fields', array() );
 
             add_action( 'admin_menu', array( $this, 'add_menu' ) );
             add_action( 'init', array( $this, 'set_defaults' ), 8 );
             add_action( 'admin_init', array( $this, 'settings_init' ), 90 );
             add_action( 'admin_enqueue_scripts', array( $this, 'script_enqueue' ) );

             add_action('wp_ajax_thaps_form_setting', array($this, 'thaps_form_setting'));
			 add_action( 'wp_ajax_nopriv_thaps_form_setting', array($this, 'thaps_form_setting'));

			 add_action('wp_ajax_thaps_reset_settings', array($this, 'thaps_reset_settings'));
			 add_action( 'wp_ajax_nopriv_thaps_reset_settings', array($this, 'thaps_reset_settings'));

            }
        

        public function add_menu(){

						$page_title = esc_html__( 'Advance Search', 'th-advance-product-search' );

						add_submenu_page( 'themehunk-plugins', $page_title, $page_title, 'manage_options', 'th-advance-product-search', array($this, 'settings_form'),11 );


		 }

		public function form_add_class(){
              $classes='';

			  if($this->get_option( 'show_submit' ) == 1){

			  $classes .= ' show-submit ';

			  }

			  $classes .= $this->get_option( 'select_srch_type' );

			  return $classes;
				
		}

		public function settings_form() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.','th-advance-product-search' ) );
			}
		
			?>
			<div id="thaps" class="settings-wrap">
				<?php $this->options_tabs(); ?>

				
				
                   <div class="setting-wrap integration">

                   	 <div class="top-header">
                <h2 class="tabheading"><?php esc_html_e("Integration", 'th-advance-product-search'); ?></h2>
               
               	 <a href="<?php echo esc_url( 'https://themehunk.com/advance-product-search/' ); ?>"
			   title="<?php esc_attr_e( 'Upgrade', 'th-advance-product-search' ); ?>"
			   target="_blank" class="upgrade-button">
				<?php esc_html_e( 'Upgrade', 'th-advance-product-search' ); ?>
			</a>
					  <p class="submit thaps-button-wrapper">
						
						
						 <button  disabled id="submit" class="button button-primary" value="<?php esc_html_e( 'Save All Changes', 'th-advance-product-search' ); ?>"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save transition-transform group-hover:scale-110" aria-hidden="true"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path><path d="M7 3v4a1 1 0 0 0 1 1h7"></path></svg></span><span class="text"><?php esc_html_e( 'Save All Changes', 'th-advance-product-search-pro' ); ?></span>
						 </button>
						  <a onclick="return confirm('<?php esc_attr_e( 'Are you sure to reset current settings?', 'th-advance-product-search' ); ?>');" class="reset" href="#"><?php esc_html_e( 'Reset all', 'th-advance-product-search-pro' ); ?>
						</a>
					</p>

					</div>

                   <div class="setting-content">            
					<form method="post" action="" enctype="multipart/form-data" class="thaps-setting-form  <?php echo esc_attr($this->form_add_class());?>">
                 <input type="hidden" name="action" value="thaps_form_setting">

					 <div id="settings-tabs">
						<?php foreach ( $this->fields as $tab ):

							if ( ! isset( $tab['active'] ) ) {
								$tab['active'] = false;
							}
							$is_active = ( $this->get_last_active_tab() == $tab['id'] );

							?>

							<div id="<?php echo esc_attr($tab['id']); ?>"
								 class="settings-tab thaps-setting-tab"
								 style="<?php echo ! esc_attr($is_active) ? 'display: none' : '' ?>">
								 
								<?php foreach ( $tab['sections'] as $section ): ?>
									<div class="thaps-section-wrapper <?php echo esc_attr($tab['id']); ?>" data-section="<?php echo esc_attr($section['id']); ?>">
					        <?php	$this->do_settings_sections( $tab['id'] . $section['id'] ); ?>
					   				 </div>

							<?php	endforeach; ?>
							</div>

						<?php endforeach; ?>
					</div>

					</form>

					<?php
					$this->last_tab_input();
					
					?>

			</div> 

			
			<?php require_once TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/inc/tapsp-live-preview.php'; ?> 
			<?php require_once TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/inc/tapsp-reset.php'; ?> 
			<?php require_once TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/inc/tapsp-help.php'; ?>
			<?php require_once TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_PATH . '/inc/thaps-premium.php'; ?>



            </div>
           
				
				
			</div>
			<?php
			
		}

	    public function thaps_form_setting(){  

	    	  if ( ! current_user_can( 'administrator' ) ) {

		            wp_die( - 1, 403 );
		            
		      } 

              check_ajax_referer( 'thaps_plugin_nonce','_wpnonce');

	                if( isset($_POST['th_advance_product_search']) ){
	             	       
	                      $sanitize_data_array = $this->thaps_form_sanitize($_POST['th_advance_product_search']);

	                      update_option('th_advance_product_search',$sanitize_data_array);         
		            }
		            
		            die();  
	    }
        
	    public function thaps_form_sanitize( $input ){
				$new_input = array();
				foreach ( $input as $key => $val ){
					$new_input[ $key ] = ( isset( $input[ $key ] ) ) ? sanitize_text_field( $val ) :'';
		   }
		   return $new_input;

	    }
	
		public function options_tabs() {

			?>

			<div class="nav-tab-wrapper wp-clearfix">

				<div class="top-wrap">

					<div id="logo">
						<a href="<?php echo esc_url('https://themehunk.com/advance-product-search/'); ?>" target="_blank">
						<img src='<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'images/resp-logo.png') ?>' alt="thaps-logo" class="resp-logo"/>
						<img src='<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'images/th-logo.png') ?>' alt="th-logo" class="th-logo"/>
					</a>
					</div>

				  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			     </div>

				<?php foreach ( $this->fields as $tabs ): ?>

				  <a data-target="<?php echo esc_attr($tabs['id']); ?>"  class="thaps-setting-nav-tab nav-tab <?php echo esc_html($this->get_options_tab_css_classes( $tabs )); ?> " href="#<?php echo esc_attr($tabs['id']); ?>" title="<?php echo esc_html($tabs['title']); ?>"><span><?php echo $this->icon_list($tabs['id']); ?></span><?php echo esc_html($tabs['title']); ?>	
					</a>

				<?php endforeach; ?>

				<div class="thaps-collapse-sidebar">
				    <button id="thaps-toggle-sidebar">
				        <span class="dashicons dashicons-arrow-left-alt2"></span>
				        <span class="collapse-text">Collapse Sidebar</span>
				    </button>
				</div>
				
			</div>

			<?php
		}
			function icon_list($id = '') {

    $icons = array(
        'integration'      => 'integration.svg',
        'search-bar'       => 'setting.svg',
        'autosetting'      => 'completesetting.svg',
        'search-configure' => 'searchconfig.svg',
        'style'            => 'interface.svg',
        'analytics'        => 'analytics.svg',
        'thaps_index_builder'    => 'loading.svg',
        'thaps_fuzzy_settings'   => 'fuzzy.svg',
        'reset'            => 'reset.svg',
        'help'            => 'help.svg',
    );

    if (!isset($icons[$id])) {
        return '';
    }

    // Absolute path to plugin root
    $svg_path = plugin_dir_path(dirname(__FILE__)) . 'images/' . $icons[$id];

    if (file_exists($svg_path)) {
        return file_get_contents($svg_path);
    }

    return '';
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
                
				// if ( $section['title'] ) {

				// 	echo "<h2 class=".esc_attr($section['id']).">".esc_html($section['title'])."</h2>";

				// }
                
				if ( $section['callback'] ) {

					call_user_func( $section['callback'], $section );

				}

				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {

					continue;

				}

				echo '<div class="form-table" id='.esc_attr($section['id']).'>';

				// To add subtitle start
				$subtitle= '';
				foreach ( $this->fields as $tab ) {
				    foreach ( $tab['sections'] as $sec ) {
				        if ( $tab['id'] . $sec['id'] === $page ) {
				            if ( ! empty( $sec['subtitle'] ) ) {
				                $subtitle = $sec['subtitle'];
				            }
				        }
				    }
				}

				echo '<div class="headingwrapper">';
				if ( $section['title'] ) {

					echo '<h2 class="heading ' . esc_attr($section['id']) . '">' . esc_html($section['title']) . '</h2>';

				}

				if ( $subtitle ) {
				    echo '<p class="thaps-section-subtitle">' . esc_html( $subtitle ) . '</p>';
				}
				echo '</div>';

					// To add subtitle End
				$this->do_settings_fields( $page, $section['id'] );
				echo '</div>';
			}
		}

		private function last_tab_input() {
			printf( '<input type="hidden" id="_last_active_tab" name="%s[_last_active_tab]" value="%s">', $this->settings_name, $this->get_last_active_tab() );
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
				
				$custom_attributes = $this->array2html_attr( isset( $field['args']['attributes'] ) ? $field['args']['attributes'] : array() );

				$wrapper_id = ! empty( $field['args']['id'] ) ? esc_attr( $field['args']['id'] ) . '-wrapper' : '';

				$dependency = ! empty( $field['args']['require'] ) ? $this->build_dependency( $field['args']['require'] ) : '';

				printf( '<div id="%s" class="thaps-settings-row" %s %s>', $wrapper_id, $custom_attributes, $dependency );

				
					echo '<div scope="row" class="thaps-settings-label">';

					if ( ! empty( $field['args']['label_for'] ) ) {

						echo '<label for="' . esc_attr( $field['args']['label_for'] ) . '">' . esc_html($field['title']). '</label>';

					} else {

						echo esc_html($field['title']);
					}

					echo '</div>';
					echo '<div class="thaps-settings-field-content">';
					call_user_func( $field['callback'], $field['args'] );
					echo '</div>';
				
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

			return 'data-thapsdepends="[' . esc_attr( wp_json_encode( $b_array ) ) . ']"';
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

				case 'color':
					$this->color_field_callback( $field );
					break;

				case 'html':
					$this->html_field_callback( $field );
					break;

			    case 'analytics-html':
					$this->analytics_html_field_callback( $field );
					break;		 			

				default:
					$this->text_field_callback( $field );
					break;
			}
			do_action( 'thaps_settings_field_callback', $field );
		}

     
      public function checkbox_field_callback( $args ) {
               
			$value = (bool)( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';?>

            <fieldset>
            	<label class="th-toggle">
            		<input <?php echo esc_attr($attrs); ?> type="checkbox" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="1" <?php echo esc_attr(checked( $value, true, false ));?>> <?php if ( ! empty( $args['desc'] ) ) {  echo esc_html($args['desc']); } ?>
            	</label>     
            </fieldset>


        <?php 
			
		}
			
		public function select_field_callback( $args ) {

			$options = apply_filters( "thaps_settings_{$args[ 'id' ]}_select_options", $args['options'] );

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
		

			public function html_field_callback( $args ){

         if(

         	$args[ 'id' ]=='how-to-integrate'):

            $thaps_karr = array( 
            'br' => array(),
            'strong' => array(),
            'code' => array(),
            'a' => array( 
                   'href' => array(),
                   'target' => array(),
                  )
            );

         	?>
			
		   <div class="th-search-docs">
  <!-- Row 1 -->
  <div class="th-doc-row">
    
    <div class="th-doc-left">
      <h3><?php esc_html_e('Default Search Bar','th-advance-product-search-pro'); ?></h3>
      <p><?php esc_html_e('Use the standard shortcode to display the default product search bar anywhere in your theme.','th-advance-product-search-pro'); ?></p>
    </div>

    <div class="th-doc-right">

      <div class="th-search-preview">
        <input type="text" placeholder="Product Search">
        <button><?php esc_html_e('Submit','th-advance-product-search-pro'); ?></button>
      </div>

      <div class="th-shortcode">
        <span><?php esc_html_e('Shortcode','th-advance-product-search-pro'); ?></span> <code>[th-aps]</code>
      </div>
      <div class="th-code-box">

      	<pre><code>[th-aps]</code></pre>
      	<div class="right">
        <span class="th-code-label"><?php esc_html_e('Shortcode','th-advance-product-search-pro'); ?></span>

        <button class="copy-btn"><?php esc_html_e('Copy','th-advance-product-search-pro'); ?></button>
    	</div>

        
      </div>

    </div>

  </div>


  <!-- Row 2 -->
  <div class="th-doc-row">

    <div class="th-doc-left">
      <h3><?php esc_html_e('Search Bar with Icon','th-advance-product-search-pro'); ?></h3>
      <p><?php esc_html_e('Display a more modern search bar that includes a search icon inside the input field.','th-advance-product-search-pro'); ?></p>
    </div>

    <div class="th-doc-right">

      <div class="th-search-preview icon">
        <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4 text-gray-400" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg></span>
        <input type="text" placeholder="Product Search">
      </div>

      <div class="th-shortcode">
        <span><?php esc_html_e('Shortcode:','th-advance-product-search-pro'); ?></span> <code>[th-aps layout="bar_style"]</code>
      </div>

          <div class="th-code-box">

        <span class="th-code-label"><?php esc_html_e('Shortcode','th-advance-product-search-pro'); ?></span>

        <button class="copy-btn"><?php esc_html_e('Copy','th-advance-product-search-pro'); ?></button>

        <pre><code>[th-aps layout="bar_style"]</code></pre>

      </div>

    </div>

  </div>


  <!-- Row 3 -->
  <div class="th-doc-row">

    <div class="th-doc-left">
      <h3><?php esc_html_e('PHP Template Tag','th-advance-product-search-pro'); ?></h3>
      <p><?php esc_html_e('For developers: Insert the search bar directly into your theme’s PHP templates.','th-advance-product-search-pro'); ?></p>
    </div>

    <div class="th-doc-right">

      <div class="th-code-box">

        <span class="th-code-label"><?php esc_html_e('PHP','th-advance-product-search-pro'); ?></span>

        <button class="copy-btn"><?php esc_html_e('Copy','th-advance-product-search-pro'); ?></button>

        <pre><code>&lt;?php echo do_shortcode('[th-aps]'); ?&gt;</code></pre>

      </div>

    </div>

  </div>

</div>

		<?php 		
			endif;
		}

        public function analytics_html_field_callback($args){

            if($args[ 'id' ]=='how-to-integrate-analytics'):

			?>
             
              <div class="th-search-analytics-wrapper">
            	 <h4><?php esc_html_e( 'Enable Site Search module Paste the following code into "functions.php" in your child theme.', 'th-advance-product-search' ); ?>: </h4>

            	  <div class="th-code-box">
			        <span class="th-code-label"><?php esc_html_e('Php','th-advance-product-search'); ?></span>
			        <button class="copy-btn"><?php esc_html_e('Copy','th-advance-product-search'); ?></button>
			        <pre><code>apply_filters("thaps_enable_ga_site_search_module", "__return_true" );</code></pre>
      			  </div>

      			   <h4><?php esc_html_e( 'To disable integrarion with Google Analytics paste following code "functions.php" your child theme.:', 'th-advance-product-search' ); ?>: </h4>

            	  <div class="th-code-box">
			        <span class="th-code-label"><?php esc_html_e('Php','th-advance-product-search'); ?></span>
			        <button class="copy-btn"><?php esc_html_e('Copy','th-advance-product-search'); ?></button>
			        <pre><code>apply_filters("thaps_google_analytics_events", "__return_false" );</code></pre>
      			  </div>

      			  <div class="image-wrapper">
      			  	<img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_IMAGES_URI.'google-analtyitcs-result.png'); ?>" />
      			  </div>
      			   <p><a target="_blank" href="<?php echo esc_url('https://themehunk.com/docs/th-advance-product-search/#google-analytics');?>" class="explore-google-analytics"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link h-4 w-4" aria-hidden="true"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg><?php esc_html_e('Explore Doc','th-advance-product-search');?></a></p>

            </div>

        <?php endif; }


		public function color_field_callback( $args ){

			$value = esc_attr( $this->get_option( $args['id'] ) );
			
			$alpha = isset( $args['alpha'] ) && $args['alpha'] === true ? $args['alpha'] : false;?>

			<input type="text" data-alpha-enabled="<?php echo esc_attr($alpha); ?>" class="thaps-color-picker" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="<?php echo esc_attr($value); ?>"  data-default-color="<?php echo esc_attr($value); ?>" />
          
          <?php if ( ! empty( $args['desc'] ) ) { ?>

           <p class="description"><?php echo esc_html($args['desc']);?></p>      

		<?php
	        }

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


	//*********************************/	
    // add ,delete ,get , reset, option
    /**********************************/

    public function set_defaults() {
			foreach ( $this->fields as $tab_key => $tab ) {
				$tab = apply_filters( 'thaps_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'thaps_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section' : $section['id'];

					$section['fields'] = apply_filters( 'thaps_settings_fields', $section['fields'], $section, $tab );

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

		  register_setting( $this->settings_name, $this->settings_name, array( $this, 'sanitize_callback' ) );

			foreach ( $this->fields as $tab_key => $tab ) {

				$tab = apply_filters( 'thaps_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'thaps_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section-' . $section_key : $section['id'];

					// Adding Settings section id
					$this->fields[ $tab_key ]['sections'][ $section_key ]['id'] = $section['id'];

					add_settings_section( $tab['id'] . $section['id'], $section['title'], function () use ( $section ) {
						if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
							echo '<div class="inside">' . esc_html($section['desc']) . '</div>';
						}
					}, $tab['id'] . $section['id'] );

					$section['fields'] = apply_filters( 'thaps_settings_fields', $section['fields'], $section, $tab );

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


		public function settings_url(){

			return add_query_arg( 

				array( 
				'page' => 'th-advance-product-search',
				'_wpnonce' => wp_create_nonce('_nonce'),
				 ),
				 admin_url( 'admin.php' ) );

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


		public function thaps_reset_settings() {

			if ( ! current_user_can( 'administrator' ) ) {
  
				wp_die( - 1, 403 );
				
			} 

			check_ajax_referer('thaps_plugin_nonce','nonce');
			
			do_action( sprintf( 'delete_%s_settings', $this->settings_name ), $this );

			return delete_option( $this->settings_name );

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
			
			if (isset($_GET['page']) && $_GET['page'] == 'th-advance-product-search') {

				wp_enqueue_style( 'wp-color-picker' );
			
				wp_enqueue_style( 'th-advance-product-search-admin', TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI. 'assets/css/admin.css', array(), TH_ADVANCE_PRODUCT_SEARCH_VERSION );
				
				wp_enqueue_script(
    'wp-color-picker-alpha',
    TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI . 'assets/js/wp-color-picker-alpha.js',
    array('wp-color-picker'),
    '1.0',   // ✅ version
    true     // ✅ footer
);

				wp_enqueue_script( 'thaps-setting-script', TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI. 'assets/js/thaps-setting.js', array('jquery'),true);

				wp_localize_script(
					'thaps-setting-script', 'THAPSPluginObject', array(
						'media_title'   => esc_html__( 'Choose an Image', 'th-advance-product-search' ),
						'button_title'  => esc_html__( 'Use Image', 'th-advance-product-search' ),
						'add_media'     => esc_html__( 'Add Media', 'th-advance-product-search' ),
						'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
						'nonce'         => wp_create_nonce( 'thaps_plugin_nonce' ),
						'reset_text'    => esc_html__( 'Are you sure to reset current settings?', 'th-advance-product-search' ),
					)
				);
			}
		}

}

endif;