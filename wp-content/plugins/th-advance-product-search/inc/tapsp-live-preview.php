<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="setting-preview-wrap style-wrapper">

    <div class="tapsp-preview-head">
        <h2 class="tapsp-preview-header">
            <?php esc_html_e( 'Live Search Preview', 'th-advance-product-search-pro' ); ?>
        </h2>
    </div>

    <!-- TABS -->
    <div class="tapsp-preview-tabs">
        <ul>
            <li class="active" data-target="#preview-style-1" data-style="th-normal"><?php esc_html_e( 'Default', 'th-advance-product-search-pro' ); ?></li>
            <li data-target="#preview-style-2" data-style="th-traditional"><?php esc_html_e( 'Traditional', 'th-advance-product-search-pro' ); ?></li>
            <li data-target="#preview-style-3" data-style="th-modern"><?php esc_html_e( 'Mordern', 'th-advance-product-search-pro' ); ?></li>
        </ul>
    </div>
    <!-- ==============================
          STYLE 1 (Your existing preview)
    =============================== -->
    <div id="preview-style-1" class="tapsp-preview-style active">

       <div id='tapsp-search-box' class="tapsp-search-box ">     
                <div class="th-preview-styletab">
                    <div class="tapsp-searchwrapper">
                    <div class="tapsp-first tapsp-from-wrap" data-th-bg="bar_bg_clr-field" data-th-border="bar_brdr_clr-field"> 
                <input id="tapsp-search-autocomplete-1" name="s" placeholder="Sample" class="tapsp-search-autocomplete tapsp-form-control" value="" type="text" title="Search" autocomplete="off" data-th-color="bar_text_clr-field">
                <button id="tapsp-search-button" aria-label="<?php echo esc_attr( 'Submit', 'th-advance-product-search-pro' ); ?>" value="Submit" type="submit" data-th-bg="bar_button_bg_clr-field" data-th-color="bar_button_txt_clr-field" data-th-bg-hover="bar_button_hvr_clr-field" data-th-color-hover="bar_button_txt_hvr_clr-field">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4" aria-hidden="true"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg>
                </button>
                    </div>
                
                    <div class="tapsp-autocomplete-suggestions" data-th-bg="sus_bg_clr-field" data-th-border="sus_brdr_clr-field">
                    
                         <div class="tapsp-suggestion-wrap  tapsp-suggestion-heading">
                         <a href="#" class="tapsp-autocomplete-suggestion " data-index="0">
                            <div class="tapsp-content-wrapp"><div class="tapsp-content-left">
                            <div class="tapsp-title" data-th-color="sus_grphd_clr-field"><?php esc_html_e( 'Category', 'th-advance-product-search-pro' ); ?></div></div></div></a>  
                         </div>
                         <div class="tapsp-suggestion-wrap  tapsp-suggestion-taxonomy-product-cat"><a href="#" class="tapsp-autocomplete-suggestion" data-index="1"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title" ><strong data-th-color="sus_hglt_clr-field"><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'category', 'th-advance-product-search-pro' ); ?></div></div></div></a>
                        </div>
                        
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-heading"><a href="#" class="tapsp-autocomplete-suggestion " data-index="2"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><?php esc_html_e( 'Product', 'th-advance-product-search-pro' ); ?></div></div></div></a>
                        </div>
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product"  data-th-bg-hover="sus_slect_clr-field"><a href="#" class="tapsp-autocomplete-suggestion" data-index="3"><span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title" data-th-color="sus_title_clr-field" data-th-color="sus_title_clr-field"><strong data-th-color="sus_hglt_clr-field"><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?></div><span class="tapsp-sku" data-th-toggle="tapsp_enable_product_sku-field"><?php esc_html_e( '( SKU : Product 01 )', 'th-advance-product-search-pro' ); ?></span><span class="tapsp-desc" data-th-toggle="tapsp_enable_product_desc-field" data-th-color="sus_text_clr-field"><?php esc_html_e( 'This is a simple product.', 'th-advance-product-search-pro' ); ?></span></div><div class="tapsp-content-right"><span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span></div></div></a></div>
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product"><a href="#" class="tapsp-autocomplete-suggestion" data-index="3"><span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title" data-th-color="sus_title_clr-field"><strong data-th-color="sus_hglt_clr-field"><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?></div><span class="tapsp-sku" data-th-toggle="tapsp_enable_product_sku-field"><?php esc_html_e( '( SKU : Product 01 )', 'th-advance-product-search-pro' ); ?></span><span class="tapsp-desc" data-th-toggle="tapsp_enable_product_desc-field" data-th-color="sus_text_clr-field"><?php esc_html_e( 'This is a simple product.', 'th-advance-product-search-pro' ); ?></span></div><div class="tapsp-content-right"><span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span></div></div></a></div>
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product"><a href="#" class="tapsp-autocomplete-suggestion" data-index="3"><span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title" data-th-color="sus_title_clr-field"><strong data-th-color="sus_hglt_clr-field"><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?></div><span class="tapsp-sku" data-th-toggle="tapsp_enable_product_sku-field"><?php esc_html_e( '( SKU : Product 01 )', 'th-advance-product-search-pro' ); ?></span><span class="tapsp-desc" data-th-toggle="tapsp_enable_product_desc-field" data-th-color="sus_text_clr-field"><?php esc_html_e( 'This is a simple product.', 'th-advance-product-search-pro' ); ?></span></div><div class="tapsp-content-right"><span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span></div></div></a></div>
                        <div class="tapsp-suggestion-wrap   tapsp-suggestion-more"><a href="#" class="tapsp-autocomplete-suggestion tapsp-autocomplete-selected" data-index="8"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><?php esc_html_e( 'See All Results  (6)', 'th-advance-product-search-pro' ); ?></div></div></div></a></div>
                    </div>
                    </div>
                    
                </div>
                        
                </div>
                
    </div>


    <!-- ==============================
          STYLE 2 (Different Layout)
    =============================== -->
    <div id="preview-style-2" class="tapsp-preview-style" style="display:none;">
        <div id='tapsp-search-box' class="tapsp-search-box ">     
                <div class="th-preview-styletab">
                    <div class="tapsp-first tapsp-from-wrap"> 
                <input id="tapsp-search-autocomplete-1" name="s" placeholder="Sample" class="tapsp-search-autocomplete tapsp-form-control" value="" type="text" title="Search" autocomplete="off">
                <button id="tapsp-search-button" aria-label="<?php echo esc_attr( 'Submit', 'th-advance-product-search-pro' ); ?>" value="Submit" type="submit">
                     <span class="th-icon th-icon-vector-search icon-style" style="color:#fff"></span>
                </button>
            </div>
                    <div class="tapsp-autocomplete-suggestions th-traditional">
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-heading">
                        <a href="#" class="tapsp-autocomplete-suggestion" data-index="0"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title">Trending Now</div></div></div></a>
                    </div>

                    
                        
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-heading"><a href="#" class="tapsp-autocomplete-suggestion " data-index="2"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><?php esc_html_e( 'Product', 'th-advance-product-search-pro' ); ?></div></div></div></a>
                        </div>
                        <div class="tapsp-products-wrapper">
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product"><a href="#" class="tapsp-autocomplete-suggestion" data-index="3"><span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><strong><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?></div><span class="tapsp-sku" data-th-toggle="tapsp_enable_product_sku-field"><?php esc_html_e( '( SKU : Product 01 )', 'th-advance-product-search-pro' ); ?></span><span class="tapsp-desc" data-th-toggle="tapsp_enable_product_desc-field"><?php esc_html_e( 'This is a simple product.', 'th-advance-product-search-pro' ); ?></span></div><div class="tapsp-content-right"><span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span></div></div></a></div>
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product"><a href="#" class="tapsp-autocomplete-suggestion" data-index="3"><span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><strong><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?></div><span class="tapsp-sku" data-th-toggle="tapsp_enable_product_sku-field"><?php esc_html_e( '( SKU : Product 01 )', 'th-advance-product-search-pro' ); ?></span><span class="tapsp-desc" data-th-toggle="tapsp_enable_product_desc-field"><?php esc_html_e( 'This is a simple product.', 'th-advance-product-search-pro' ); ?></span></div><div class="tapsp-content-right"><span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span></div></div></a></div>
                        </div>
                        <div class="tapsp-suggestion-wrap   tapsp-suggestion-more"><a href="#" class="tapsp-autocomplete-suggestion tapsp-autocomplete-selected" data-index="8"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><?php esc_html_e( 'See All Results  (6)', 'th-advance-product-search-pro' ); ?></div></div></div></a></div>
                    </div>
                </div>
                        
                </div>
    </div>


    <!-- ==============================
          STYLE 3 (Another Layout)
    =============================== -->
    <div id="preview-style-3" class="tapsp-preview-style" style="display:none;">
     <div id='tapsp-search-box' class="tapsp-search-box ">     
                <div class="th-preview-styletab">
                    <div class="tapsp-first tapsp-from-wrap"> 
                <input id="tapsp-search-autocomplete-1" name="s" placeholder="Sample" class="tapsp-search-autocomplete tapsp-form-control" value="" type="text" title="Search" autocomplete="off">
                <button id="tapsp-search-button" aria-label="<?php echo esc_attr( 'Submit', 'th-advance-product-search-pro' ); ?>" value="Submit" type="submit">
                     <span class="th-icon th-icon-vector-search icon-style" style="color:#fff"></span>
                </button>
            </div>
                    <div class="tapsp-autocomplete-suggestions th-modern">
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-heading">
                        <a href="#" class="tapsp-autocomplete-suggestion" data-index="0"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title">Trending Now</div></div></div></a>
                    </div>

                    <div class="tapsp-trending-wrapper">
                        <div class="tapsp-suggestion-wrap ">
                            <a href="http://localhost:8888/wp1/?s=Vintage+dress&amp;post_type=product" class="tapsp-autocomplete-suggestion" data-index="1">
                                <div class="tapsp-content-wrapp">
                                    <div class="tapsp-content-left">
                                        <div class="tapsp-title">Trend1</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="tapsp-suggestion-wrap ">
                            <a href="http://localhost:8888/wp1/?s=Black+dress&amp;post_type=product" class="tapsp-autocomplete-suggestion" data-index="2">
                                <div class="tapsp-content-wrapp">
                                    <div class="tapsp-content-left">
                                    <div class="tapsp-title">Trend2</div>
                                </div>
                            </div>
                        </a>
                    </div>
                   </div>
                        
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-heading">
                            <a href="#" class="tapsp-autocomplete-suggestion " data-index="2">
                                <div class="tapsp-content-wrapp">
                                    <div class="tapsp-content-left">
                                        <div class="tapsp-title">
                                            <?php esc_html_e( 'Product', 'th-advance-product-search-pro' ); ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="tapsp-products-wrapper">
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product">
                            <a href="#" class="tapsp-autocomplete-suggestion" data-index="3">
                                <span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span>
                                <div class="tapsp-content-wrapp">
                                    <div class="tapsp-content-left">
                                        <div class="tapsp-title">
                                            <strong><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> 
                                            <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?>
                                        </div>
                                        
                                           
                                    </div>
                                    <div class="tapsp-content-right"> 
                                            <span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span>
                                            <?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi>
                                                <span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span>
                                    </div>
                                </div>
                                    </a>
                                </div>
                        <div class="tapsp-suggestion-wrap  tapsp-suggestion-product"><a href="#" class="tapsp-autocomplete-suggestion" data-index="3"><span class="tapsp-img" data-th-toggle="tapsp_enable_product_image-field"><img src="<?php echo esc_url(TH_ADVANCE_PRODUCT_SEARCH_PLUGIN_URI.'/images/tapsp-placeholder.png');?>" alt="Beanie with Logo"></span><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><strong><?php esc_html_e( 'sample', 'th-advance-product-search-pro' ); ?></strong> <?php esc_html_e( 'product', 'th-advance-product-search-pro' ); ?></div></div><div class="tapsp-content-right"><span class="tapsp-price" data-th-toggle="tapsp_enable_product_price-field"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '20.00', 'th-advance-product-search-pro' ); ?></bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'th-advance-product-search-pro' ); ?></span><?php esc_html_e( '18.00', 'th-advance-product-search-pro' ); ?></bdi></span></ins></span></div></div></a></div>
                        </div>
                        <div class="tapsp-suggestion-wrap   tapsp-suggestion-more"><a href="#" class="tapsp-autocomplete-suggestion tapsp-autocomplete-selected" data-index="8"><div class="tapsp-content-wrapp"><div class="tapsp-content-left"><div class="tapsp-title"><?php esc_html_e( 'See All Results  (6)', 'th-advance-product-search-pro' ); ?></div></div></div></a></div>
                    </div>
                </div>
                        
                </div>
    </div>

</div>