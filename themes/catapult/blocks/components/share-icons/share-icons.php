<?php
/**
 * The share icons partial.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

?>

<div class="a2a_kit share-icons wp-block-buttons wp-block-buttons--social">
	<?php
	echo wp_kses_post(
		catapult_array_to_link(
			array(
				'title' => __( 'Share on LinkedIn', 'catapult' ),
				'url'   => '#',
			),
			'is-style-social',
			array(
				'icon'         => 'icon-linkedin',
				'link_classes' => 'a2a_button_linkedin share-icons__link',
				'atts'         => ' role="button"',
			)
		)
	);

	echo wp_kses_post(
		catapult_array_to_link(
			array(
				'title' => __( 'Share on X', 'catapult' ),
				'url'   => '#',
			),
			'is-style-social',
			array(
				'icon'         => 'icon-x',
				'link_classes' => 'a2a_button_x share-icons__link',
				'atts'         => ' role="button"',
			)
		)
	);

	echo wp_kses_post(
		catapult_array_to_link(
			array(
				'title' => __( 'Share on Facebook', 'catapult' ),
				'url'   => '#',
			),
			'is-style-social',
			array(
				'icon'         => 'icon-facebook',
				'link_classes' => 'a2a_button_facebook share-icons__link',
				'atts'         => ' role="button"',
			)
		)
	);

	echo wp_kses_post(
		catapult_array_to_link(
			array(
				'title' => __( 'Share', 'catapult' ),
				'url'   => '#',
			),
			'is-style-social',
			array(
				'icon'         => 'icon-link',
				'link_classes' => 'a2a_dd share-icons__link',
				'atts'         => ' role="button"',
			)
		)
	);
	?>
</div>
