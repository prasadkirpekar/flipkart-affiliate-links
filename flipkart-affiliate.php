<?php
/*
Plugin Name: Flipkart Affiliate Links
Author: Prasad Kirpekar
Description: Display Prices of products from flipkart. Easy to include Flipkart Affiliate Links
Version: 1.0.0
Author URI: mailto:prasadkirpekar@outlook.com

Note: You can not resell this plugin.
*/

add_shortcode('flipkart_aff','fa_test_build');
add_action( 'wp_ajax_nopriv_fk_aff_data', 'fk_aff_data' );
add_action( 'wp_ajax_fk_aff_data', 'fk_aff_data' );
function fk_aff_data() {
  	$id =$_POST['id'];
	$ch=curl_init();
	$headers=$arrayName = array('Fk-Affiliate-Id:amitgoenk','Fk-Affiliate-Token:f27f6b4f3beb47d486c81e8d06f9139c');
	curl_setopt($ch, CURLOPT_URL, "https://affiliate-api.flipkart.net/affiliate/1.0/product.json?id=".$id);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$o=curl_exec($ch);
	$price= json_decode($o)->productBaseInfoV1->flipkartSellingPrice->amount;
	$inStock = json_decode($o)->productBaseInfoV1->inStock;
	echo json_encode(array('price'=>$price,'instock'=>$inStock));
  	die();

}
function flipkart_aff_js(){
    wp_enqueue_script('fk_js','/wp-content/plugins/flipkart-affiliate/flipkart-affiliate.js',array('jquery'),8.0);  
}
add_action('wp_enqueue_scripts', 'flipkart_aff_js');
function fa_test_build($atts){
	$atts = shortcode_atts( array(
        'link' => 'Invalid Link'
    ), $atts );

	$query = parse_url($atts['link'], PHP_URL_QUERY);
	parse_str($query, $params);
	$id = $params['pid'];
  	$gif_url="../wp-content/plugins/flipkart-affiliate/assets/flipkart-affiliate.gif";
	$html='<p id="main_'.$id.'" style="text-align: center;"><a href="{link}" target="_blank" rel="nofollow noopener noreferrer"><span style="text-	decoration: underline;"><strong>Rs <span class="fk_af" id="'.$id.'"><img src="'.$gif_url.'"></span></strong></span>&nbsp;&nbsp;<img class="alignnone size-full wp-image-2403" src="../wp-content/plugins/flipkart-affiliate/assets/buy-from-flipkart.png" alt="Buy from Flipkart" width="118" height="40"></a></p>';
	$html=str_replace("{link}", $atts['link'], $html);
	return $html;	
}
?>
