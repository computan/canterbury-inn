<?php
/**
 * The color styles section for the block library.
 *
 * @package Catapult
 * @since   2.2.0
 * @since   2.2.6
 */

?>

<?php if ( file_exists( get_stylesheet_directory() . '/css/__base-includes/figma/_figma-color-variables.scss' ) ) : ?>
	<?php
	$color_variable_file_data = esc_html( file_get_contents( get_stylesheet_directory() . '/css/__base-includes/figma/_figma-color-variables.scss' ) );
	?>

	<?php if ( ! empty( $color_variable_file_data ) ) : ?>
		<?php
		preg_match_all( '/\t(.*): #(.*),/m', $color_variable_file_data, $matches, PREG_SET_ORDER );
		?>

		<?php if ( ! empty( $matches ) ) : ?>
			<section class="block-library__colors content-wrapper">
				<h2><?php esc_html_e( 'Color Styles', 'catapult' ); ?></h2>

				<?php foreach ( $matches as $match ) : ?>
					<?php if ( ! empty( $match[1] ) && ! empty( $match[2] ) ) : ?>
						<?php
						if ( 3 === strlen( $match[2] ) ) {
							$color_values = str_split( $match[2] );

							if ( $color_values[0] === $color_values[1] && $color_values[1] === $color_values[2] ) {
								$match[2] = $match[2] . $match[2];
							} else {
								$match[2] = $color_values[0] . $color_values[1] . $color_values[2] . $color_values[0] . $color_values[1] . $color_values[2];
							}
						}

						$r     = hexdec( substr( $match[2], 0, 2 ) );
						$g     = hexdec( substr( $match[2], 2, 2 ) );
						$b     = hexdec( substr( $match[2], 4, 2 ) );
						$yiq   = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
						$color = ( $yiq >= 128 ) ? 'black' : 'white';
						?>

						<div class="block-library__color" style="background-color: var(--<?php echo esc_attr( $match[1] ); ?>); color: var(--<?php echo esc_attr( $color ); ?>);"><?php echo esc_html( $match[1] ); ?><br><span class="block-library__color-hex">#<?php echo esc_html( $match[2] ); ?></span></div>
					<?php endif; ?>
				<?php endforeach; ?>
			</section>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
