<?php
/**
 * Custom Comments Walker.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Custom Comments Walker Class.
 */
class Custom_Comments_Walker extends Walker_Comment {
	/**
	 * What the class handles.
	 *
	 * @var string
	 *
	 * @see Walker::$tree_type
	 */
	public $tree_type = 'comment';

	/**
	 * Database fields to use.
	 *
	 * @var array
	 *
	 * @see Walker::$db_fields
	 */
	public $db_fields = array(
		'parent' => 'comment_parent',
		'id'     => 'comment_ID',
	);

	/**
	 * Wrapper for comments list.
	 */
	public function __construct() {
		?>
		<section class="comments-list">
		<?php
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 * @global int $comment_depth
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int    $depth  Optional. Depth of the current comment. Default 0.
	 * @param array  $args   Optional. Uses 'style' argument for type of HTML list. Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['comment_depth'] = $depth + 2;
		?>
		<section class="child-comments comments-list">
		<?php
	}

	/**
	 * Ends the list of items after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 * @global int $comment_depth
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int    $depth  Optional. Depth of the current comment. Default 0.
	 * @param array  $args   Optional. Will only append content if style argument value is 'ol' or 'ul'.
	 *                       Default empty array.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['comment_depth'] = $depth + 2;
		?>
		</section>
		<?php
	}

	/**
	 * Starts the element output.
	 *
	 * @see Walker::start_el()
	 * @see wp_list_comments()
	 * @global int        $comment_depth
	 * @global WP_Comment $comment       Global comment object.
	 *
	 * @param string     $output  Used to append additional content. Passed by reference.
	 * @param WP_Comment $comment Comment data object.
	 * @param int        $depth   Optional. Depth of the current comment in reference to parents. Default 0.
	 * @param array      $args    Optional. An array of arguments. Default empty array.
	 * @param int        $id      Optional. ID of the current comment. Default 0 (unused).
	 */
	public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		++$depth;
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['comment_depth'] = $depth;
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['comment'] = $comment;
		$parent_class       = ( empty( $args['has_children'] ) ? '' : 'parent' );

		$tag       = 'article';
		$add_below = 'comment';

		?>

		<article <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
			<figure class="gravatar"><?php echo get_avatar( $comment, 65 ); ?></figure>

			<div class="comment-meta post-meta">
				<h2 class="comment-author">
					<a class="comment-author-link" href="<?php comment_author_url(); ?>"><?php comment_author(); ?></a>
				</h2>

				<time class="comment-meta-item" datetime="<?php comment_date( 'Y-m-d' ); ?>T<?php comment_time( 'H:iP' ); ?>"><?php comment_date( 'jS F Y' ); ?>, <a href="#comment-<?php comment_ID(); ?>"><?php comment_time(); ?></a></time>
				<?php edit_comment_link( '<p class="comment-meta-item">Edit this comment</p>', '', '' ); ?>
				<?php if ( 0 === intval( $comment->comment_approved ) ) : ?>
				<p class="comment-meta-item">Your comment is awaiting moderation.</p>
				<?php endif; ?>
			</div>

			<div class="comment-content post-content">
				<?php comment_text(); ?>
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => $add_below,
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
						)
					)
				);
				?>
			</div>

		<?php
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @see Walker::end_el()
	 * @see wp_list_comments()
	 *
	 * @param string     $output  Used to append additional content. Passed by reference.
	 * @param WP_Comment $comment The current comment object. Default current comment.
	 * @param int        $depth   Optional. Depth of the current comment. Default 0.
	 * @param array      $args    Optional. An array of arguments. Default empty array.
	 */
	public function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		?>
		</article>
		<?php
	}

	/**
	 * Closing tag for the comments list wrapper.
	 */
	public function __destruct() {
		?>
		</section>
		<?php
	}
}
