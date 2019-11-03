<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bok-ecommerce
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php 
	dynamic_sidebar( 'sidebar-1' ); 
	
	//If page is shop, single product or product category call
	//the sale_items func. that displays all products on sales 
	//in a drop down box.
	if(is_shop() || is_product() || is_product_category())
	{
		sale_items();	
	}?>
	
</aside><!-- #secondary -->
