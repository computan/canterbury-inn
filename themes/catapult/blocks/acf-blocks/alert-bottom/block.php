<?php
/**
 * Alert-Bottom
 *
 * Title:             Alert-Bottom
 * Description:       Alert block that appears fixed at the bottom of the page for messages like cookie notices.
 * Instructions:
 * Category:          Navigation
 * Icon:              bell
 * Keywords:          alert, notification, bottom bar, notice, message, cookie
 * Post Types:        theme_block
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: background_color
 * Background Colors:
 * Default BG Color:  neutral-3
 * InnerBlocks:       true
 * Styles:
 * Context:
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$current_date      = gmdate( 'YmdHi' );
$schedule          = get_field( 'schedule' );
$start_date        = get_field( 'start_date' );
$end_date          = get_field( 'end_date' );
$close_button_text = get_field( 'close_button_text' );

if ( empty( $close_button_text ) ) {
	$close_button_text = __( 'Got it!', 'catapult' );
}

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/buttons', 'core/button' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Alert message', 'catapult' ),
		),
	),
);

if ( empty( $content ) ) {
	$content = '';
}

?>

<?php if ( ( is_admin() || ! $schedule || ( $current_date > $start_date && $current_date < $end_date ) ) && empty( $_GET['qa'] ) && empty( $_GET['lighthouse'] ) ) :  //phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
	<div id="alert-<?php echo esc_html( md5( $content ) ); ?>" role="banner" class="block-alert-bottom block-alert-bottom--hidden<?php echo esc_attr( $content_block->get_block_classes() ); ?>">	
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />

		<button class="block-alert-bottom__close-button" type="button"><?php echo esc_html( $close_button_text ); ?></button>
	</div>
<?php endif; ?>
