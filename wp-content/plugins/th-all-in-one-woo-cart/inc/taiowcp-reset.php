<?php if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- preview wrapper -->
			<div class="setting-preview-wrap reset-wrapper" data-tab="taiowc_reset">
				    <div class="page-wrapper">
                    <div class="reset-tab">

                        <div class="svg-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                        </div>
                        <h2><?php esc_html_e('Reset All Settings?','th-all-in-one-woo-cart'); ?></h2>
                        <p><?php esc_html_e('This action will restore all configuration settings to their default factory values. Your 
                        current custom styles, colors, and behavior logic will be permanently deleted.','th-all-in-one-woo-cart'); ?></p>

                        <p class="submit taiowc-button-wrapper th-save-btn">
                        
                        <a onclick="return confirm('<?php esc_attr_e( 'Are you sure to reset current settings?', 'th-all-in-one-woo-cart' ) ?>')" class="reset" href="<?php echo esc_url($this->reset_url()); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw" aria-hidden="true"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path><path d="M21 3v5h-5"></path><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path><path d="M8 16H3v5"></path></svg><?php esc_html_e( 'Reset Settings', 'th-all-in-one-woo-cart' ); ?>
                        </a>
                    </p>
                    </div>

                    </div>
				
			</div>
<!-- preview wrapper -->  