<?php
/**
 * Filter blocks sort component.
 *
 * @package Catapult
 * @since   3.0.19
 * @since   3.1.0
 */

?>

<div class="filter-sort">
	<div class="filter-sort__label">
		<label><?php echo esc_html( __( 'Sort by', 'catapult' ) ); ?></label>

		<button type="button" class="filter-sort__label-current"><?php echo esc_html( __( 'Newest to Oldest', 'catapult' ) ); ?></button>
	</div>

	<div class="filter-sort__dropdown">
		<button value="newest" type="button" class="filter-sort__option selected"><?php echo esc_html( __( 'Newest to Oldest', 'catapult' ) ); ?></button>

		<button value="oldest" type="button" class="filter-sort__option"><?php echo esc_html( __( 'Oldest to Newest', 'catapult' ) ); ?></button>

		<button value="alphabetical" type="button" class="filter-sort__option"><?php echo esc_html( __( 'A - Z', 'catapult' ) ); ?></button>

		<button value="reverse_alphabetical" type="button" class="filter-sort__option"><?php echo esc_html( __( 'Z - A', 'catapult' ) ); ?></button>
	</div>
</div>
