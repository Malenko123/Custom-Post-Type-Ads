<?php
function register_ads() {

	$labels = array(
		'name'               => 'Ads',
		'singular_name'      => 'AD',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New AD',
		'edit_item'          => 'Edit AD',
		'new_item'           => 'New AD',
		'all_items'          => 'All ADs',
		'view_item'          => 'View AD',
		'search_items'       => 'Search ADss',
		'not_found'          =>  'No ADs found',
		'not_found_in_trash' => 'No ADs found in Trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'ADs'
	);
 
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'ads' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => 5,
		'menu_icon'			 =>'dashicons-media-spreadsheet',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'ADs', $args );
}
add_action( 'init', 'register_ads' );

/**
 * Taxonomy: Locations for ADs
 */
function ads_categories() {

	//Register locations taxonomy
	$labels_locations = [
		"name" => __( "Locations" ),
		"singular_name" => __( "Locations" ),
	];

	$args_locations = [
		"label" => __( "Locations" ),
		"labels" => $labels_locations,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'locations', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "ads_locations",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
	];
	register_taxonomy( "ads_locations", [ "ads" ], $args_locations );
}
add_action( 'init', 'ads_categories' );


/* Prices */
//create a custom meta box where you can input the prices
add_action( 'admin_init', 'my_admin_ads' );
function my_admin_ads() {
    add_meta_box( 'ads_meta_box', 'Prices', 'display_ads_meta_box','ads', 'normal', 'high' );
}
function display_ads_meta_box( $ads ) {
    ?>
    <h4>General Details</h4>
    <table width="100%">
        <tr>
            <td>Price ($)</td>
            <td><input type="text" style="width:425px;" name="meta[price]" placeholder="$" value="<?php echo esc_html( get_post_meta( $ads->ID, 'price', true ) );?>" />
            </td>
        </tr>
    </table>
<?php 
}
add_action( 'save_post', 'add_ads_fields', 10, 2 );
function add_ads_fields( $ads_id, $ads ) {
    if ( $ads->post_type == 'ads' ) {
        if ( isset( $_POST['meta'] ) ) {
            foreach( $_POST['meta'] as $key => $value ){
                update_post_meta( $ads_id, $key, $value );
            }
        }
    }
}
?>