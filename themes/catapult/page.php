<?php
/**
 * The static page template.
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
?>

<main id="main" class="content-wrapper">
	<?php catapult_default_content(); ?>
</main>

<?php
get_footer();
