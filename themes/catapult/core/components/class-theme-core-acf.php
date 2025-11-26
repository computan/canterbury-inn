<?php
/**
 * ACF Integration.
 *
 * This component adds ACF options pages.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.17
 * @since   3.1.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class handing this themes custom ACF functionality.
 */
class Theme_Core_ACF extends Theme_Core_Component {

	/**
	 * Kicks off this class' functionality.
	 */
	protected function init() {
		$this->add_acf_pages();
	}

	/**
	 * Register the ACF option pages defined in settings file.
	 * This function also checks if the option pages settings are enabled, and that ACF is
	 * installed and enabled in the first place.
	 */
	private function add_acf_pages() {
		if (
			! isset( $this->settings->acf_options ) || // There are no ACF settings.
			true !== $this->settings->acf_options->init || // ACF is disabled in settings.
			! function_exists( 'acf_add_options_page' )    // ACF is not installed.
		) {
			return;
		}

		// Create options page.
		acf_add_options_page(
			array(
				'page_title' => $this->settings->acf_options->page_title,
				'menu_title' => $this->settings->acf_options->menu_title,
				'menu_slug'  => $this->settings->acf_options->menu_slug,
				'position'   => '2',
			)
		);

		// Create all options subpages.
		if ( isset( $this->settings->acf_options->subpages ) ) {
			$subpages = (array) $this->settings->acf_options->subpages;

			ksort( $subpages );

			foreach ( $subpages as $subpage ) {
				if ( isset( $subpage->parent_slug ) ) {
					$parent_slug = $subpage->parent_slug;
				} else {
					$parent_slug = $this->settings->acf_options->menu_slug;
				}

				if ( isset( $subpage->post_id ) ) {
					$subpage_post_id = $subpage->post_id;
					$menu_slug       = 'acf-options-' . $subpage->post_id;
				} else {
					$subpage_post_id = 'options';
					$menu_slug       = false;
				}

				acf_add_options_sub_page(
					array(
						'page_title'  => $subpage->page_title,
						'menu_title'  => $subpage->menu_title,
						'parent_slug' => $parent_slug,
						'post_id'     => $subpage_post_id,
						'menu_slug'   => $menu_slug,
					)
				);
			}
		}
	}
}
