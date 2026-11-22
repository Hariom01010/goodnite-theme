<?php
/**
 * Collection page routing and URL rewrite rules.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register query vars for collection filtering.
 *
 * @param array $vars Public query vars.
 * @return array
 */
function goodnite_register_query_vars( array $vars ): array {
	$vars[] = 'gn_collection';
	return $vars;
}
add_filter( 'query_vars', 'goodnite_register_query_vars' );

/**
 * Register rewrite rules for collection and mattresses routes.
 */
function goodnite_register_rewrites(): void {
	add_rewrite_rule( '^(?:mattresses|collection)/?$', 'index.php?gn_collection=all', 'top' );
	add_rewrite_rule( '^(?:mattresses|collection)/([^/]+)/?$', 'index.php?gn_collection=$matches[1]', 'top' );
}
add_action( 'init', 'goodnite_register_rewrites' );

/**
 * Determine the requested collection slug from query vars or request URI.
 *
 * @return string
 */
function goodnite_get_current_collection_slug(): string {
	$var = get_query_var( 'gn_collection' );
	if ( ! empty( $var ) ) {
		return strtolower( sanitize_title( (string) $var ) );
	}

	if ( isset( $_GET['collection'] ) && '' !== trim( (string) $_GET['collection'] ) ) {
		return strtolower( sanitize_title( (string) $_GET['collection'] ) );
	}

	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && ! empty( $term->slug ) ) {
			return strtolower( sanitize_title( $term->slug ) );
		}
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return 'all';
	}

	$uri_path = trim( (string) ( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ?: '' ), '/' );
	$home_path = trim( (string) ( parse_url( home_url(), PHP_URL_PATH ) ?: '' ), '/' );

	if ( '' !== $home_path && 0 === strpos( $uri_path, $home_path ) ) {
		$uri_path = trim( substr( $uri_path, strlen( $home_path ) ), '/' );
	}

	if ( preg_match( '#^(?:mattresses|collection)(?:/([^/]+))?/?$#i', $uri_path, $matches ) ) {
		return ! empty( $matches[1] ) ? strtolower( sanitize_title( $matches[1] ) ) : 'all';
	}

	return 'all';
}

/**
 * Determine whether the current request is for a collection page.
 *
 * @return bool
 */
function goodnite_is_collection_page(): bool {
	if ( ! empty( get_query_var( 'gn_collection' ) ) ) {
		return true;
	}

	if ( is_page_template( 'page-collection.php' ) ) {
		return true;
	}

	if ( is_page( array( 'mattresses', 'collection' ) ) ) {
		return true;
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return true;
	}

	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		return true;
	}

	if ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
		return true;
	}

	$uri_path = trim( (string) ( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ?: '' ), '/' );
	$home_path = trim( (string) ( parse_url( home_url(), PHP_URL_PATH ) ?: '' ), '/' );
	if ( '' !== $home_path && 0 === strpos( $uri_path, $home_path ) ) {
		$uri_path = trim( substr( $uri_path, strlen( $home_path ) ), '/' );
	}

	return (bool) preg_match( '#^(?:mattresses|collection)(?:/[^/]+)?/?$#i', $uri_path );
}

/**
 * Template loader filter to route collection requests to page-collection.php.
 *
 * @param string $template Matched template file.
 * @return string
 */
function goodnite_collection_template_include( string $template ): string {
	$uri_path  = trim( (string) ( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ?: '' ), '/' );
	$home_path = trim( (string) ( parse_url( home_url(), PHP_URL_PATH ) ?: '' ), '/' );
	if ( '' !== $home_path && 0 === strpos( $uri_path, $home_path ) ) {
		$uri_path = trim( substr( $uri_path, strlen( $home_path ) ), '/' );
	}

	if ( preg_match( '#^find-your-mattress/?$#i', $uri_path ) ) {
		$finder_template = locate_template( array( 'page-find-your-mattress.php' ) );
		if ( $finder_template ) {
			return $finder_template;
		}
	}

	if ( goodnite_is_collection_page() ) {
		$col_template = locate_template( array( 'page-collection.php' ) );
		if ( $col_template ) {
			return $col_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'goodnite_collection_template_include', 99 );

/**
 * Set document title for collection pages.
 *
 * @param array $title Parts of the document title.
 * @return array
 */
function goodnite_collection_document_title_parts( array $title ): array {
	if ( goodnite_is_collection_page() ) {
		$slug        = goodnite_get_current_collection_slug();
		$collections = function_exists( 'goodnite_get_collections' ) ? goodnite_get_collections() : array();
		if ( isset( $collections[ $slug ]['title'] ) ) {
			$title['title'] = $collections[ $slug ]['title'];
		}
	}
	return $title;
}
add_filter( 'document_title_parts', 'goodnite_collection_document_title_parts' );

