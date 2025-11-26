<?php
/**
 * Embed responsive wrapper.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

/**
 * Adds an embed responsive wrapper.
 *
 * @param string $cached_html Cached HTML for the responsive embed.
 * @param string $url         Url for the external embedded asset.
 */
function oembed_video_wrapper( $cached_html, $url ) {
	$class = 'iframe-wrapper';
	// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	// $icon  = get_svg( 'triangle-play' );
	$id = substr( $url, strrpos( $url, '/' ) + 1 );

	if ( strpos( $url, 'wistia' ) !== false ) {
		$class   .= ' wistia';
		$title    = __( 'wistia video', 'catapult' );
		$response = wp_remote_get( "https://fast.wistia.com/oembed?url=$url" );

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$data    = json_decode( $response['body'] );
			$img_src = $data->thumbnail_url;
		}
	} elseif ( strpos( $url, 'vimeo' ) !== false ) {
		$class   .= ' vimeo';
		$title    = __( 'vimeo video', 'catapult' );
		$response = wp_remote_get( "https://vimeo.com/api/oembed.json?url=$url&width=1680" );

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$data    = json_decode( $response['body'] );
			$img_src = preg_replace( '/_[\s\S]+?\./', '.', $data->thumbnail_url );
		}
	} else {
		$class  .= ' youtube';
		$img_src = $url;
		$matches;
		preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches );

		if ( ! empty( $matches[1] ) ) {
			$id       = $matches[1];
			$url_base = 'https://img.youtube.com/vi/' . $id . '/';
			$url_max  = $url_base . 'maxresdefault.jpg';
			$url_sd   = $url_base . 'sddefault.jpg';
			$url      = $url_base . '0.jpg';

			if ( catapult_is_url_200( $url_max ) ) {
				$img_src = $url_max;
			} elseif ( catapult_is_url_200( $url_sd ) ) {
				$img_src = $url_sd;
			}
		}

		$title = __( 'youtube video', 'catapult' );
	}

	if ( catapult_is_block_library() ) {
		$img_src = get_stylesheet_directory_uri() . '/images/block-library/placeholder-16-9.png';
	}

	// Move iframe src to data-src attribute to avoid loading iframe when page is loading.
	$cached_html = preg_replace( '/src=\"/', 'loading="lazy" data-src="', $cached_html );

	// Add title to iframe.
	$cached_html = preg_replace( '/<iframe/', '<iframe title="' . $title . '"', $cached_html );

	return "<div class='$class' data-video-id='$id' data-image-src='$img_src'>
				<div class='iframe-wrapper__overlay' style='background-image: url($img_src);'>
					<button class='iframe-wrapper__play c-btn--play c-btn--color-alt'><span class='sr-only'>" . __( 'Play video', 'catapult' ) . "</span></button>
				</div>
				$cached_html
			</div>";
}
add_filter( 'embed_oembed_html', 'oembed_video_wrapper', 99, 4 );
