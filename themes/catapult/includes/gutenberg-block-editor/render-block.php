<?php
/**
 * Filter rendered block output.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.12
 * @since   3.0.13
 * @since   3.0.14
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 */

namespace Catapult\RenderBlock;

/**
 * Filter rendered block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function render_block( $block_content, $block ) {
	if ( ! is_admin() && ! empty( $block['blockName'] ) ) {
		if ( 0 === strpos( $block['blockName'], 'acf/' ) && false !== strpos( $block_content, 'wp-block-image' ) ) {
			$acf_blocks = acf_get_block_types();

			if ( ! empty( $acf_blocks[ $block['blockName'] ] ) ) {
				if ( ! empty( $acf_blocks[ $block['blockName'] ]['image_wrapper'] ) ) {
					$block_content = preg_replace_callback(
						'/<img.*?>/mi',
						function ( $matches ) {
								return '<div class="image-wrapper">' . $matches[0] . '</div>';
						},
						$block_content
					);
				}

				if ( ! empty( $acf_blocks[ $block['blockName'] ]['image_size'] ) ) {
					$image_size = $acf_blocks[ $block['blockName'] ]['image_size'];

					$block_content = preg_replace_callback(
						'/<img[^>]*wp-image-([0-9]*)[^>]*>/mi',
						function ( $matches ) use ( $image_size ) {
							return wp_get_attachment_image( $matches[1], $image_size, '', array( 'class' => 'wp-image-' . $matches[1] ) );
						},
						$block_content
					);
				}
			}
		}

		if ( 'core/html' === $block['blockName'] ) {
			$block_content = str_replace( '<iframe', '<div class="iframe-wrapper"><iframe', $block_content );
			$block_content = str_replace( '</iframe>', '</iframe></div>', $block_content );

			$content  = '<div class="wp-block-html">';
			$content .= $block_content;
			$content .= '</div>';

			return $content;
		} elseif ( 'core/image' === $block['blockName'] ) {
			if ( ! empty( $block['attrs']['videoURL'] ) ) {
				$video            = $block['attrs']['videoURL'];
				$original_content = $block_content;
				$block_content    = str_replace( 'wp-block-image ', 'wp-block-image component-video ', $block_content );
				$block_content    = str_replace( '<figure ', '<figure data-embed-url="' . $video . '" ', $block_content );
				$block_content    = str_replace( '<img ', '<div class="wp-block-image__wrapper"><button class="component-video__play-button c-btn--play c-btn--color-alt" type="button"><span class="sr-only">' . __( 'Play video', 'catapult' ) . '</span></button><img ', $block_content );

				if ( strpos( $block_content, '<figcaption' ) !== false ) {
					$block_content = str_replace( '<figcaption', '</div><figcaption', $block_content );
				} else {
					$block_content = str_replace( '</figure>', '</div></figure>', $block_content );
				}

				wp_enqueue_style( 'catapult-component-video' );
				wp_enqueue_script( 'catapult-component-video' );
			}

			if ( false !== strpos( $block_content, 'block-library/site-logo-placeholder' ) && false === strpos( $block_content, 'width' ) ) {
				$block_content = str_replace( '<img', '<img width="80" height="32"', $block_content );
			}
		} elseif ( 'core/heading' === $block['blockName'] ) {
			if ( 1 === preg_match( '/^<[^>]*><\/[^>]*>$/mU', trim( $block_content ) ) ) {
				return;
			}
		} elseif ( 'core/paragraph' === $block['blockName'] ) {
			if ( 1 === preg_match( '/^<[^>]*><\/[^>]*>$/mU', trim( $block_content ) ) ) {
				return;
			}
		} elseif ( 'core/list' === $block['blockName'] ) {
			$block_content = str_replace( '<ul class="', '<ul class="wp-block-list ', $block_content );
			$block_content = str_replace( '<ol class="', '<ol class="wp-block-list ', $block_content );
			$block_content = str_replace( '<ul>', '<ul class="wp-block-list">', $block_content );
			$block_content = str_replace( '<ol>', '<ol class="wp-block-list">', $block_content );
			$block_content = str_replace( '<li class="', '<li class="wp-block-list-item ', $block_content );
			$block_content = str_replace( '<li>', '<li class="wp-block-list-item">', $block_content );
			$block_content = str_replace( 'wp-block-list wp-block list', 'wp-block-list', $block_content );
			$block_content = str_replace( 'wp-block list-item wp-block list-item', 'wp-block-list-item', $block_content );
		} elseif ( 'core/button' === $block['blockName'] ) {
			if ( false !== strpos( $block_content, 'is-style-social' ) ) {
				if ( false !== strpos( $block_content, '></a>' ) || false !== strpos( $block_content, '>placeholder</a>' ) ) {
					$accessible_text = __( 'Visit us on social media', 'catapult' );

					$block_content = str_replace( 'placeholder', '', $block_content );

					preg_match( '/(?<=var\(--icon-)[a-zA-z0-9-_]*/m', $block_content, $social_link_matches );

					if ( ! empty( $social_link_matches ) ) {
						$social_link_matches[0] = str_replace( 'linkedin', 'LinkedIn', $social_link_matches[0] );
						$social_link_matches[0] = str_replace( 'YouTube', 'YouTube', $social_link_matches[0] );

						$accessible_text = sprintf( __( 'Visit us on %s', 'catapult' ), ucwords( str_replace( '-', ' ', $social_link_matches[0] ) ) );

						$block_content = str_replace( '</a>', $accessible_text . '</a>', $block_content );
					}
				}
			}

			if ( false !== strpos( $block_content, 'is-style-accordion' ) || false !== strpos( $block_content, 'is-style-tab' ) || false === strpos( $block_content, 'href' ) ) {
				$block_content = str_replace( '<a', '<button type="button"', $block_content );
				$block_content = str_replace( '</a>', '</button>', $block_content );
			}

			if ( false !== strpos( $block_content, 'is-style-accordion' ) ) {
				$block_content = '<!--AccordionButtonStart-->' . $block_content . '<!--AccordionButtonEnd-->';
				$block_content = str_replace( '<button', '<button aria-expanded="false"', $block_content );
			}
		} elseif ( 'core/buttons' === $block['blockName'] ) {
			if ( false !== strpos( $block_content, 'is-style-tertiary' ) && 1 !== preg_match( '/is-style-(?!tertiary)/m', $block_content ) ) {
				$block_content = str_replace( 'wp-block-buttons', 'wp-block-buttons wp-block-buttons--tertiary', $block_content );
			} elseif ( false !== strpos( $block_content, 'is-style-social' ) && 1 !== preg_match( '/is-style-(?!social)/m', $block_content ) ) {
				$block_content = str_replace( 'wp-block-buttons', 'wp-block-buttons wp-block-buttons--social', $block_content );
			}
		} elseif ( 'core/quote' === $block['blockName'] ) {
			if ( false !== strpos( $block_content, '|' ) ) {
				preg_match( '/<cite>.*<\/cite>/m', $block_content, $citation_match );

				if ( ! empty( $citation_match[0] ) ) {
					$citation = $citation_match[0];
					$citation = str_replace( '| ', '|', $citation );
					$citation = str_replace( ' |', '|', $citation );
					$citation = str_replace( '|', '<span class="wp-block-quote__citation-divider">|</span>', $citation );

					$block_content = str_replace( $citation_match[0], $citation, $block_content );
				}
			}
		} elseif ( 'core/table' === $block['blockName'] ) {
			if ( false === strpos( $block_content, 'wp-block-table is-style' ) ) {
				$block_content = str_replace( 'wp-block-table', 'wp-block-table is-style-flip', $block_content );
			}
		} elseif ( 'acf/media-gallery' === $block['blockName'] ) {
			$block_content = preg_replace_callback(
				'/<figure([^>]*)wp-block-image[^>]*>(.*?wp-image-([0-9]*).*?)<\/figure>/im',
				function ( $matches ) {
					$caption_html          = '';
					$additional_attributes = '';
					$additional_classes    = '';
					$caption               = wp_get_attachment_caption( $matches[3] );

					if ( ! empty( $caption ) ) {
						$caption_html = '<figcaption class="wp-element-caption">' . $caption . '</figcaption>';
					}

					preg_match( '/data-embed-url="([^"]*)"/', $matches[1], $embed_url_matches );

					if ( ! empty( $embed_url_matches ) ) {
						$additional_attributes = ' data-embed-url="' . $embed_url_matches[1] . '"';
						$additional_classes    = ' component-video';
					}

					return '<figure class="wp-block-image size-card-image-link-4" data-lightbox-content="' . htmlspecialchars( wp_json_encode( '<figure class="component-lightbox__image-wrapper' . $additional_classes . '"' . $additional_attributes . '>' . wp_get_attachment_image( $matches[3], 'full-width' ) . '</figure>' . $caption_html ) ) . '">' . $matches[2] . '</figure>';
				},
				$block_content
			);
		} elseif ( 'catapult/navigation' === $block['blockName'] ) {
			$block_content = preg_replace_callback(
				'/<img[^>]*block-navigation__logo--([0-9]*)[^>]*>/mi',
				function ( $matches ) {
					if ( ! empty( get_post_status( $matches[1] ) ) ) {
						$logo_id = $matches[1];
					} else {
						$logo_id = 'site-logo-placeholder';
					}

					return wp_get_attachment_image( $logo_id, 'large', '', array( 'class' => 'block-navigation__logo' ) );
				},
				$block_content
			);
		} elseif ( 'acf/accordion-item' === $block['blockName'] ) {
			$block_content = preg_replace( '/<!--AccordionButtonStart-->\s*<div/m', '<!--AccordionButtonStart--><h2', $block_content );
			$block_content = preg_replace( '/<\/div>\s*<!--AccordionButtonEnd-->/m', '</h2><!--AccordionButtonEnd-->', $block_content );

			if ( false !== strpos( $block_content, 'is-style-open' ) ) {
				$block_content = str_replace( 'aria-expanded="false"', 'aria-expanded="true"', $block_content );
			}

			$accordion_content_id = wp_unique_id( 'accordion-content-' );

			$block_content = str_replace( 'aria-expanded', 'aria-controls="' . $accordion_content_id . '" aria-expanded', $block_content );
			$block_content = preg_replace( '/<!--AccordionButtonEnd-->\s*<div/m', '<!--AccordionButtonEnd--><div id="' . $accordion_content_id . '"', $block_content );
		}

		if ( 'acf/accordion-centered' === $block['blockName'] || 'acf/accordion-side-heading' === $block['blockName'] || 'acf/accordion-side-image' === $block['blockName'] ) {
			if ( false !== strpos( $block_content, '<h2 class="wp-block-heading' ) ) {
				$block_content = str_replace( '<!--AccordionButtonStart--><h2', '<!--AccordionButtonStart--><h3', $block_content );
				$block_content = str_replace( '</h2><!--AccordionButtonEnd-->', '</h3><!--AccordionButtonEnd-->', $block_content );
			}
		}
	}

	return $block_content;
}
add_filter( 'render_block', 'Catapult\RenderBlock\render_block', 10, 2 );
