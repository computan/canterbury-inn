<?php
/**
 * The tag archive page.
 *
 * @package Catapult
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

get_header();
?>

<main id="main" class="content-wrapper">
	<?php
	$queried_object = get_queried_object();

	if ( ! empty( $queried_object->name ) ) {
		catapult_render_theme_blocks( $queried_object->taxonomy );
	}
	?>
</main>

<?php
get_footer();
