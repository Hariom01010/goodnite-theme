<?php
/**
 * Goodnite Single Product (PDP) helpers and data loader.
 *
 * @package Goodnite
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'gn_money' ) ) {
	/**
	 * Format Indian Rupee currency with standard comma separators.
	 *
	 * @param int $amount Amount in INR.
	 * @return string Formatted amount with rupee symbol.
	 */
	function gn_money( int $amount ): string {
		$digits = (string) $amount;
		if ( strlen( $digits ) <= 3 ) {
			return '₹' . $digits;
		}
		$tail = substr( $digits, -3 );
		$head = substr( $digits, 0, -3 );
		return '₹' . preg_replace( '/\B(?=(\d{2})+(?!\d))/', ',', $head ) . ',' . $tail;
	}
}

if ( ! function_exists( 'gn_variant_label' ) ) {
	/**
	 * Human-readable label for a mattress variant.
	 *
	 * @param array $variant Mattress variant item.
	 * @return string Formatted label.
	 */
	function gn_variant_label( array $variant ): string {
		return ( $variant['category'] ?? '' ) . ' — ' . ( $variant['length_in'] ?? '' ) . ' × ' . ( $variant['width_in'] ?? '' ) . ' in';
	}
}

if ( ! function_exists( 'gn_reference_price' ) ) {
	/**
	 * Reference price lookup.
	 *
	 * @param array $variant Variant data.
	 * @param bool  $with_pillow_top Pillow top flag.
	 * @return int Price in INR.
	 */
	function gn_reference_price( array $variant, bool $with_pillow_top ): int {
		return (int) ( $variant[ $with_pillow_top ? 'reference_with_pt_inr' : 'reference_without_pt_inr' ] ?? 0 );
	}
}

if ( ! function_exists( 'gn_display_price' ) ) {
	/**
	 * Display price calculator.
	 *
	 * @param array  $product Product array.
	 * @param string $variant_id Variant identifier.
	 * @param bool   $with_pillow_top Pillow top flag.
	 * @return int|null Computed price or null if unavailable.
	 */
	function gn_display_price( array $product, string $variant_id, bool $with_pillow_top ): ?int {
		if ( ! isset( $product['variants'][ $variant_id ] ) ) {
			return null;
		}
		if ( empty( $product['variant_prices_approved'] ) ) {
			return gn_reference_price( $product['variants'][ $variant_id ], $with_pillow_top );
		}
		$key    = $variant_id . ':' . ( $with_pillow_top ? 'with' : 'without' );
		$amount = $product['approved_prices_inr'][ $key ] ?? null;
		return is_int( $amount ) && $amount > 0 ? $amount : null;
	}
}

if ( ! function_exists( 'gn_validate_purchase' ) ) {
	/**
	 * Purchase validation.
	 *
	 * @param array  $product Product array.
	 * @param string $variant_id Variant ID.
	 * @param bool   $with_pillow_top Pillow top flag.
	 * @return array Validation result and optional line item.
	 */
	function gn_validate_purchase( array $product, string $variant_id, bool $with_pillow_top ): array {
		if ( ! isset( $product['variants'][ $variant_id ] ) ) {
			return array(
				'ok'    => false,
				'error' => __( 'Please select an available mattress size.', 'goodnite' ),
			);
		}
		$key      = $variant_id . ':' . ( $with_pillow_top ? 'with' : 'without' );
		$approved = $product['approved_prices_inr'][ $key ] ?? null;
		if ( empty( $product['variant_prices_approved'] ) || ! is_int( $approved ) || $approved <= 0 ) {
			return array(
				'ok'    => false,
				'error' => __( 'Pricing is not yet available for online checkout. Please contact Goodnite for assistance.', 'goodnite' ),
			);
		}
		return array(
			'ok'        => true,
			'line_item' => array(
				'product_id'      => $product['id'],
				'product_name'    => $product['name'],
				'variant_id'      => $variant_id,
				'length_in'       => $product['variants'][ $variant_id ]['length_in'],
				'width_in'        => $product['variants'][ $variant_id ]['width_in'],
				'with_pillow_top' => $with_pillow_top,
				'price_inr'       => $approved,
			),
		);
	}
}

if ( ! function_exists( 'gn_json_for_script' ) ) {
	/**
	 * Safe JSON encoder for inline script blocks.
	 *
	 * @param mixed $value Value to encode.
	 * @return string JSON string.
	 */
	function gn_json_for_script( $value ): string {
		return (string) json_encode( $value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE );
	}
}

/**
 * Get product configuration data array, enriched with SCF custom fields
 * and falling back to the reference prototype dataset.
 *
 * @param int|null $post_id Product post ID if in loop.
 * @return array Product data structure.
 */
function goodnite_get_product_data( ?int $post_id = null ): array {
	$data_file = get_template_directory() . '/inc/data/kayler.php';
	$product   = file_exists( $data_file ) ? require $data_file : array();

	if ( ! $post_id ) {
		$post_id = get_the_ID() ?: get_queried_object_id();
	}

	if ( $post_id && ( 'product' === get_post_type( $post_id ) || is_singular( 'product' ) || is_page() ) ) {
		// 1. Native WooCommerce Product Extraction.
		$wc_product = function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : null;
		if ( $wc_product ) {
			$product['id']   = (string) $wc_product->get_id();
			$product['name'] = $wc_product->get_name();

			$short_desc = $wc_product->get_short_description();
			if ( ! empty( $short_desc ) ) {
				$product['intro'] = wp_strip_all_tags( $short_desc );
			}

			// Gallery images.
			$image_id    = $wc_product->get_image_id();
			$gallery_ids = $wc_product->get_gallery_image_ids();
			if ( $image_id || ! empty( $gallery_ids ) ) {
				$new_gallery = array();
				if ( $image_id ) {
					$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
					$new_gallery[] = array(
						'src'      => wp_get_attachment_image_url( $image_id, 'full' ) ?: '',
						'alt'      => $alt ? (string) $alt : $wc_product->get_name(),
						'label'    => 'Full mattress view',
						'approved' => true,
						'is_full'  => true,
					);
				}
				foreach ( $gallery_ids as $idx => $gid ) {
					$alt = get_post_meta( $gid, '_wp_attachment_image_alt', true );
					$new_gallery[] = array(
						'src'      => wp_get_attachment_image_url( $gid, 'full' ) ?: '',
						'alt'      => $alt ? (string) $alt : $wc_product->get_name(),
						'label'    => 'View ' . ( $idx + 2 ),
						'approved' => true,
						'is_full'  => true,
					);
				}
				if ( ! empty( $new_gallery ) ) {
					$product['gallery'] = $new_gallery;
				}
			}

			// Collection category.
			$terms = get_the_terms( $post_id, 'product_cat' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$product['collection']     = $terms[0]->name;
				$term_link                 = get_term_link( $terms[0] );
				if ( ! is_wp_error( $term_link ) ) {
					$product['collection_url'] = $term_link;
				}
			}

			if ( $wc_product->is_purchasable() ) {
				$product['cart_connected'] = true;
			}
		}

		// 2. Secure Custom Fields (SCF) Dynamic Enrichment.
		if ( function_exists( 'get_field' ) ) {
			// Overview / Tagline.
			$tagline = get_field( 'product_tagline', $post_id );
			if ( ! empty( $tagline ) ) {
				$product['intro'] = (string) $tagline;
			}

			// Comfort Feel.
			$comfort_pos = get_field( 'comfort_feel_position', $post_id );
			if ( null !== $comfort_pos && '' !== $comfort_pos ) {
				$product['comfort']['position'] = (int) $comfort_pos;
			}
			$comfort_notes = get_field( 'comfort_feel_notes', $post_id );
			if ( ! empty( $comfort_notes ) ) {
				$product['comfort']['notes'] = (string) $comfort_notes;
			}

			// Feature Pills repeater.
			$pills = get_field( 'pdp_feature_pills', $post_id );
			if ( ! empty( $pills ) && is_array( $pills ) ) {
				$preset_icons = array(
					'core-support'       => 'PDP Icons/Core Support.png',
					'natural-freshness'  => 'PDP Icons/Natural Freshness.png',
					'quilted-finish'     => 'PDP Icons/Quilted Finish.png',
					'responsive-comfort' => 'PDP Icons/Responsive Comfort.png',
				);
				$parsed_pills = array();
				foreach ( $pills as $pill ) {
					$label  = ! empty( $pill['pill_label'] ) ? (string) $pill['pill_label'] : '';
					$preset = ! empty( $pill['pill_preset'] ) ? (string) $pill['pill_preset'] : 'core-support';
					$icon   = '';
					if ( ! empty( $pill['pill_icon'] ) ) {
						if ( is_array( $pill['pill_icon'] ) && ! empty( $pill['pill_icon']['url'] ) ) {
							$icon = $pill['pill_icon']['url'];
						} elseif ( is_numeric( $pill['pill_icon'] ) ) {
							$icon = wp_get_attachment_image_url( (int) $pill['pill_icon'], 'thumbnail' ) ?: '';
						} elseif ( is_string( $pill['pill_icon'] ) ) {
							$icon = $pill['pill_icon'];
						}
					}
					if ( empty( $icon ) && isset( $preset_icons[ $preset ] ) ) {
						$icon = $preset_icons[ $preset ];
					}
					if ( $label ) {
						$parsed_pills[] = array(
							'icon'   => $icon,
							'preset' => $preset,
							'label'  => $label,
						);
					}
				}
				if ( ! empty( $parsed_pills ) ) {
					$product['feature_pills'] = $parsed_pills;
				}
			}

			// Construction Section.
			$const_title = get_field( 'construction_title', $post_id );
			if ( ! empty( $const_title ) ) {
				$product['construction']['title'] = (string) $const_title;
			}
			$const_img = get_field( 'construction_image', $post_id );
			if ( ! empty( $const_img ) ) {
				if ( is_array( $const_img ) && ! empty( $const_img['url'] ) ) {
					$product['construction']['image'] = $const_img['url'];
				} elseif ( is_numeric( $const_img ) ) {
					$product['construction']['image'] = wp_get_attachment_image_url( (int) $const_img, 'full' ) ?: $product['construction']['image'];
				} elseif ( is_string( $const_img ) ) {
					$product['construction']['image'] = $const_img;
				}
			}
			$const_layers = get_field( 'construction_layers', $post_id );
			if ( ! empty( $const_layers ) && is_array( $const_layers ) ) {
				$parsed_layers = array();
				foreach ( $const_layers as $layer ) {
					$step  = ! empty( $layer['layer_step'] ) ? (string) $layer['layer_step'] : '';
					$title = ! empty( $layer['layer_title'] ) ? (string) $layer['layer_title'] : '';
					$desc  = ! empty( $layer['layer_description'] ) ? (string) $layer['layer_description'] : '';
					if ( $title ) {
						$parsed_layers[] = array(
							'step'        => $step,
							'title'       => $title,
							'description' => $desc,
						);
					}
				}
				if ( ! empty( $parsed_layers ) ) {
					$product['construction']['layers'] = $parsed_layers;
				}
			}

			// Technologies Section.
			$tech_title = get_field( 'tech_section_title', $post_id );
			if ( ! empty( $tech_title ) ) {
				$product['tech_section_title'] = (string) $tech_title;
			}
			$tech_list = get_field( 'technologies_list', $post_id );
			if ( ! empty( $tech_list ) && is_array( $tech_list ) ) {
				$parsed_tech = array();
				foreach ( $tech_list as $tech ) {
					$tech_name = ! empty( $tech['tech_name'] ) ? (string) $tech['tech_name'] : '';
					$video_url = '';
					if ( ! empty( $tech['tech_video'] ) ) {
						if ( is_array( $tech['tech_video'] ) && ! empty( $tech['tech_video']['url'] ) ) {
							$video_url = $tech['tech_video']['url'];
						} elseif ( is_string( $tech['tech_video'] ) ) {
							$video_url = $tech['tech_video'];
						} elseif ( is_numeric( $tech['tech_video'] ) ) {
							$video_url = wp_get_attachment_url( (int) $tech['tech_video'] ) ?: '';
						}
					}
					$poster_url = '';
					if ( ! empty( $tech['tech_poster'] ) ) {
						if ( is_array( $tech['tech_poster'] ) && ! empty( $tech['tech_poster']['url'] ) ) {
							$poster_url = $tech['tech_poster']['url'];
						} elseif ( is_string( $tech['tech_poster'] ) ) {
							$poster_url = $tech['tech_poster'];
						} elseif ( is_numeric( $tech['tech_poster'] ) ) {
							$poster_url = wp_get_attachment_image_url( (int) $tech['tech_poster'], 'full' ) ?: '';
						}
					}
					if ( $tech_name ) {
						$parsed_tech[] = array(
							'name'    => $tech_name,
							'tagline' => ! empty( $tech['tech_tagline'] ) ? (string) $tech['tech_tagline'] : '',
							'video'   => $video_url,
							'poster'  => $poster_url,
							'has_tm'  => in_array( $tech_name, array( 'CloudFlex', 'SupportCore' ), true ),
						);
					}
				}
				if ( ! empty( $parsed_tech ) ) {
					$product['technologies'] = $parsed_tech;
				}
			}

			// Specifications, Care, and Shipping accordions.
			$spec_mat = get_field( 'spec_materials', $post_id );
			if ( ! empty( $spec_mat ) ) {
				$product['specs']['materials'] = (string) $spec_mat;
			}
			$care_inst = get_field( 'care_instructions', $post_id );
			if ( ! empty( $care_inst ) ) {
				$product['specs']['care'] = (string) $care_inst;
			}
			$shipping = get_field( 'shipping_return_policy', $post_id );
			if ( ! empty( $shipping ) ) {
				$product['specs']['shipping'] = (string) $shipping;
			}

			// Custom Size calculator parameters.
			$rate_wopt = get_field( 'rate_without_pt', $post_id );
			if ( null !== $rate_wopt && '' !== $rate_wopt ) {
				$product['custom']['rate_without_pt'] = (float) $rate_wopt;
			}
			$rate_wpt = get_field( 'rate_with_pt', $post_id );
			if ( null !== $rate_wpt && '' !== $rate_wpt ) {
				$product['custom']['rate_with_pt'] = (float) $rate_wpt;
			}
			$max_dim = get_field( 'preview_max_dimension', $post_id );
			if ( null !== $max_dim && '' !== $max_dim ) {
				$product['custom']['preview_max_dimension_in'] = (int) $max_dim;
			}
			$pillow_top = get_field( 'pillow_top_explanation', $post_id );
			if ( ! empty( $pillow_top ) ) {
				$product['pillow_top_explanation'] = (string) $pillow_top;
			}

			// Product FAQs.
			$faqs = get_field( 'product_faqs', $post_id );
			if ( ! empty( $faqs ) && is_array( $faqs ) ) {
				$parsed_faqs = array();
				foreach ( $faqs as $faq ) {
					$q = ! empty( $faq['faq_question'] ) ? (string) $faq['faq_question'] : '';
					$a = ! empty( $faq['faq_answer'] ) ? (string) $faq['faq_answer'] : '';
					if ( $q && $a ) {
						$parsed_faqs[] = array(
							'question' => $q,
							'answer'   => $a,
						);
					}
				}
				if ( ! empty( $parsed_faqs ) ) {
					$product['faqs'] = $parsed_faqs;
				}
			}

			// Recommended Mattresses.
			$recs = get_field( 'recommended_products', $post_id );
			if ( ! empty( $recs ) && is_array( $recs ) ) {
				$parsed_recs = array();
				foreach ( $recs as $rec ) {
					$rec_id = is_object( $rec ) ? $rec->ID : (int) $rec;
					if ( $rec_id ) {
						$rec_post = get_post( $rec_id );
						if ( $rec_post ) {
							$terms    = get_the_terms( $rec_id, 'product_cat' );
							$col_name = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'Collection';
							$thumb_id = get_post_thumbnail_id( $rec_id );
							$img_url  = $thumb_id ? ( wp_get_attachment_image_url( $thumb_id, 'medium' ) ?: '' ) : '';
							$parsed_recs[] = array(
								'name'       => get_the_title( $rec_id ),
								'collection' => $col_name,
								'url'        => get_permalink( $rec_id ),
								'image'      => $img_url,
								'alt'        => get_the_title( $rec_id ),
								'is_url'     => true,
							);
						}
					}
				}
				if ( ! empty( $parsed_recs ) ) {
					$product['recommendations'] = $parsed_recs;
				}
			}
		}
	}

	return $product;
}

/**
 * Get contact phone/whatsapp settings.
 *
 * @return array
 */
function goodnite_get_contact_data(): array {
	$contact_file = get_template_directory() . '/inc/data/contact.php';
	if ( file_exists( $contact_file ) ) {
		return require $contact_file;
	}
	return array(
		'whatsapp' => null,
		'phone'    => null,
	);
}
