<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package yabwt
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function yabwt_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
				$classes[] = 'group-blog';
		}

		return $classes;
}
add_filter( 'body_class', 'yabwt_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
		/**
		 * Filters wp_title to print a neat <title> tag based on what is being viewed.
		 *
		 * @param string $title Default title text for current view.
		 * @param string $sep Optional separator.
		 * @return string The filtered title.
		 */
		function yabwt_wp_title( $title, $sep ) {
				if ( is_feed() ) {
						return $title;
				}

				global $page, $paged;

				// Add the blog name
				$title .= get_bloginfo( 'name', 'display' );

				// Add the blog description for the home/front page.
				$site_description = get_bloginfo( 'description', 'display' );
				if ( $site_description && ( is_home() || is_front_page() ) ) {
						$title .= " $sep $site_description";
				}

				// Add a page number if necessary:
				if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
						$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'yabwt' ), max( $paged, $page ) );
				}

				return $title;
		}
add_filter( 'wp_title', 'yabwt_wp_title', 10, 2 );

/**
 * Title shim for sites older than WordPress 4.1.
 *
 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
 * @todo Remove this function when WordPress 4.3 is released.
 */
function yabwt_render_title() {
		?>
				<title><?php wp_title( '|', true, 'right' ); ?></title>
				<?php
}
add_action( 'wp_head', 'yabwt_render_title' );
endif;

function yabwt_get_post_icon() {
		$icon = get_post_format();
		if ( !$icon ) {
				return "<i class=\"fa fa-file-text\"></i>";
		}
		elseif ( $icon === "video" ) {
				return "<i class=\"fa fa-video-camera\"></i>";
		}
		elseif ( $icon === "link" ) {
				return "<i class=\"fa fa-link\"></i>";
		}
		elseif ( $icon === "image" ) {
				return "<i class=\"fa fa-picture-o\"></i>";
		}
		elseif ( $icon === "quote" ) {
				return "<i class=\"fa fa-quote-right\"></i>";
		}
		elseif( $icon === "aside" ) {
				//return "<i class=\"fa fa-pencil-square\"></i>";
				return "";
		}

}

/**
 * Disable the emoji's
 */
function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );	
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param    array  $plugins  
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
				return array();
		}
}

$args = array(
				'width'         => 1070,
				'height'        => 160,
				'default-image' => get_template_directory_uri() . '/images/logo.jpg',
				'uploads'       => true,
			 );
add_theme_support( 'custom-header', $args );

function theme_header_dynamic_style() {
		?>
				<style type="text/css">
#masthead .site-branding {
		background: url('<?php header_image();?>');
		background-size: cover;
		background-position: center;
		min-height: 160px;
}
</style>
<?php
}
add_action('wp_head', 'theme_header_dynamic_style');
