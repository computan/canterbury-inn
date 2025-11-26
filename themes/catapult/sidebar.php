<?php
/**
 * Primary widget area
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

?>

<aside class="widget-area">
	<?php

	if ( is_active_sidebar( 'primary_widget_area' ) ) {
		dynamic_sidebar( 'primary_widget_area' );
	}

	?>
</aside>
