<?php
/*
	Plugin Name: Custom Post Type - ADs
	Plugin URI: 
	Description: 
	Author: Kliment Malenko
	Version: 1.0
	Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


register_activation_hook( __FILE__, 'ad_activate' );
function ad_activate() {
    register_ads();
    ads_categories();
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'ad_deactivate' );
function ad_deactivate() {
    delete_option('rewrite_rules');
}

register_uninstall_hook( __FILE__, 'ad_uninstall' );
function ad_uninstall() {
    delete_option('ads_settings'); 
}


include( plugin_dir_path( __FILE__ ) . 'settings-page.php');
include( plugin_dir_path( __FILE__ ) . 'register-cpt.php');


// register jquery and style on initialization
function register_script() {
    wp_register_script( 'filterjs', plugins_url('assets/js/filter.js', __FILE__), array('jquery'), '2.5.1' );
    wp_register_script( 'price-slider', plugins_url('assets/js/price-slider.js', __FILE__), array('jquery'), '2.5.1' );
    wp_register_style( 'ads_style', plugins_url('assets/css/style-ads.css', __FILE__), false, '1.0.0', 'all');
    wp_register_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array(), '4.2.0' );
}
add_action('init', 'register_script');


// use the registered jquery and style above
function enqueue_style(){
   wp_enqueue_script('filterjs');
   wp_enqueue_script('price-slider');
   wp_enqueue_style( 'ads_style' );
   wp_enqueue_style( 'fontawesome' );
}
add_action('wp_enqueue_scripts', 'enqueue_style');



//Create templates for ADs
function ads_templates( $template ) {
    $post_types = array( 'ads' );

    if ( is_post_type_archive( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'templates/ads.php' ) ){
        $template = plugin_dir_path(__FILE__) . 'templates/ads.php';
    }

    if ( is_singular( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'templates/single-ads.php' ) ){
        $template = plugin_dir_path(__FILE__) . 'templates/single-ads.php';
    }

    return $template;
}
add_filter( 'template_include', 'ads_templates' );



//create the filter function
function ad_filter_function(){

	$locations = $_POST['locationfilter']; //get the select

	$args = array(
		'post_type' => 'ads',
		'posts_per_page'   => -1,
		'orderby' => 'publish_date',
		'order' => 'DESC'
	);


	if( isset($locations) && !empty($locations) ){
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'ads_locations',
				'field' => 'id',
				'terms' => $locations
			)
		);
	} 
	
 	// create $args['meta_query'] array if one of the following fields is filled
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] ){
		$args['meta_query'] = array( 'relation'=>'AND' ); // AND means that all conditions of meta_query should be true
	}
 
	// if both minimum price and maximum price are specified we will use BETWEEN comparison
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] && isset( $_POST['price_max'] ) && $_POST['price_max'] ) {
		$args['meta_query'][] = array(
			'key' => 'price',
			'value' => array( $_POST['price_min'], $_POST['price_max'] ),
			'type' => 'numeric',
			'compare' => 'between'
		);
	} else {
		// if only min price is set
		if( isset( $_POST['price_min'] ) && $_POST['price_min'] ){
			$args['meta_query'][] = array(
				'key' => 'price',
				'value' => $_POST['price_min'],
				'type' => 'numeric',
				'compare' => '>'
			);
		}
		// if only max price is set
		if( isset( $_POST['price_max'] ) && $_POST['price_max'] ){
			$args['meta_query'][] = array(
				'key' => 'price',
				'value' => $_POST['price_max'],
				'type' => 'numeric',
				'compare' => '<'
			);
		}
			
	}

 
	$ads_query = new WP_Query( $args );
 
	if( $ads_query->have_posts() ) : ?>
		<div class="container">
			<div class="ad-row row">
			    <?php 
			      while ( $ads_query->have_posts() ) : $ads_query->the_post(); ?>

			      	<?php $price = get_post_meta( get_the_ID(), 'price', true ); ?>

			      	<div class="col ads-col">
			            <?php 
				          $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' ); ?>

				          <?php if( $url ) : ?>
					          <div class="ads-img">
					          	  <a href="<?php the_permalink(); ?>" class="img-link"></a>
					              <img style="width:100%;" src="<?php echo $url; ?>" />
					          </div>
					      <?php endif; ?>

					      <div class="ads-body">
				      		<div class="ads-header">
					      		<p class="ads-date"><?php echo get_the_date('j. F Y') ?></p>
					      		<p class="ads-price"><strong>$<?php echo number_format($price); ?></strong></p>
					      	</div>

					      	<a href="<?php the_permalink(); ?>" class="ads-link">
					      		<h2 class="ads-title"><?php the_title(); ?> <span class="post-go-to"><i class="fas fa-arrow-right"></i></span></h2>
					      	</a>
					      </div>
				  	</div>
				<?php endwhile; ?> <!-- end while -->
			</div>
		</div>
	<?php wp_reset_postdata();

	else : ?>

		<p class="no-ads-found">Sorry, no ads found.</p>
		
	<?php endif;
 
	die();
}
add_action('wp_ajax_adsfilter', 'ad_filter_function'); 
add_action('wp_ajax_nopriv_adsfilter', 'ad_filter_function');