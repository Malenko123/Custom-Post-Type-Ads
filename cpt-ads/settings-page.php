<?php
//add ads settings page
function ads_options_page() {
 
	add_options_page(
		'ADs Settings', // page <title>Title</title>
		'ADs Settings', // menu link text
		'manage_options', // capability to access the page
		'ads-settings-slug', // page URL slug
		'ads_page_content', // callback function with content
		2 // priority
	);
 
}
add_action( 'admin_menu', 'ads_options_page' );
 

function ads_page_content(){
 
	echo '<div class="wrap">
	<h1>ADs Settings</h1>
	<form method="post" action="options.php">';
 
		settings_fields( 'ads_settings' ); // settings group name
		do_settings_sections( 'ads-settings-slug' ); // just a page slug
		submit_button();
 
	echo '</form></div>';
 
}

//register the setting
function ads_register_setting(){
 
 	//register location label
	register_setting(
		'ads_settings', // settings group name
		'location_label', // option name
		'sanitize_text_field' // sanitization function
	);
	//register price label
	register_setting(
		'ads_settings', 
		'price_label', 
		'sanitize_text_field'
	);
	//register filter button text
	register_setting(
		'ads_settings', 
		'filter_btn_text',
		'sanitize_text_field' 
	);
	//register clear button text
	register_setting(
		'ads_settings', 
		'clear_btn_text', 
		'sanitize_text_field' 
	);
 
	add_settings_section(
		'ad_settings_section_id', // section ID
		'', // title (if needed)
		'', // callback function (if needed)
		'ads-settings-slug' // page slug
	);
 
 	//add location label
	add_settings_field(
		'location_label',
		'Location field label',
		'ads_location_field_html', // function which prints the field
		'ads-settings-slug', // page slug
		'ad_settings_section_id', // section ID
		array( 
			'label_for' => 'location_label',
			'class' => 'setting-field', // for <tr> element
		)
	);
	//add price label
	add_settings_field(
		'price_label',
		'Price field label',
		'ads_price_field_html', 
		'ads-settings-slug', 
		'ad_settings_section_id', 
		array( 
			'label_for' => 'price_label',
			'class' => 'setting-field', 
		)
	);
	//add filter button text
	add_settings_field(
		'filter_btn_text',
		'Text for the "Filter" button',
		'ads_filter_btn_html', 
		'ads-settings-slug', 
		'ad_settings_section_id',
		array( 
			'label_for' => 'filter_btn_text',
			'class' => 'setting-field',
		)
	);
	//add clear button text
	add_settings_field(
		'clear_btn_text',
		'Text for the "Reset" button',
		'ads_clear_btn_html',
		'ads-settings-slug',
		'ad_settings_section_id',
		array( 
			'label_for' => 'clear_btn_text',
			'class' => 'setting-field',
		)
	);
}
add_action( 'admin_init',  'ads_register_setting' );
 

//location field html
function ads_location_field_html(){
 
	$locationLabel = get_option( 'location_label' );
	printf(
		'<input type="text" id="location_label" name="location_label" value="%s" />',
		esc_attr( $locationLabel )
	);
}
//price field html
function ads_price_field_html(){
 
	$priceLabel = get_option( 'price_label' );
	printf(
		'<input type="text" id="price_label" name="price_label" value="%s" />',
		esc_attr( $priceLabel )
	); 
}
//filter button  html
function ads_filter_btn_html(){
 
	$filterBtnLabel = get_option( 'filter_btn_text' );
	printf(
		'<input type="text" id="filter_btn_text" name="filter_btn_text" value="%s" />',
		esc_attr( $filterBtnLabel )
	);
}
//clear button html
function ads_clear_btn_html(){
 
	$clearBtnLabel = get_option( 'clear_btn_text' );
	printf(
		'<input type="text" id="clear_btn_text" name="clear_btn_text" value="%s" />',
		esc_attr( $clearBtnLabel )
	); 
}

?>