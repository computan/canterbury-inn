<?php
/**
 * The main template file.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

get_header();
?>

<main id="main" class="content-wrapper">
	<?php
	if ( is_home() ) {
		$blog_page_id = get_option( 'page_for_posts' );

		echo apply_filters( 'the_content', get_the_content( null, false, $blog_page_id ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$queried_object = get_queried_object();

		if ( ! empty( $queried_object->taxonomy ) ) {
			catapult_render_theme_blocks( $queried_object->taxonomy );
		}
	}
	?>
</main>

<?php
get_footer();
