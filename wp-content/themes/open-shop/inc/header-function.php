<?php 
/**
 * Header Function for open shop theme.
 * 
 * @package     Open Shop
 * @author      ThemeHunk
 * @since       Open Shop 1.0.0
 */
/**************************************/
//Top Header function
/**************************************/
if ( ! function_exists( 'open_shop_top_header_markup' ) ){	
function open_shop_top_header_markup(){ 
$open_shop_above_header_layout     = get_theme_mod( 'open_shop_above_header_layout','abv-none');
$open_shop_above_header_col1_set   = get_theme_mod( 'open_shop_above_header_col1_set','text');
$open_shop_above_header_col2_set   = get_theme_mod( 'open_shop_above_header_col2_set','text');
$open_shop_above_header_col3_set   = get_theme_mod( 'open_shop_above_header_col3_set','text');
$open_shop_menu_open = get_theme_mod('open_shop_mobile_menu_open','left');
if($open_shop_above_header_layout!=='abv-none'):?> 
<div class="top-header">
      <div class="container">
      	<?php if($open_shop_above_header_layout=='abv-three'){?>
        <div class="top-header-bar thnk-col-3">
          <div class="top-header-col1"> 
          	<?php open_shop_top_header_conetnt_col1($open_shop_above_header_col1_set,$open_shop_menu_open); ?>
          </div>
          <div class="top-header-col2">
          	<?php open_shop_top_header_conetnt_col2($open_shop_above_header_col2_set,$open_shop_menu_open); ?>
          </div>
          <div class="top-header-col3">
          	<?php open_shop_top_header_conetnt_col2($open_shop_above_header_col3_set,$open_shop_menu_open); ?>
          </div>
        </div> 
    <?php } ?>
    <?php if($open_shop_above_header_layout=='abv-two'){?>
        <div class="top-header-bar thnk-col-2">
          <div class="top-header-col1"> 
          	<?php open_shop_top_header_conetnt_col1($open_shop_above_header_col1_set,$open_shop_menu_open); ?>
          </div>
          <div class="top-header-col2">
          	<?php open_shop_top_header_conetnt_col2($open_shop_above_header_col2_set,$open_shop_menu_open); ?>
          </div>
        </div> 
    <?php } ?>
    <?php if($open_shop_above_header_layout=='abv-one'){
    	?>
        <div class="top-header-bar thnk-col-1">
          <div class="top-header-col1"> 
          	<?php open_shop_top_header_conetnt_col1($open_shop_above_header_col1_set,$open_shop_menu_open); ?>
          </div>
        </div> 
    <?php } ?>
      <!-- end top-header-bar -->
   </div>
</div>
<?php endif;
   }
}
add_action( 'open_shop_top_header', 'open_shop_top_header_markup' );

//************************************/
// top header col1 function
//************************************/
if ( ! function_exists( 'open_shop_top_header_conetnt_col1' ) ){ 
function open_shop_top_header_conetnt_col1($content,$mobileopen){ ?>
<?php if($content=='text'){?>
<div class='content-html'>
  <?php echo esc_html(get_theme_mod('open_shop_col1_texthtml',  __( 'Add your content here', 'open-shop' )));?>
</div>
<?php }elseif($content=='menu'){
if ( has_nav_menu('open-shop-above-menu' ) ){?>
<!-- Menu Toggle btn-->
     <nav> 
        <div class="menu-toggle">
            <button type="button" class="menu-btn" id="menu-btn-abv">
              <div class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-align-justify w-5 h-5 text-gray-500 group-hover:text-gray-700" aria-hidden="true"><path d="M3 5h18"></path><path d="M3 12h18"></path><path d="M3 19h18"></path></svg>
                </div>
            </button>
        </div>
        <div class="sider above open-shop-menu-hide  <?php echo esc_attr($mobileopen);?>">
        <div class="sider-inner">
        <?php open_shop_abv_nav_menu();?>
        </div>
      </div>
    </nav>
<?php 
  }else{?>
<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Assign Above header menu', 'open-shop' );?></a>
 <?php }
}elseif($content=='widget'){?>
  <div class="content-widget">
    <?php if( is_active_sidebar('top-header-widget-col1' ) ){
    dynamic_sidebar('top-header-widget-col1' );
     }else{?>
      <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Add Widget', 'open-shop' );?></a>
     <?php }?>
     </div>
<?php }elseif($content=='social'){?>
<div class="content-social">
<?php echo open_shop_social_links();?>
</div>
<?php }elseif($content=='none'){
return true;
}?>
<?php }
}
//************************************/
// top header col2 function
//************************************/
if ( ! function_exists( 'open_shop_top_header_conetnt_col2' ) ){ 
function open_shop_top_header_conetnt_col2($content,$mobileopen){ ?>
<?php if($content=='text'){?>
<div class='content-html'>
  <?php echo esc_html(get_theme_mod('open_shop_col2_texthtml',  __( 'Add your content here', 'open-shop' )));?>
</div>
<?php }elseif($content=='menu'){
  if ( has_nav_menu('open-shop-above-menu' ) ){?>
<!-- Menu Toggle btn-->
        <nav> 
        <div class="menu-toggle">
            <button type="button" class="menu-btn" id="menu-btn-abv">
                <div class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-align-justify w-5 h-5 text-gray-500 group-hover:text-gray-700" aria-hidden="true"><path d="M3 5h18"></path><path d="M3 12h18"></path><path d="M3 19h18"></path></svg>
                </div>
            </button>
        </div>
        <div class="sider above open-shop-menu-hide <?php echo esc_attr($mobileopen);?>">
        <div class="sider-inner">
        <?php open_shop_abv_nav_menu();?>
        </div>
      </div>
    </nav>
<?php 
 }else{?>
<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Assign Above header menu', 'open-shop' );?></a>
 <?php }
}
elseif($content=='widget'){?>
  <div class="content-widget">
    <?php if( is_active_sidebar('top-header-widget-col2' ) ){
    dynamic_sidebar('top-header-widget-col2' );
     }else{?>
      <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Add Widget', 'open-shop' );?></a>
     <?php }?>
     </div>
<?php }elseif($content=='social'){?>
<div class="content-social">
<?php echo open_shop_social_links();?>
</div>
<?php }elseif($content=='none'){
return true;
}?>
<?php }
}
//************************************/
// top header col3 function
//************************************/
if ( ! function_exists( 'open_shop_top_header_conetnt_col3' ) ){ 
function open_shop_top_header_conetnt_col3($content,$mobileopen){ ?>
<?php if($content=='text'){?>
<div class='content-html'>
  <?php echo esc_html(get_theme_mod('open_shop_col3_texthtml',  __( 'Add your content here', 'open-shop' )));?>
</div>
<?php }elseif($content=='menu'){
  if ( has_nav_menu('open-shop-above-menu' ) ){?>
<!-- Menu Toggle btn-->
        <nav> 
        <div class="menu-toggle">
            <button type="button" class="menu-btn" id="menu-btn-abv">
                <div class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-align-justify w-5 h-5 text-gray-500 group-hover:text-gray-700" aria-hidden="true"><path d="M3 5h18"></path><path d="M3 12h18"></path><path d="M3 19h18"></path></svg>
                </div>
            </button>
        </div>
        <div class="sider above open-shop-menu-hide <?php echo esc_attr($mobileopen);?>">
        <div class="sider-inner">
        <?php open_shop_abv_nav_menu();?>
        </div>
      </div>
    </nav>
<?php 
 }else{?>
<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Assign Above header menu', 'open-shop' );?></a>
 <?php }
}
elseif($content=='widget'){?>
  <div class="content-widget">
    <?php if( is_active_sidebar('top-header-widget-col2' ) ){
    dynamic_sidebar('top-header-widget-col2' );
     }else{?>
      <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Add Widget', 'open-shop' );?></a>
     <?php }?>
     </div>
<?php }elseif($content=='social'){?>
<div class="content-social">
<?php echo open_shop_social_links();?>
</div>
<?php }elseif($content=='none'){
return true;
}?>
<?php }
}
/**************************************/
//Below Header function
/**************************************/
if ( ! function_exists( 'open_shop_below_header_markup' ) ){	
function open_shop_below_header_markup(){ 
$open_shop_menu_alignment = get_theme_mod('open_shop_menu_alignment','center');
$open_shop_menu_open = get_theme_mod('open_shop_mobile_menu_open','left');
$categoryText = get_theme_mod('open_shop_main_hdr_cat_txt','Category');
?> 
<div class="below-header  mhdrdefault  <?php echo esc_attr($open_shop_menu_alignment);?>">
			<div class="container">
				<div class="below-header-bar thnk-col-3">
					<div class="below-header-col1">
						<div class="menu-category-list">
						  <div class="toggle-cat-wrap">
						  	  <div class="cat-toggle">
                    <span class="cat-icon"> 
                      <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle;">
  <path d="M5 7h14"></path>
  <path d="M5 12h9"></path>
  <path d="M5 17h11"></path>
</svg>
                     </span>
						  	  	<span class="toggle-title"><?php echo esc_html($categoryText); ?></span>
						  	  	<span class="toggle-icon"></span>
						  	  </div>
						  </div>
						  <?php open_shop_product_list_categories(); ?>
					   </div><!-- menu-category-list -->
				   </div>
           <div class="below-header-col2">
             <?php  
             open_shop_th_advance_product_search();
            ?>
         </div>
           <div class="below-header-col3">
            <div class="thunk-icon">
             <?php open_shop_header_icon(); ?>  
                <?php if(class_exists( 'WooCommerce' )){ 
                        if(get_theme_mod('open_shop_cart_mobile_disable')==true){

                        if (wp_is_mobile()!== true):
                          
                      ?>
                     
                      
                        <?php open_shop_th_cart(); ?> 

                  
                     
                      <?php  endif; }
                      elseif(get_theme_mod('open_shop_cart_mobile_disable')==false){?>


                         
                        <?php open_shop_th_cart(); ?> 

                            
                          
                     <?php  } } ?>  
                  </div>      
            </div>
				</div> <!-- end main-header-bar -->
			</div>
		</div> <!-- end below-header -->
<?php	}
}
add_action( 'open_shop_below_header', 'open_shop_below_header_markup' );
/**************************************/
//Main Header function
/**************************************/
if ( ! function_exists( 'open_shop_main_header_markup' ) ){	
function open_shop_main_header_markup(){ 

$main_header_opt = get_theme_mod('open_shop_main_header_option','none');
$open_shop_menu_alignment = get_theme_mod('open_shop_menu_alignment','center');

$open_shop_menu_open = get_theme_mod('open_shop_mobile_menu_open','left');
?>
<div class="main-header mhdrdefault <?php echo esc_attr($main_header_opt);?> <?php echo esc_attr($open_shop_menu_alignment);?>">
			<div class="container">
				<div class="main-header-bar thnk-col-3">
					<div class="main-header-col1">
          <span class="logo-content">
            <?php open_shop_logo();?> 
          </span>
     
        </div>
					<div class="main-header-col2">
      
        <nav>
        <!-- Menu Toggle btn-->
        <div class="menu-toggle">
            <button type="button" class="menu-btn" id="menu-btn">
                <div class="btn">
                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-align-justify w-5 h-5 text-gray-500 group-hover:text-gray-700" aria-hidden="true"><path d="M3 5h18"></path><path d="M3 12h18"></path><path d="M3 19h18"></path></svg>
               </div>
            </button>
        </div>
        <div class="sider main  open-shop-menu-hide <?php echo esc_attr($open_shop_menu_open);?>">
        <div class="sider-inner">
          <?php if(has_nav_menu('open-shop-main-menu' )){ 

             if (wp_is_mobile()!== false){
                   if(has_nav_menu('open-shop-above-menu' )){
                                open_shop_abv_nav_menu();
                       }
                  }  
                    open_shop_main_nav_menu();
              }else{
                 wp_page_menu(array( 
                 'items_wrap'  => '<ul class="open-shop-menu" data-menu-style="horizontal">%3$s</ul>',
                 'link_before' => '<span>',
                 'link_after'  => '</span>'));
             }?>
        </div>
        </div>
        </nav>
 
      </div> 
<?php if($main_header_opt!=='none'):?>
					<div class="main-header-col3">
             <?php open_shop_main_header_optn();?>
          </div>
<?php endif; ?>
				</div> <!-- end main-header-bar -->
			</div>
		</div> 
<?php	}
}
add_action( 'open_shop_main_header', 'open_shop_main_header_markup' );

function open_shop_main_header_optn(){
          $open_shop_main_header_option = get_theme_mod('open_shop_main_header_option','none');
          if($open_shop_main_header_option =='button'){?>
          <a href="<?php echo esc_url(get_theme_mod('open_shop_main_hdr_btn_lnk','#')); ?>" class="btn-main-header"><?php echo esc_html(get_theme_mod('open_shop_main_hdr_btn_txt', __('Button Text','open-shop'))); ?></a>
          <?php }
          elseif($open_shop_main_header_option =='callto'){ ?>
          <div class="header-support-wrap">
              <div class="header-support-icon">
                <i class="fa fa-phone" aria-hidden="true"></i>
              </div>
              <div class="header-support-content">
                <span class="sprt-tel"><b><?php echo esc_html(get_theme_mod('open_shop_main_hdr_calto_txt',__('Call To','open-shop'))); ?></b> <a href="tel:<?php echo esc_attr(get_theme_mod('open_shop_main_hdr_calto_nub',__('+1800090098','open-shop'))); ?>"><?php echo esc_html(get_theme_mod('open_shop_main_hdr_calto_nub',__('+1800090098','open-shop'))); ?></a></span>
                <span class="sprt-eml"><?php _e('Email :','open-shop');?> <a href="mailto:<?php echo esc_attr(get_theme_mod('open_shop_main_hdr_calto_email',__('Info@gmail.com','open-shop'))); ?>"><?php echo esc_html(get_theme_mod('open_shop_main_hdr_calto_email',__('Info@gmail.com','open-shop'))); ?></a></span>
              </div>
          </div>
          <?php }elseif($open_shop_main_header_option =='widget'){?>
               <div class="header-widget-wrap">
                 <?php
                  if ( is_active_sidebar('main-header-widget') ){
                       dynamic_sidebar('main-header-widget');
                   }
                  ?>
               </div>
         <?php  }
}
/**************************************/
//logo & site title function
/**************************************/
if ( ! function_exists( 'open_shop_logo' ) ){
function open_shop_logo(){
$title_disable          = get_theme_mod( 'title_disable','enable');
$tagline_disable        = get_theme_mod( 'tagline_disable','');
$description            = get_bloginfo( 'description', 'display' );
open_shop_custom_logo(); 
if($title_disable!='' || $tagline_disable!=''){
if($title_disable!=''){ 
?>
<div class="site-title"><span>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
</span>
</div>
<?php
}
if($tagline_disable!=''){
if( $description || is_customize_preview() ):?>
<div class="site-description">
   <p><?php echo esc_html($description); ?></p>
</div>
<?php endif;
      }
    } 
  }
}
/**********************************/
// header icon function
/**********************************/
function open_shop_header_icon(){
$whs_icon = get_theme_mod('open_shop_whislist_mobile_disable',false);
$acc_icon = get_theme_mod('open_shop_account_mobile_disable',false);
?>
<div class="header-icon">
     <?php 
     //Yith wishlist Icon
    if( class_exists( 'THWL_Wishlist' ) || class_exists( 'YITH_WCWL' )){
      if($whs_icon == true){ 
       if (wp_is_mobile()!== true):
        ?>
      <a class="whishlist" href="<?php echo esc_url( open_shop_whishlist_url() ); ?>" title="Wishlist"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-6 h-6 stroke-[1.5px]" aria-hidden="true"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path></svg>
        <span class="th-whishlist-text tooltip"><?php _e('Wishlist','open-shop');?></span>
      </a>
      
     <?php endif; }
     elseif($whs_icon == false){?>
        <a class="whishlist" href="<?php echo esc_url( open_shop_whishlist_url() ); ?>"  title="Wishlist"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-6 h-6 stroke-[1.5px]" aria-hidden="true"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path></svg>
        <span class="th-whishlist-text tooltip"><?php _e('Wishlist','open-shop');?></span>
      </a>
    <?php  } }


     if($acc_icon == true){
       if (wp_is_mobile()!== true):
        open_shop_account();
      endif;
       }elseif($acc_icon == false){
          open_shop_account();
       } ?>
</div>
<?php } 

/**************************/
//PRELOADER
/**************************/
if( ! function_exists( 'open_shop_preloader' ) ){
 function open_shop_preloader(){
 if (( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) ||
                isset( $_REQUEST['elementor-preview'] )){
      return;
 }else{    
    $open_shop_preloader_enable = get_theme_mod('open_shop_preloader_enable',false);
    $open_shop_preloader_image_upload = get_theme_mod('open_shop_preloader_image_upload',OPEN_SHOP_PRELOADER);
    if($open_shop_preloader_enable==true){ ?>
    <div class="open_shop_overlayloader">
    <div class="open-shop-pre-loader"><img src="<?php echo esc_url($open_shop_preloader_image_upload);?>"></div>
    </div> 
   <?php }
   }
 }

}
add_action('open_shop_site_preloader','open_shop_preloader');

 /**********************/
 // Sticky Header
 /**********************/
 if( ! function_exists( 'open_shop_sticky_header_markup' )){
 function open_shop_sticky_header_markup(){ 
 $open_shop_menu_open = get_theme_mod('open_shop_mobile_menu_open','left'); ?>
<div class="sticky-header">
   <div class="container">
        <div class="sticky-header-bar thnk-col-3">
           <div class="sticky-header-col1">
               <span class="logo-content">
                  <?php open_shop_logo();?> 
              </span>
           </div>
           <div class="sticky-header-col2">
             <nav>
        <!-- Menu Toggle btn-->
        <div class="menu-toggle">
            <button type="button" class="menu-btn" id="menu-btn-stk">
                <div class="btn">
                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-text-align-justify w-5 h-5 text-gray-500 group-hover:text-gray-700" aria-hidden="true"><path d="M3 5h18"></path><path d="M3 12h18"></path><path d="M3 19h18"></path></svg>
               </div>
            </button>
        </div>
        <div class="sider main  open-shop-menu-hide  <?php echo esc_attr($open_shop_menu_open); ?>">
        <div class="sider-inner">
          <?php if(has_nav_menu('open-shop-sticky-menu' )){ 
             if (wp_is_mobile()!== false){
                    if(has_nav_menu('open-shop-above-menu')){
                      open_shop_abv_nav_menu();
                    }     
                  }  
                open_shop_stick_nav_menu();
              }else{
                 wp_page_menu(array( 
                 'items_wrap'  => '<ul class="open-shop-menu" data-menu-style="horizontal">%3$s</ul>',
                 'link_before' => '<span>',
                 'link_after'  => '</span>'));
             }?>
        </div>
        </div>
        </nav>
           </div>
            <div class="sticky-header-col3">
              <div class="thunk-icon">
        
                <div class="header-icon">
                  <a class="prd-search-icon"><?php  if ( shortcode_exists('tapsp') ){

          echo do_shortcode('[tapsp layout="icon_style"]'); 

        }elseif( shortcode_exists('th-aps') ){

              echo do_shortcode('[th-aps layout="icon_style"]'); 
              
        }?></a>        
                     <?php 
                    if( class_exists( 'THWL_Wishlist' ) || class_exists( 'YITH_WCWL' )){
                      ?>
                      <a class="whishlist" href="<?php echo esc_url(open_shop_whishlist_url() ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-6 h-6 stroke-[1.5px]" aria-hidden="true"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path></svg></a>
                     <?php } 
                        open_shop_account();
                       ?>
                </div>
             <?php if(class_exists( 'WooCommerce' )){ ?>
                      <div class="cart-icon" > 

                        <?php open_shop_th_cart(); ?> 

                         
                       </div>
                      <?php  } ?> 
                  </div>
           </div>
        </div>

   </div>
</div>
      <div class="search-wrapper">
                     <div class="container">
                      <div class="search-close"><a class="search-close-btn"></a></div>
                     <?php 
                open_shop_th_advance_product_search();?>
                       </div>
       </div> 
 <?php }
}
if(get_theme_mod('open_shop_sticky_header',false)==true):
add_action('open_shop_sticky_header','open_shop_sticky_header_markup');
endif;

//********************************//
//th advance product search 
//*******************************//
function open_shop_th_advance_product_search(){

              if ( shortcode_exists('th-aps') ){

                echo do_shortcode('[th-aps]');

              }elseif ( shortcode_exists('tapsp') ){

                echo do_shortcode('[tapsp]');

              }elseif ( !shortcode_exists('th-aps') && !shortcode_exists('tapsp') && is_user_logged_in()) {

                $url = admin_url('themes.php?page=thunk_started&th-tab=recommended-plugin');

                ?>

                <a target="_blank" class="plugin-active-msg" href="<?php echo esc_url($url);?>">

                  <?php _e('Please Install "th advance product search" Plugin','open-shop');?>
                  
                </a>


                <?php      

            }
}

//********************************//
//th woo cart 
//*******************************//

function open_shop_th_cart(){

  if ( shortcode_exists('taiowc') ){ ?>

             <div class="cart-icon">

             <?php echo do_shortcode('[taiowc]');?>

             </div>  

             <?php  }elseif ( shortcode_exists('taiowcp') ){ ?>

              <div class="cart-icon">

             <?php echo do_shortcode('[taiowcp]');?>

              </div>  

             <?php  } elseif ( !shortcode_exists('taiowc') && !shortcode_exists('taiowcp') && is_user_logged_in()) {

                $url = admin_url('themes.php?page=thunk_started&th-tab=recommended-plugin');

                ?>

                <a target="_blank" class="cart-plugin-active-msg" href="<?php echo esc_url($url);?>">

                  <?php _e('Add Cart','open-shop');?>
                  
                </a>


                <?php      

            }
}