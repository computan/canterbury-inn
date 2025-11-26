<?php
/**
 * Display the deafult content.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Display the deafult content.
 */
function catapult_default_content() {
	if ( has_blocks() ) : ?>
		<?php the_content(); ?>
	<?php else : ?>
		<div class="default-content acf-block bg-transparent acf-inline-block">
			<?php the_content(); ?>
		</div>
		<?php
	endif;
}
