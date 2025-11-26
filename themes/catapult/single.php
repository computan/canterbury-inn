<?php
/**
 * The single post page template.
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
the_post();

global $post;

?>

<main id="main" class="content-wrapper">
	<?php catapult_render_theme_blocks( $post->post_type . '_top' ); ?>
	<?php catapult_default_content(); ?>
	<?php catapult_render_theme_blocks( $post->post_type . '_bottom' ); ?>
</main>

<?php
get_footer();
