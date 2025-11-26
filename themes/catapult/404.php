<?php
/**
 * The 404 page template.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 */

get_header();
?>

<main id="main" class="content-wrapper"> 
	<?php catapult_render_theme_blocks( '404_page' ); ?>
</main>

<?php
get_footer();
