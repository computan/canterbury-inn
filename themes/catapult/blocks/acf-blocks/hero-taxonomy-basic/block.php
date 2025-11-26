<?php
/**
 * Hero-Taxonomy-Basic
 *
 * Title:             Hero-Taxonomy-Basic
 * Description:       Hero section for use on taxonomy theme block locations.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          hero, taxonomy, basic, category, tag, archive
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:          core/button
 * JS Deps:
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       false
 * Mode:              preview
 * Styles:
 * Context:
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$hero_title       = '';
$hero_pre_heading = '';
$hero_description = '';

if ( is_tax() || is_category() || is_tag() ) {
	$term_object = get_queried_object();

	if ( ! empty( $term_object ) && ! empty( $term_object->name ) && ! empty( $term_object->taxonomy ) && ! empty( $term_object->term_id ) ) {
		$hero_title       = $term_object->name;
		$hero_description = $term_object->description;
	}
} elseif ( catapult_is_block_library() || catapult_is_theme_block() ) {
	$hero_title       = __( 'Taxonomy Title', 'catapult' );
	$hero_pre_heading = __( 'Taxonomy Type', 'catapult' );
	$hero_description = __( 'Taxonomy Title', 'catapult' );
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-taxonomy-basic<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link(); ?>

	<div class="container">
		<?php if ( ! empty( $hero_pre_heading ) ) : ?>
			<p class="has-overline-font-size"><?php echo esc_html( $hero_pre_heading ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $hero_title ) ) : ?>
			<h1><?php echo esc_html( $hero_title ); ?></h1>
		<?php endif; ?>

		<?php if ( ! empty( $hero_description ) ) : ?>
			<p><?php echo esc_html( $hero_description ); ?></p>
		<?php endif; ?>
	</div>
</section>
