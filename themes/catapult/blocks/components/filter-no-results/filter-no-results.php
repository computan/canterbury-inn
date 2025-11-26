<?php
/**
 * No results content for filter blocks.
 *
 * @package Catapult
 * @since   3.0.19
 * @since   3.1.0
 * @since   3.1.6
 * @since   3.1.8
 */

?>

<?php
$contact_page_id = get_field( 'contact_page', 'general' );

if ( ! empty( $contact_page_id ) ) {
	$contact_permalink = get_permalink( $contact_page_id );
}
?>

<div class="filter-no-results">
	<?php
	if ( ! empty( $selected_filters ) ) {
		$selected_filters = json_decode( $selected_filters );
		$search_term      = $selected_filters->search ?? '';
	}
	?>
	
	<p class="filter-no-results__header">
		<?php if ( ! empty( $search_term ) ) : ?>
			<?php echo esc_html( sprintf( __( 'No results found for "%s".', 'catapult' ), $search_term ) ); ?>
		<?php endif; ?>

		<?php echo esc_html( __( 'Please reset or adjust your search.', 'catapult' ) ); ?>
	</p>

	<div class="filter-no-results__buttons">
		<button type="button" class="filter-no-results__reset-search c-btn c-btn--tertiary"><?php echo esc_html( __( 'Reset Search', 'catapult' ) ); ?></button>

		<?php if ( ! empty( $contact_permalink ) ) : ?>
			<a href="<?php echo esc_url( $contact_permalink ); ?>" class="c-btn c-btn--tertiary"><?php echo esc_html( __( 'Contact Us', 'catapult' ) ); ?></a>
		<?php endif; ?>
	</div>
</div>
