<?php
/**
 * Alert-Popup
 *
 * Title:             Alert-Popup
 * Description:       Alert block that appears as a full-screen popup message.
 * Instructions:
 * Category:          Navigation
 * Icon:              bell
 * Keywords:          alert, notification, popup, notice, message, cookie
 * Post Types:        theme_block
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$current_date = gmdate( 'YmdHi' );
$schedule     = get_field( 'schedule' );
$start_date   = get_field( 'start_date' );
$end_date     = get_field( 'end_date' );

$allowed_blocks = catapult_allowed_block_types_all();

$template = array(
	array(
		'acf/text-centered',
	),
);

if ( empty( $content ) ) {
	$content = '';
}

?>

<?php if ( ( is_admin() || ! $schedule || ( $current_date > $start_date && $current_date < $end_date ) ) && empty( $_GET['qa'] ) && empty( $_GET['lighthouse'] ) ) : //phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
	<dialog id="alert-<?php echo esc_html( md5( $content ) ); ?>" class="block-alert-popup block-alert-popup--hidden<?php echo esc_attr( $content_block->get_block_classes() ); ?>" role="dialog" aria-modal="true" aria-live="assertive">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-alert-popup__content-wrapper content-wrapper" />

		<div class="bg-dark"><button class="block-alert-popup__close-button" aria-label="<?php esc_html_e( 'Close popup', 'catapult' ); ?>" type="button"></button></div>
	</dialog>
<?php endif; ?>
