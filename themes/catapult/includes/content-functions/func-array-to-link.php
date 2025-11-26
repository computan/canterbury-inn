<?php
/**
 * Converts an ACF "link" field array into html.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Converts an ACF "link" field array into HTML.
 *
 * @param array  $link {      The ACF link field array.
 *     @type string   $url              The link URL.
 *     @type string   $title            The text to appear on the link.
 *     @type string   $target           The link target. Defaults to _self.
 * }
 * @param string $classes   Classes to be applied to the .wp-block-button element.
 * @param array  $args {
 *     Optional arguments.
 *
 *     @type int|bool $opening_tag_only Whether to output just the opening tag with no title or closing tag.
 *                                      Accepts 1|true or 0|false. Default 0|false.
 *     @type int|bool $no_title         Whether to not include the title within the link. If true, adds title as aria-label.
 *                                      Accepts 1|true or 0|false. Default 0|false.
 *     @type string   $element          An alternative HTML element to use instead of an '<a>' link.
 *     @type string   $link_classes     Classes to be applied to the .wp-block-button__link element.
 *     @type string   $atts             Additional attributes to add to the link element.
 *     @type string   $icon             If using an icon, the icon class to add to the icon '<span>'.
 *     @type string   $icon_position    Whether the icon should appear before or after the title.
 *                                      Accepts before/after or right/left. Default after.
 *     @type int|bool $disabled         Whether to not make the button inactive with the disabled attribute. Will covert links to button elements.
 *                                      Accepts 1|true or 0|false. Default 0|false.
 * }
 * @return string           HTML link.
 */
function catapult_array_to_link( $link, $classes = '', $args = array() ) {
	if ( empty( $link ) || ! is_array( $link ) ) {
		return;
	}

	if ( is_admin() ) {
		$link['url']    = '#';
		$link['target'] = '_self';
	}

	$defaults = array(
		'opening_tag_only' => false,
		'no_title'         => false,
		'element'          => null,
		'atts'             => '',
		'icon'             => null,
		'icon_position'    => 'right',
		'link_classes'     => '',
		'disabled'         => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$wrapper_atts = '';

	if ( empty( $link['target'] ) ) {
		$link['target'] = '_self';
	}

	if ( '_blank' === $link['target'] ) {
		$args['atts'] .= ' rel="noopener"';

		if ( empty( $args['icon'] ) ) {
			$args['icon'] = 'icon-arrow-external';
		}
	}

	if ( strpos( $link['url'], '/uploads/' ) ) {
		$args['atts'] .= ' download';
	}

	if ( ! empty( $args['icon'] ) ) {
		$wrapper_atts .= ' style="--buttonIcon: var(--' . $args['icon'] . ');"';

		if ( 'before' === $args['icon_position'] || 'left' === $args['icon_position'] ) {
			$classes .= ' wp-block-button--icon-left';
		} else {
			$classes .= ' wp-block-button--icon-right';
		}
	}

	if ( ( empty( $link['title'] ) && empty( $args['opening_tag_only'] ) ) || $args['no_title'] ) {
		if ( ! empty( $link['title'] ) ) {
			$args['atts'] .= ' aria-label="' . wp_strip_all_tags( $link['title'] ) . '"';
		} elseif ( ! empty( $args['icon'] ) ) {
			$args['atts'] .= ' aria-label="' . sprintf( __( '%s icon', 'catapult' ), $args['icon'] ) . '"';
		}
	}

	if ( ! empty( $classes ) && 0 !== strpos( $classes, ' ' ) ) {
		$classes = ' ' . $classes;
	}

	if ( ! empty( $args['link_classes'] ) && 0 !== strpos( $args['link_classes'], ' ' ) ) {
		$args['link_classes'] = ' ' . $args['link_classes'];
	}

	$output = '';

	$output .= '<div class="wp-block-button' . $classes . '"' . $wrapper_atts . '>';

	if ( ! empty( $args['disabled'] ) ) {
		$args['element'] = 'button';
		$args['atts']   .= ' disabled';

		if ( false === strpos( $args['link_classes'], 'disabled' ) ) {
			$args['link_classes'] .= ' disabled';
		}
	}

	if ( ! empty( $args['element'] ) ) {
		if ( 'button' === $args['element'] ) {
			$args['atts'] .= ' type="button"';
		}

		$output .= '<' . $args['element'] . $args['atts'] . ' class="wp-block-button__link' . $args['link_classes'] . '">';
	} else {
		$output .= '<a href="' . esc_url( $link['url'] ) . '" class="wp-block-button__link' . $args['link_classes'] . '" target="' . $link['target'] . '"' . $args['atts'] . '>';
	}

	if ( $args['opening_tag_only'] ) {
		return wp_kses_post( $output );
	}

	if ( false === $args['no_title'] ) {
		$output .= $link['title'];
	}

	if ( ! empty( $args['element'] ) ) {
		$output .= '</' . $args['element'] . '>';
	} else {
		$output .= '</a>';
	}

	$output .= '</div>';

	return wp_kses_post( $output );
}
