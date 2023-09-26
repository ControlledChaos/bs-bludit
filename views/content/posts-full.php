<?php
/**
 * Posts page full content template
 *
 * Used for posts loop, whether on the
 * home page or blog page when a static
 * home page is used.
 *
 * Theme config 'posts' > 'loop'
 * must be set to 'full' to use this.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	blog_data
};
use function BSB_Tags\{
	posts_loop_header,
	sticky_icon,
	page_description,
	has_tags,
	get_author
};

// Get blog data.
$blog_data = blog_data();

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

echo posts_loop_header();

// If posts, print for each.
foreach ( $content as $post ) :

// Maybe a sticky icon.
$sticky = '';
if ( $post->sticky() ) {
	$sticky = sprintf(
		'%s ',
		sticky_icon(
			'false',
			'sticky-icon-heading',
			$L->get( 'Post is sticky' )
		)
	);
}

// Tags list.
$tags_list = function() use ( $post ) {

	$tags  = $post->tags( true );
	$links = [];
	$sep   = ' ';

	if ( $post->tags( true ) ) {
		$html = '<ul class="inline-list tags-list">';
		foreach ( $tags as $tagKey => $tagName ) {

			$links[] = sprintf(
				'<li><a href="%s" class="tag-list-entry" rel="tag">%s</a></li>',
				DOMAIN_TAGS . $tagKey,
				$tagName
			);
		}
		$html .= implode( $sep, $links );
		$html .= '</ul>';

		return $html;
	}
	return '';
};

?>
<article class="site-article" role="article" data-site-article>

	<header class="page-header posts-loop-header" data-post-header>
		<h2 class="page-title posts-loop-title">
			<a href="<?php echo $post->permalink(); ?>"><?php echo $post->title(); ?></a>
		</h2>
		<?php if ( $post->description() ) {
			printf(
				'<p class="page-description post-loop-description">%s</p>',
				$post->description()
			);
		} ?>
	</header>

	<?php if ( $post->coverImage() ) : ?>
	<figure class="page-cover posts-loop-cover">
		<a href="<?php echo $post->permalink(); ?>">
			<img src="<?php echo $post->coverImage(); ?>" loading="lazy" />
		</a>
		<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
	</figure>
	<?php endif; ?>

	<footer class="page-info">
		<p>
			<?php if ( THEME_CONFIG['posts']['byline'] ) : ?>
			<span class="page-info-entry page-info-author">
				<?php echo get_author(); ?>
			</span>
			<?php endif ?>

			<?php if ( THEME_CONFIG['posts']['post_date'] ) : ?>
			<span class="page-info-entry page-info-date">
				<?php echo $post->date(); ?>
			</span>
			<br />
			<?php endif ?>

			<?php if ( $post->tags( true ) ) : ?>
			<span class="page-info-entry page-info-tags">
				<?php echo $tags_list(); ?>
			</span>
			<?php endif ?>
		</p>
	</footer>

	<div class="post-content" itemprop="articleBody" data-post-content>
		<?php echo $post->content(); ?>
	</div>
</article>
<?php endforeach; ?>

<?php

// Get page navigation.
if ( 'numerical' == THEME_CONFIG['posts']['paged'] ) {
	include( THEME_DIR . 'views/navigation/paged-numerical.php' );
} else {
	include( THEME_DIR . 'views/navigation/paged-prev-next.php' );
}
