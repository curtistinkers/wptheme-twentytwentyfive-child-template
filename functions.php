<?php
/**
 * %pretty-name% functions and definitions
 *
 * This file contains the functions and definitions for the %pretty-name% WordPress child theme.
 *
 * @package %pascal-case%
 * @since %pretty-name% 0.1.0
 */

declare(strict_types=1);

if ( ! function_exists( '%text-domain%_get_primary_site_name' ) ) :

	/**
	 * Get the primary site name
	 *
	 * This function retrieves the name of the primary site in a multisite network.
	 *
	 * @since %pretty-name% 0.1.0
	 *
	 * @return string The primary site name or the current site name if not in a multisite.
	 */
	function %text-domain%_get_primary_site_name(): string {

		if ( ! function_exists( 'get_site' ) ) {
			$site_name = get_bloginfo( 'name' );
		} else {
			$site = get_site( 1 );
			if ( ! is_object( $site ) ) {
				$site_name = get_bloginfo( 'name' );
			} else {
				$site_name = $site->blogname;
			}
		}

		return $site_name;
	}
endif;

// Enqueues the theme stylesheet on the front-end.
add_action( 'wp_enqueue_scripts', '%text-domain%_enqueue_styles' );

if ( ! function_exists( '%text-domain%_enqueue_styles' ) ) :

	/**
	 * Enqueues the theme stylesheet for %pretty-name% and the parent Twenty Twenty-Five theme.
	 *
	 * @since %pretty-name% 0.1.0
	 *
	 * @return void
	 */
	function %text-domain%_enqueue_styles(): void {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);

		wp_enqueue_style(
			'%text-domain%-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( 'twentytwentyfive-style' ),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;

// Unregister Twenty Twenty-Five block patterns that are overridden by %pretty-name%.
add_action( 'init', '%text-domain%_unregister_twentytwentyfive_block_patterns' );

if ( ! function_exists( '%text-domain%_unregister_twentytwentyfive_block_patterns' ) ) :

	/**
	 * Unregister Twenty Twenty-Five block patterns that are overridden by %pretty-name%.
	 *
	 * @since %pretty-name% 0.1.0
	 *
	 * @return void
	 */
	function %text-domain%_unregister_twentytwentyfive_block_patterns(): void {
		// unregister_block_pattern( 'twentytwentyfive/footer' );
	}
endif;

// Add custom template types for %pretty-name%.
add_filter( 'default_template_types', '%text-domain%_add_custom_template_types', 10, 1 );

if ( ! function_exists( '%text-domain%_add_custom_template_types' ) ) :

	/**
	 * Add custom template types for %pretty-name%.
	 *
	 * @since %pretty-name% 0.1.0
	 *
	 * @param array<array<string, string>> $templates The default template types.
	 *
	 * @return array<array<string, string>> The modified template types.
	 */
	function %text-domain%_add_custom_template_types( array $templates ): array {

		// $templates['page-example'] = array(
		// 	'title'       => __( 'Example page', '%text-domain%' ),
		// 	'description' => __( 'Used as an example custom page.', '%text-domain%' ),
		// );

		return $templates;
	}
endif;

// Render shortcodes in block content.
add_filter( 'render_block', '%text-domain%_render_shortcodes_in_blocks', 10, 1 );

if ( ! function_exists( '%text-domain%_render_shortcodes_in_blocks' ) ) :

	/**
	 * Render shortcodes in block content.
	 *
	 * @since %pretty-name% 0.1.0
	 *
	 * @param string $block_content The block content.
	 *
	 * @return string The block content with shortcodes rendered.
	 */
	function %text-domain%_render_shortcodes_in_blocks( string $block_content ): string {

		return do_shortcode( $block_content );
	}
endif;

// Register %pretty-name% block pattern categories.
add_action( 'init', '%text-domain%_register_pattern_categories' );

if ( ! function_exists( '%text-domain%_register_pattern_categories' ) ) :

	/**
	 * Register %pretty-name% block pattern categories.
	 *
	 * @since %pretty-name% 0.1.0
	 *
	 * @return void
	 */
	function %text-domain%_register_pattern_categories(): void {

		register_block_pattern_category(
			'%text-domain%/custom',
			array(
				'label'       => __( '%pretty-name%: Custom', '%text-domain%' ),
				'description' => __( 'Custom patterns for %pretty-name%.', '%text-domain%' ),
			)
		);
	}
endif;
