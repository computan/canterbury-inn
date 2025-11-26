<?php
/**
 * The footer for theme.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

$custom_footer = get_field( 'custom_footer' );
?>

			<footer id="footer" class="main-footer">
				<a href="#top" class="skip-link"><?php esc_html_e( 'Skip footer and return to top.', 'catapult' ); ?></a>

				<?php
				if ( ! empty( $custom_footer ) ) {
					if ( ! empty( $custom_footer->post_content ) ) {
						echo apply_filters( 'the_content', $custom_footer->post_content ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					catapult_render_theme_blocks( 'footer' );
				}
				?>
			</footer>
		</div> <!-- /#page -->

		<?php catapult_render_theme_blocks( 'alert_bottom' ); ?>

		<?php catapult_render_theme_blocks( 'alert_popup' ); ?>

		<span class="tablet-checker"></span>

		<?php wp_footer(); ?>
	</body>
</html>
