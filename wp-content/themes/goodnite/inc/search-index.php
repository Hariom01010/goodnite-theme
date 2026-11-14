<?php
/**
 * Search index generator and query resolver.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Compile the global search index for products, collections, technologies and pages.
 *
 * @return array
 */
function goodnite_search_index(): array {
	static $index = null;
	if ( null !== $index ) {
		return $index;
	}

	$index = array();

	// 1. Products.
	if ( function_exists( 'goodnite_get_collections' ) ) {
		$all_col = goodnite_get_collections();
		$raw_products = $all_col['all']['products'] ?? array();
		$seen = array();
		foreach ( $raw_products as $product ) {
			if ( isset( $seen[ $product['name'] ] ) ) {
				continue;
			}
			$seen[ $product['name'] ] = true;
			$index[] = array(
				'id'          => 'product-' . strtolower( (string) preg_replace( '/[^a-z0-9]+/i', '-', $product['name'] ) ),
				'type'        => 'product',
				'title'       => $product['name'],
				'url'         => $product['url'],
				'description' => $product['collection'] . ' · ' . $product['descriptor'] . ' · ₹' . number_format( (int) $product['price'] ),
				'keywords'    => array( $product['collection'], $product['descriptor'], $product['comfort'], $product['material'], 'mattress', 'sleep', 'comfort' ),
				'aliases'     => array(),
				'thumbnail'   => goodnite_asset_url( 'images/' . $product['image'] ),
				'status'      => 'published',
			);
		}
	} else {
		foreach ( goodnite_product_cards() as $product ) {
			$index[] = array(
				'id'          => 'product-' . strtolower( (string) preg_replace( '/[^a-z0-9]+/i', '-', $product['name'] ) ),
				'type'        => 'product',
				'title'       => $product['name'],
				'url'         => $product['url'],
				'description' => $product['collection'] . ' · ' . $product['technology'] . ' · ' . $product['price'],
				'keywords'    => array( $product['collection'], $product['technology'], 'mattress', 'sleep', 'comfort' ),
				'aliases'     => array(),
				'thumbnail'   => goodnite_asset_url( 'images/' . $product['image'] ),
				'status'      => 'published',
			);
		}
	}

	// 2. Collections.
	$index[] = array(
		'id'          => 'collection-all',
		'type'        => 'collection',
		'title'       => 'All Mattresses',
		'url'         => '/mattresses',
		'description' => 'The Complete Goodnite Collection',
		'keywords'    => array( 'All Mattresses', 'The Goodnite Collection', 'Shop All', 'Dreamer', 'Love', 'Signature' ),
		'aliases'     => array( 'Mattresses' ),
		'thumbnail'   => goodnite_asset_url( 'images/collection-hero-desktop-v2.png' ),
		'status'      => 'published',
	);

	foreach ( goodnite_collection_cards() as $collection ) {
		$index[] = array(
			'id'          => 'collection-' . $collection['id'],
			'type'        => 'collection',
			'title'       => $collection['title'],
			'url'         => $collection['url'],
			'description' => $collection['subtitle'],
			'keywords'    => array( $collection['title'], $collection['subtitle'], 'mattresses', 'series' ),
			'aliases'     => array(),
			'thumbnail'   => goodnite_asset_url( 'images/' . $collection['image'] ),
			'status'      => 'published',
		);
	}

	// 3. Technologies.
	$technologies = array(
		array(
			'title'       => 'IceSleep™',
			'description' => 'Cooling fabric technology for active heat dissipation',
			'keywords'    => array( 'cooling', 'cool', 'fabric', 'icesleep', 'temperature' ),
			'image'       => 'goodnite-technology-fabric-v2.png',
		),
		array(
			'title'       => 'StatFree®',
			'description' => 'Anti-static carbon-thread yarn technology',
			'keywords'    => array( 'anti-static', 'static', 'fabric', 'statfree', 'stress' ),
			'image'       => 'goodnite-technology-fabric-v2.png',
		),
		array(
			'title'       => 'Double Posture Coil® (DPC®)',
			'description' => 'Dual-stage posture-focused coil support system',
			'keywords'    => array( 'dpc', 'coil', 'spring', 'posture', 'support', 'back' ),
			'image'       => 'goodnite-technology-coil-v2.png',
		),
		array(
			'title'       => 'Pocketed Spring',
			'description' => 'Independently responsive springs with motion isolation',
			'keywords'    => array( 'pocket spring', 'pocketed', 'springs', 'motion isolation', 'coil' ),
			'image'       => 'goodnite-technology-coil-v2.png',
		),
		array(
			'title'       => 'Natural Latex',
			'description' => 'Naturally breathable and contouring comfort core',
			'keywords'    => array( 'latex', 'natural', 'organic', 'breathable', 'comfort' ),
			'image'       => 'goodnite-technology-material-v2.png',
		),
		array(
			'title'       => 'Memory Foam',
			'description' => 'Adaptive pressure-relieving body contouring foam',
			'keywords'    => array( 'memory foam', 'foam', 'pressure relief', 'contouring' ),
			'image'       => 'goodnite-technology-material-v2.png',
		),
	);

	foreach ( $technologies as $tech ) {
		$index[] = array(
			'id'          => 'tech-' . strtolower( (string) preg_replace( '/[^a-z0-9]+/i', '-', $tech['title'] ) ),
			'type'        => 'technology',
			'title'       => $tech['title'],
			'url'         => '/#technology-heading',
			'description' => $tech['description'],
			'keywords'    => $tech['keywords'],
			'aliases'     => array(),
			'thumbnail'   => goodnite_asset_url( 'images/' . $tech['image'] ),
			'status'      => 'published',
		);
	}

	return $index;
}

/**
 * Normalize search strings for accurate matching.
 *
 * @param string $value Query or text token.
 * @return string
 */
function goodnite_search_normalize( string $value ): string {
	$value = strtolower( trim( $value ) );
	$value = (string) preg_replace( '/[®™]+/u', '', $value );
	$value = (string) preg_replace( '/\s+/', ' ', $value );
	return trim( $value );
}

/**
 * Filter and score search results from the index.
 *
 * @param string $query User search term.
 * @param string $type Optional type filter ('all', 'product', 'collection', 'technology', 'article').
 * @return array Ranked results.
 */
function goodnite_search_results( string $query, string $type = 'all' ): array {
	$needle = goodnite_search_normalize( $query );
	if ( '' === $needle ) {
		return array();
	}

	$terms   = preg_split( '/\s+/', $needle, -1, PREG_SPLIT_NO_EMPTY ) ?: array();
	$results = array();

	foreach ( goodnite_search_index() as $item ) {
		if ( 'all' !== $type && $item['type'] !== $type ) {
			continue;
		}

		$title    = goodnite_search_normalize( $item['title'] );
		$haystack = goodnite_search_normalize(
			implode( ' ', array_merge( array( $item['description'] ), $item['keywords'], $item['aliases'] ) )
		);

		$score = 0;
		if ( $title === $needle ) {
			$score = 1000;
		} elseif ( 0 === strpos( $title, $needle ) ) {
			$score = 850;
		} elseif ( false !== strpos( $title, $needle ) ) {
			$score = 700;
		} elseif ( false !== strpos( $haystack, $needle ) ) {
			$score = 450;
		} else {
			foreach ( $terms as $term ) {
				if ( strlen( $term ) > 1 && ( false !== strpos( $title, $term ) || false !== strpos( $haystack, $term ) ) ) {
					$score += 100;
				}
			}
		}

		if ( $score > 0 ) {
			$item['_score'] = $score;
			$results[]      = $item;
		}
	}

	usort(
		$results,
		static function ( array $a, array $b ): int {
			return ( $b['_score'] <=> $a['_score'] ) ?: strcmp( $a['title'], $b['title'] );
		}
	);

	foreach ( $results as &$item ) {
		unset( $item['_score'] );
	}

	return $results;
}
