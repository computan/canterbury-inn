<?php
/**
 * Icon
 *
 * Title:             Icon
 * Description:       Block with selectable icon.
 * Instructions:
 * Category:          Icon
 * Icon:              marker
 * Keywords:          icon, icons
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Styles:
 * Mode:              preview
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$icon      = get_field( 'icon' );
$alignment = get_field( 'alignment' );

if ( empty( $alignment ) ) {
	$alignment = 'left';
}

if ( empty( $icon ) ) {
	$icon = '';
}

?>

<?php if ( ! empty( $icon ) ) : ?>
	<div class="block-icon block-icon--<?php echo esc_attr( $alignment ); ?>">
		<span class="block-icon__icon <?php echo esc_html( $icon ); ?>"></span>
	</div>
<?php elseif ( is_admin() ) : ?>
	<div class="block-icon"><span class="icon-maximize"></span><em><?php esc_html_e( 'Add Icon', 'catapult' ); ?></em></div>
<?php endif; ?>
