<?php
/**
 * The button styles section for the block library.
 *
 * @package Catapult
 * @since   2.2.6
 * @since   3.0.0
 */

?>

<section class="block-library__buttons">
	<div class="block-library__foundations-header">
		<div class="container">
			<h2><?php esc_html_e( 'Button Styles', 'catapult' ); ?></h2>
		</div>
	</div>

	<?php for ( $section_index = 1; $section_index <= 2; $section_index++ ) : ?>
		<?php
		$grid_classes  = '';
		$section_style = __( '(Light)', 'catapult' );

		if ( 2 === $section_index ) {
			$grid_classes  = ' bg-dark';
			$section_style = __( '(Dark)', 'catapult' );
		}
		?>

		<div class="block-library__buttons-section<?php echo esc_attr( $grid_classes ); ?>" data-block-index="<?php echo esc_attr( $section_index ); ?>">
			<div class="container">
				<div class="block-library__buttons-row row">
					<div class="col-4"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Primary Buttons', 'catapult' ); ?></h3></div>
					<div class="col-4"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Secondary Buttons', 'catapult' ); ?></h3></div>
					<div class="col-4"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Tertiary Buttons', 'catapult' ); ?></h3></div>

					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Primary Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Primary Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Primary Disabled', 'catapult' ); ?></h4></div>
					<div class="col-1"></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Secondary Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Secondary Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Secondary Disabled', 'catapult' ); ?></h4></div>
					<div class="col-1"></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Tertiary Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Tertiary Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Tertiary Disabled', 'catapult' ); ?></h4></div>
					<div class="col-1"></div>

					<?php for ( $row_index = 1; $row_index <= 7; $row_index++ ) : ?>
						<?php for ( $column_index = 1; $column_index <= 9; $column_index++ ) : ?>
							<?php
							$button_classes       = '';
							$button_title         = '';
							$button_icon          = '';
							$button_icon_position = '';
							$button_link_classes  = '';
							$button_disabled      = false;

							if ( $column_index >= 1 && $column_index <= 3 ) {
								$button_title    = __( 'Primary', 'catapult' );
								$button_classes .= ' is-style-primary';
							} elseif ( $column_index >= 4 && $column_index <= 6 ) {
								$button_title    = __( 'Secondary', 'catapult' );
								$button_classes .= ' is-style-secondary';
							} elseif ( $column_index >= 7 && $column_index <= 9 ) {
								$button_title    = __( 'Tertiary', 'catapult' );
								$button_classes .= ' is-style-tertiary';
							}

							if ( in_array( $column_index, array( 2, 5, 8 ), true ) ) {
								$button_link_classes .= ' hover';
							} elseif ( in_array( $column_index, array( 3, 6, 9 ), true ) ) {
								$button_disabled = true;
							}

							if ( $row_index > 4 ) {
								$button_classes .= ' wp-block-button--small';
							}

							if ( 2 === $row_index || 6 === $row_index ) {
								$button_icon          = 'icon-arrow-right';
								$button_icon_position = 'right';
							} elseif ( 3 === $row_index || 7 === $row_index ) {
								$button_icon          = 'icon-arrow-right';
								$button_icon_position = 'left';
							} elseif ( 4 === $row_index ) {
								$button_classes      .= ' wp-block-button--hidden-label';
								$button_icon          = 'icon-arrow-right';
								$button_icon_position = 'right';
							}
							?>

							<div class="col-1 block-library__button-col block-library__button-col--main">
								<?php
								if ( $column_index < 7 || 4 !== $row_index ) {
									echo wp_kses_post(
										catapult_array_to_link(
											array(
												'title' => $button_title,
												'url'   => '#',
											),
											$button_classes,
											array(
												'icon'     => $button_icon,
												'icon_position' => $button_icon_position,
												'link_classes' => $button_link_classes,
												'disabled' => $button_disabled,
											)
										)
									);
								}
								?>
							</div>

							<?php if ( 3 === $column_index || 6 === $column_index || 9 === $column_index ) : ?>
								<div class="block-library__buttons-table-spacer col-1 block-library__button-col"></div>
							<?php endif; ?>
						<?php endfor; ?>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	<?php endfor; ?>

	<?php for ( $section_index = 1; $section_index <= 2; $section_index++ ) : ?>
		<?php
		$grid_classes  = '';
		$section_style = __( '(Light)', 'catapult' );

		if ( 2 === $section_index ) {
			$grid_classes  = ' bg-dark';
			$section_style = __( '(Dark)', 'catapult' );
		}
		?>

		<div class="block-library__buttons-section<?php echo esc_attr( $grid_classes ); ?>" data-block-index="<?php echo esc_attr( $section_index + 2 ); ?>">
			<div class="container">
				<div class="block-library__buttons-row row">
					<div class="col-3"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Solid Slider Buttons', 'catapult' ); ?></h3></div>
					<div class="col-3"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Outline Slider Buttons', 'catapult' ); ?></h3></div>
					<div class="col-1"></div>
					<div class="col-2"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Solid Play Buttons', 'catapult' ); ?></h3></div>
					<div class="col-2"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Outline Play Buttons', 'catapult' ); ?></h3></div>
					<div class="col-1"></div>

					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Solid Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Solid Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Solid Disabled', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Outline Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Outline Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Outline Disabled', 'catapult' ); ?></h4></div>
					<div class="col-1"></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Solid Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Solid Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Outline Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Outline Hover', 'catapult' ); ?></h4></div>
					<div class="col-1"></div>

					<div class="col-1 block-library__button-col">
						<button type="button" class="swiper-button-prev"><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
						<button type="button" class="swiper-button-next"><?php esc_html_e( 'Next', 'catapult' ); ?></button>
					</div>
					<div class="col-1 block-library__button-col">
						<button type="button" class="swiper-button-prev hover"><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
						<button type="button" class="swiper-button-next hover"><?php esc_html_e( 'Next', 'catapult' ); ?></button>
					</div>
					<div class="col-1 block-library__button-col">
						<button type="button" class="swiper-button-prev" disabled><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
						<button type="button" class="swiper-button-next" disabled><?php esc_html_e( 'Next', 'catapult' ); ?></button>
					</div>
					<div class="col-1 block-library__button-col">
						<button type="button" class="swiper-button-prev swiper-button--outline"><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
						<button type="button" class="swiper-button-next swiper-button--outline"><?php esc_html_e( 'Next', 'catapult' ); ?></button>
					</div>
					<div class="col-1 block-library__button-col">
						<button type="button" class="swiper-button-prev swiper-button--outline hover"><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
						<button type="button" class="swiper-button-next swiper-button--outline hover"><?php esc_html_e( 'Next', 'catapult' ); ?></button>
					</div>
					<div class="col-1 block-library__button-col">
						<button type="button" class="swiper-button-prev swiper-button--outline" disabled><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
						<button type="button" class="swiper-button-next swiper-button--outline" disabled><?php esc_html_e( 'Next', 'catapult' ); ?></button>
					</div>

					<div class="col-1"></div>

					<div class="col-1 block-library__button-col"><button type="button" class="c-btn c-btn--play" aria-label="<?php esc_html_e( 'Play', 'catapult' ); ?>"></button></div>
					<div class="col-1 block-library__button-col"><button type="button" class="c-btn c-btn--play hover" aria-label="<?php esc_html_e( 'Play', 'catapult' ); ?>"></button></div>
					<div class="col-1 block-library__button-col"><button type="button" class="c-btn c-btn--secondary c-btn--play" aria-label="<?php esc_html_e( 'Play', 'catapult' ); ?>"></button></div>
					<div class="col-1 block-library__button-col"><button type="button" class="c-btn c-btn--secondary c-btn--play hover" aria-label="<?php esc_html_e( 'Play', 'catapult' ); ?>"></button></div>
				</div>
			</div>
		</div>
	<?php endfor; ?>

	<div class="block-library__buttons-section block-library__buttons-section--transparent bg-dark" data-block-index="5">
		<div class="container">
			<div class="block-library__buttons-row row">
				<div class="col-3"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Transparent Slider Buttons', 'catapult' ); ?></h3></div>
				<div class="col-9"></div>

				<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Transparent Default', 'catapult' ); ?></h4></div>
				<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Transparent Hover', 'catapult' ); ?></h4></div>
				<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Transparent Disabled', 'catapult' ); ?></h4></div>
				<div class="col-9"></div>

				<div class="col-1 block-library__button-col">
					<button type="button" class="swiper-button-prev swiper-button--transparent"><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
					<button type="button" class="swiper-button-next swiper-button--transparent"><?php esc_html_e( 'Next', 'catapult' ); ?></button>
				</div>
				<div class="col-1 block-library__button-col">
					<button type="button" class="swiper-button-prev swiper-button--transparent hover"><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
					<button type="button" class="swiper-button-next swiper-button--transparent hover"><?php esc_html_e( 'Next', 'catapult' ); ?></button>
				</div>
				<div class="col-1 block-library__button-col">
					<button type="button" class="swiper-button-prev swiper-button--transparent" disabled><?php esc_html_e( 'Previous', 'catapult' ); ?></button>
					<button type="button" class="swiper-button-next swiper-button--transparent" disabled><?php esc_html_e( 'Next', 'catapult' ); ?></button>
				</div>

				<div class="col-9"></div>
			</div>
		</div>
	</div>

	<?php for ( $section_index = 1; $section_index <= 2; $section_index++ ) : ?>
		<?php
		$grid_classes  = '';
		$section_style = __( '(Light)', 'catapult' );

		if ( 2 === $section_index ) {
			$grid_classes  = ' bg-dark';
			$section_style = __( '(Dark)', 'catapult' );
		}
		?>

		<div class="block-library__buttons-section<?php echo esc_attr( $grid_classes ); ?>" data-block-index="<?php echo esc_attr( $section_index + 5 ); ?>">
			<div class="container">
				<div class="block-library__buttons-row row">
					<div class="col-4"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Close Buttons', 'catapult' ); ?></h3></div>
					<div class="col-8"><h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Social Buttons', 'catapult' ); ?></h3></div>

					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Default', 'catapult' ); ?></h4></div>
					<div class="col-1"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Hover', 'catapult' ); ?></h4></div>
					<div class="col-2"></div>

					<div class="col-4"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Default', 'catapult' ); ?></h4></div>
					<div class="col-4"><h4 class="block-library__foundations-overline"><?php esc_html_e( 'Hover', 'catapult' ); ?></h4></div>

					<div class="col-1"><button type="button" class="c-btn--close" aria-label="<?php esc_html_e( 'Close', 'catapult' ); ?>"></button></div>
					<div class="col-1"><button type="button" class="c-btn--close hover" aria-label="<?php esc_html_e( 'Close', 'catapult' ); ?>"></button></div>
					<div class="col-2"></div>

					<?php for ( $column_index = 1; $column_index <= 2; $column_index++ ) : ?>
						<div class="col-4">
							<div class="wp-block-buttons wp-block-buttons--social">
								<?php for ( $button_index = 0; $button_index < 10; $button_index++ ) : ?>
									<?php
									$button_title        = '';
									$button_icon         = '';
									$button_link_classes = '';

									$social_icons = array( 'LinkedIn', 'X', 'Facebook', 'YouTube', 'Instagram', 'Tiktok', 'Pinterest' );

									if ( ! empty( $social_icons[ $button_index ] ) ) {
										$button_title = sprintf( __( 'Visit us on %s', 'catapult' ), $social_icons[ $button_index ] );
										$button_icon  = 'icon-' . strtolower( $social_icons[ $button_index ] );
									} elseif ( 7 === $button_index ) {
										$button_title = __( 'Email us', 'catapult' );
										$button_icon  = 'icon-email';
									} elseif ( 8 === $button_index ) {
										$button_title = __( 'Call us', 'catapult' );
										$button_icon  = 'icon-phone';
									} elseif ( 9 === $button_index ) {
										$button_title = __( 'Permalink', 'catapult' );
										$button_icon  = 'icon-link';
									}

									if ( 2 === $column_index ) {
										$button_link_classes = 'hover';
									}

									echo wp_kses_post(
										catapult_array_to_link(
											array(
												'title' => $button_title,
												'url'   => '#',
											),
											'is-style-social',
											array(
												'icon' => $button_icon,
												'link_classes' => $button_link_classes,
											)
										)
									);
									?>
								<?php endfor; ?>
							</div>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	<?php endfor; ?>
</section>
