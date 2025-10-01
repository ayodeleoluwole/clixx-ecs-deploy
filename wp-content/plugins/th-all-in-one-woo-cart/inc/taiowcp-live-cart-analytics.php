<?php
?>
<div class="setting-preview-wrap cart-an" data-tab="<?php esc_attr_e('taiowc_cart_analyst', 'th-all-in-one-woo-cart'); ?>">
    <div class="page-wrapper">
        <div id="taiowc_cart_analys" class="taiowc-cart-track-wrapper" style="<?php esc_attr_e('display: block;', 'th-all-in-one-woo-cart'); ?>">
            <div id="taiowc-track-root">
                <div class="th-cart-track-wrap">
                    <!-- ================= TRACK HEADER ================= -->
                    <div class="th-track-card">
                        <div class="th-track-left">
                            <div class="th-track-icon">
                                <!-- SVG SAFE (no escaping needed) -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg>
                            </div>
                            <div class="th-track-text">
                                <h3><?php esc_html_e('Tracking Analytics Status', 'th-all-in-one-woo-cart'); ?></h3>
                                <p><?php esc_html_e('Global session tracking is currently active and recording. Ensure this is enabled for analytics.', 'th-all-in-one-woo-cart'); ?></p>
                            </div>
                        </div>
                        <label class="th-toggle-switch">
                            <span class="th-toggle-label off"><?php esc_html_e('Analytics DISABLED', 'th-all-in-one-woo-cart'); ?></span>
                            <input type="checkbox" <?php checked(true); ?> />
                            <span class="th-toggle-slider"></span>
                            <span class="th-toggle-label on"><?php esc_html_e('Analytics ACTIVE', 'th-all-in-one-woo-cart'); ?></span>
                        </label>
                    </div>

                    <!-- ================= ACTIVE SESSION TABLE ================= -->
                    <h2><?php esc_html_e('Active Customer Sessions', 'th-all-in-one-woo-cart'); ?></h2>
                    <table class="wp-list-table widefat fixed striped table-view-list">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Username', 'th-all-in-one-woo-cart'); ?></th>
                                <th><?php esc_html_e('Product in cart', 'th-all-in-one-woo-cart'); ?></th>
                                <th><?php esc_html_e('Last update', 'th-all-in-one-woo-cart'); ?></th>
                                <th><?php esc_html_e('Cart Action', 'th-all-in-one-woo-cart'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php esc_html_e('User -108', 'th-all-in-one-woo-cart'); ?></td>
                                <td><span class="units"><?php esc_html_e('134', 'th-all-in-one-woo-cart'); ?></span></td>
                                <td><?php esc_html_e('D:09-02-2026 T:10:44:48', 'th-all-in-one-woo-cart'); ?></td>
                                <td>
                                    <button class="th-view"><span class="dashicons dashicons-visibility"></span></button>
                                    <span class="th-seprator"></span>
                                    <button class="th-delete"></button>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Guest#1081008', 'th-all-in-one-woo-cart'); ?></td>
                                <td><span class="units"><?php esc_html_e('1', 'th-all-in-one-woo-cart'); ?></span></td>
                                <td><?php esc_html_e('D:05-02-2026 T:12:40:42', 'th-all-in-one-woo-cart'); ?></td>
                                <td>
                                    <button class="th-view"><span class="dashicons dashicons-visibility"></span></button>
                                    <span class="th-seprator"></span>
                                    <button class="th-delete"></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- ================= PRODUCT PERFORMANCE ================= -->
                    <h2><?php esc_html_e('Product Performance (Most Wanted)', 'th-all-in-one-woo-cart'); ?></h2>
                    <table class="wp-list-table taiowc-user-product th-product-user widefat fixed striped table-view-list">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Product Name', 'th-all-in-one-woo-cart'); ?></th>
                                <th><?php esc_html_e('Total User', 'th-all-in-one-woo-cart'); ?></th>
                                <th><?php esc_html_e('Status', 'th-all-in-one-woo-cart'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="th-product-title">
                                    <div class="th-image">
                                        <img src="<?php echo esc_url(TAIOWC_PLUGIN_URI.'/images/product-1.jpg'); ?>" alt="<?php esc_attr_e('Vivamus maximus purus', 'th-all-in-one-woo-cart'); ?>">
                                        <a href="#">
                                            <?php esc_html_e('Vivamus maximus purus', 'th-all-in-one-woo-cart'); ?>
                                        </a>
                                    </div>
                                </td>
                                <td><span class="units"><?php esc_html_e('1', 'th-all-in-one-woo-cart'); ?></span></td>
                                <td><button class="th-view"><span class="dashicons dashicons-visibility"></span></button></td>
                            </tr>
                            <tr>
                                <td class="th-product-title">
                                    <div class="th-image">
                                        <img src="<?php echo esc_url(TAIOWC_PLUGIN_URI.'/images/product-2.jpg'); ?>" alt="<?php esc_attr_e('Hoodie', 'th-all-in-one-woo-cart'); ?>">
                                        <a href="#">
                                            <?php esc_html_e('Hoodie', 'th-all-in-one-woo-cart'); ?>
                                        </a>
                                    </div>
                                </td>
                                <td><span class="units"><?php esc_html_e('2', 'th-all-in-one-woo-cart'); ?></span></td>
                                <td><button class="th-view"><span class="dashicons dashicons-visibility"></span></button></td>
                            </tr>

                            <tr>
                                <td class="th-product-title">
                                    <div class="th-image">
                                        <img src="<?php echo esc_url(TAIOWC_PLUGIN_URI.'/images/product-3.jpg'); ?>" alt="<?php esc_attr_e('Vivamus maximus purus', 'th-all-in-one-woo-cart'); ?>">
                                        <a href="#">
                                            <?php esc_html_e('Pellentesque habitant morb', 'th-all-in-one-woo-cart'); ?>
                                        </a>
                                    </div>
                                </td>
                                <td><span class="units"><?php esc_html_e('1', 'th-all-in-one-woo-cart'); ?></span></td>
                                <td><button class="th-view"><span class="dashicons dashicons-visibility"></span></button></td>
                            </tr>

                            <tr>
                                <td class="th-product-title">
                                    <div class="th-image">
                                        <img src="<?php echo esc_url(TAIOWC_PLUGIN_URI.'/images/product-4.jpg'); ?>" alt="<?php esc_attr_e('Vivamus maximus purus', 'th-all-in-one-woo-cart'); ?>">
                                        <a href="#">
                                            <?php esc_html_e('Phasellus elit felis vulputate', 'th-all-in-one-woo-cart'); ?>
                                        </a>
                                    </div>
                                </td>
                                <td><span class="units"><?php esc_html_e('1', 'th-all-in-one-woo-cart'); ?></span></td>
                                <td><button class="th-view"><span class="dashicons dashicons-visibility"></span></button></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- ================= EXPORT ================= -->
                    <div class="th-export-card">
                        <div class="th-export-left">
                            <h3><?php esc_html_e('Export Performance Data', 'th-all-in-one-woo-cart'); ?></h3>
                            <p><?php esc_html_e('Download current session datasets in high-fidelity formats for business intelligence and external reporting.', 'th-all-in-one-woo-cart'); ?></p>
                        </div>
                        <div class="th-export-actions">
                            <button class="th-export-btn"><?php esc_html_e('↓ CSV FORMAT', 'th-all-in-one-woo-cart'); ?></button>
                            <button class="th-export-btn secondary"><?php esc_html_e('↓ JSON DATA', 'th-all-in-one-woo-cart'); ?></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>