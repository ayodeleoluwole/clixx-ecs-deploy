<?php

if ( ! defined( 'ABSPATH' ) ) exit;


$uniqueID   = ++ taiowc()->cartInstances;

$layoutType = !empty($args['layout'])  ? $args['layout'] : '';
$fixedcart_class = '';
if ($layoutType == 'cart_fixed_1' || $layoutType == 'cart_fixed_2' ) {
	$fixedcart_class = 'taiowc-fixed-cart';
}
$cartStyle = 'taiowc-slide-right';

$taiowc_fxd_cart_position  = esc_html(taiowc()->get_option( 'taiowc-fxd_cart_position' ));

// Append position class with space
if (!empty($taiowc_fxd_cart_position)) {
    $fixedcart_class .= ' ' . $taiowc_fxd_cart_position;
}

?>

<div id="<?php echo esc_attr($uniqueID); ?>" class="taiowc-wrap  <?php echo esc_attr($cartStyle); ?> <?php echo esc_attr($fixedcart_class); ?>  <?php echo esc_attr($layoutType); ?>">
	<?php 
     taiowc_markup()->taiowc_cart_show();
	?>
</div>