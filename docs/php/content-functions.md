[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/php/README.md) | [Next Article →](/docs/php/theme-functions.md)

# PHP Content Functions
The [/includes/content-functions/](/themes/catapult/includes/content-functions/) directory contains a number of functions used throughout the theme. Documentation of the function parameters can be found in each file. Here are some of the most commonly used functions:

* `catapult_array_to_link( $link, $classes, $args )` File: [func-array-to-link.php](/themes/catapult/includes/content-functions/func-array-to-link.php)
	* This function will convert an [ACF link](https://www.advancedcustomfields.com/resources/link/) array into the HTML markup for a core/button WordPress block. Helpful when needing to add buttons outside the block editor.

* `catapult_the_back_link( $post_id, $title, $permalink )` File: [func-the-back-link.php](/themes/catapult/includes/content-functions/func-the-back-link.php)
	* Used in hero blocks to display a back link. Displays a link to a parent page, or an archive page for CPTs.

* `catapult_get_primary_term( $taxonomy, $post_id, $args )` File: [func-get-primary-term.php](/themes/catapult/includes/content-functions/func-get-primary-term.php)
	* Get a single taxonomy term for a post. If Yoast is used, will return the primary term, otherwise will return the first term.

* `catapult_modify_filter_block_args( $args )` File: [func-modify-filter-block-args.php](/themes/catapult/includes/content-functions/func-modify-filter-block-args.php)
	* This function is used to modify the WP Query args that are used within the Filter blocks as well as the REST API when new pages or filters are changed. If you need to control the queries on archive pages, this function can be modified rather than adding a new `pre_get_posts` filter.
