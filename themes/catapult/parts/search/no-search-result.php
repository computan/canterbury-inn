<?php
/**
 * The search no search results.
 *
 * @category Template
 * @package Catapult
 * @since   3.1.2
 */

if ( function_exists( 'get_field' ) ) {
	$not_found_main           = get_field( 'no_result_found_messages', 'search' );
	$result_not_found_heading = ! empty( $not_found_main['result_not_found_heading'] ) ? $not_found_main['result_not_found_heading'] : '';
	$result_not_found_text    = ! empty( $not_found_main['result_not_found_text'] ) ? $not_found_main['result_not_found_text'] : '';
}

$contact_button_link = ! empty( $not_found_main['contact_button'] ) ? $not_found_main['contact_button'] : '';
$url                 = ! empty( $contact_button_link['url'] ) ? $contact_button_link['url'] : '';
$post_title          = ! empty( $contact_button_link['title'] ) ? $contact_button_link['title'] : '';
?>
<div class="search-no-results">
	<div class="no-result">
		<div class="no-result__heading">
			<h2><?php echo esc_html( $result_not_found_heading ); ?></h2>
		</div>
		<div class="no-result__para">
			<p><?php echo esc_html( $result_not_found_text ); ?></p>
		</div>
		<div class="no-result__buttons">
			<div class="clear-search-btn no-result-btn">
				<a href="javascript:void(0);" class="wp-block-button__link">Clear Search</a>
			</div>  
			<div class="no-result-btn">
				<a href="<?php echo esc_attr( $url ); ?>" class="wp-block-button__link"><?php echo esc_html( $post_title ); ?></a>
			</div>
		</div>
	</div>
</div>
