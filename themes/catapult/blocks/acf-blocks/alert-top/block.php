<?php
/**
 * Alert-Top
 *
 * Title:             Alert-Top
 * Description:       Alert block that appears at the top of the page.
 * Instructions:
 * Category:          Navigation
 * Icon:              bell
 * Keywords:          alert, notification, top bar, notice, message
 * Post Types:        theme_block
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: background_color
 * Background Colors: transparent, white, neutral-3
 * Default BG Color:  neutral-3
 * InnerBlocks:       true
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.15
 * @since   3.0.16
 * @since   3.1.2
 * @since   3.1.5
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$current_date = gmdate( 'YmdHi' );
$schedule     = get_field( 'schedule' );
$start_date   = get_field( 'start_date' );
$end_date     = get_field( 'end_date' );

$allowed_blocks = array( 'core/paragraph' );

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add an alert message here. Add links within this text.', 'catapult' ),
		),
	),
);

?>

<?php if ( is_admin() || ! $schedule || ( $current_date > $start_date && $current_date < $end_date ) ) : ?>
	<div class="block-alert-top acf-inline-block<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-alert-top__container" templateLock="all" />
		<button class="block-alert-top__close" type="button" aria-label="Close alert">
			<span class="alert-cancel">
				<i class="icon icon-cancel"></i>
				<span class="alert-cancel--overlay"></span>
			</span>
		</button>
	</div>
<?php endif; ?>
