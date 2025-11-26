<?php
/**
 * Hooks for the password form.
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.1
 * @since   3.0.2
 */

namespace Catapult\PasswordForm;

/**
 * Retrieve protected post password form content.
 *
 * @param string      $output The password form HTML output.
 * @param int|WP_Post $post   Optional. Post ID or WP_Post object. Default is global $post.
 * @return string HTML content for password form for password protected post.
 */
function password_form( $output, $post = 0 ) {
	$post   = get_post( $post );
	$label  = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
	$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
	<div class="post-password-form__parent-container">
		<div class="post-password-form__container">
			<p class="post-password-form__content">' . __( 'Enter your access code to continue.', 'catapult' ) . '</p>
			<p class="post-password-form__code"><span>Code</span></p>
			<p class="post-password-form__label-container">
				<label class="post-password-form__label" for="' . $label . '">
					<input name="post_password" id="' . $label . '" type="password" size="20" placeholder="********" />
				</label>
			</p>
			<button type="submit" name="Submit" class="c-btn c-btn--primary post-password-form__button">' . esc_attr_x( 'Submit', 'post password form', 'catapult' ) . '</button>
		</div>
	</div>
	</form>';

	return $output;
}
add_filter( 'the_password_form', 'Catapult\PasswordForm\password_form', 10, 2 );
