<?php
/**
 * Entire RCM child theme.
 *
 * The pages are a faithful reproduction of the exported Stitch design, so the
 * front end loads the same compiled Tailwind utilities the design was authored
 * against. This file supplies what blocks cannot: the asset bundle, the body
 * classes, three shortcodes for the pieces the design renders as inline SVG,
 * icon fonts and scripted widgets, and the search/social metadata.
 *
 * @package Entire_RCM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ENTIRE_RCM_VERSION', '2.0.0' );

/**
 * Cache-busting version for a theme asset.
 */
function entire_rcm_asset_version( $relative ) {
	$path = get_stylesheet_directory() . '/' . ltrim( $relative, '/' );
	return file_exists( $path ) ? (string) filemtime( $path ) : ENTIRE_RCM_VERSION;
}

/**
 * Front-end assets.
 */
function entire_rcm_enqueue_assets() {
	$uri = get_stylesheet_directory_uri();

	// The design's three font/icon requests, loaded exactly as it loads them.
	wp_enqueue_style(
		'entire-rcm-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&family=Inter:wght@100..900&display=swap',
		array(),
		null
	);

	// Material Symbols, exactly as the design loads it.
	wp_enqueue_style(
		'material-symbols',
		'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
		array( 'entire-rcm-fonts' ),
		null
	);

	wp_enqueue_style( 'entire-rcm-tailwind', $uri . '/assets/css/tailwind.css', array(), entire_rcm_asset_version( 'assets/css/tailwind.css' ) );
	wp_enqueue_style( 'entire-rcm', $uri . '/assets/css/rcm.css', array( 'entire-rcm-tailwind' ), entire_rcm_asset_version( 'assets/css/rcm.css' ) );
	wp_enqueue_script( 'entire-rcm', $uri . '/assets/js/rcm.js', array(), entire_rcm_asset_version( 'assets/js/rcm.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'entire_rcm_enqueue_assets', 20 );

/**
 * The design's <body> classes, so the page chrome matches it exactly.
 */
function entire_rcm_body_class( $classes ) {
	$classes[] = 'bg-background';
	$classes[] = 'text-on-surface';
	$classes[] = 'font-body-md';
	$classes[] = 'antialiased';
	return $classes;
}
add_filter( 'body_class', 'entire_rcm_body_class' );

/**
 * Drop WordPress's own block styling: every class in the design is a compiled
 * Tailwind utility, and the parent theme's merged theme.json styles (Manrope
 * body copy at 36px/300, root padding, preset sizes) would win over the design's
 * utilities on anything that inherits. The block contents carry their own
 * typography, so nothing here depends on the global stylesheet.
 */
function entire_rcm_dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'entire_rcm_dequeue_block_styles', 100 );

/* -------------------------------------------------------------------------
 * Shortcodes for the things the design renders that blocks cannot hold.
 * ---------------------------------------------------------------------- */

/**
 * Material Symbols icon: [ercm_icon name="bolt" class="text-[16px]"].
 */
function entire_rcm_icon_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'  => 'check',
			'class' => '',
		),
		$atts,
		'ercm_icon'
	);

	return sprintf(
		'<span class="material-symbols-outlined %s" aria-hidden="true">%s</span>',
		esc_attr( $atts['class'] ),
		esc_html( $atts['name'] )
	);
}
add_shortcode( 'ercm_icon', 'entire_rcm_icon_shortcode' );

/**
 * The collections-velocity chart: the design's inline SVG, kept intact.
 */
function entire_rcm_chart_shortcode() {
	return '<svg class="w-full h-24 text-secondary" fill="none" preserveAspectRatio="none" viewBox="0 0 600 100">'
		. '<path d="M0,80 Q75,70 150,55 T300,35 T450,20 T600,8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="3"></path>'
		. '<path d="M0,80 Q75,70 150,55 T300,35 T450,20 T600,8 L600,100 L0,100 Z" fill="currentColor" fill-opacity="0.08"></path>'
		. '<path d="M0,85 Q150,88 300,82 T600,75" fill="none" stroke="#94a3b8" stroke-dasharray="4 4" stroke-width="2"></path>'
		. '</svg>';
}
add_shortcode( 'ercm_chart', 'entire_rcm_chart_shortcode' );

/**
 * The ROI calculator widget: [ercm_calculator].
 *
 * Markup, ids and figures mirror the design's calculator card so rcm.js drives
 * it exactly as designed. Each range is attribute-driven so it can be re-tuned
 * from the editor without touching the theme.
 */
function entire_rcm_calculator_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'volume'      => '350000',
			'volume_min'  => '50000',
			'volume_max'  => '2000000',
			'volume_step' => '25000',
			'denial'      => '18',
			'ar'          => '48',
		),
		$atts,
		'entire_rcm_calculator'
	);

	ob_start();
	?>
	<div class="bg-surface-container-lowest rounded-xl p-space-lg text-on-surface shadow-2xl">
		<div class="space-y-5">
			<div>
				<div class="flex justify-between items-center mb-1">
					<span class="font-label-md text-label-md text-on-surface">Monthly Billed Charges:</span>
					<span class="font-data-metric text-headline-md text-primary font-bold" id="calc-volume-text">$350,000</span>
				</div>
				<input class="w-full h-2 bg-surface-variant rounded-lg appearance-none cursor-pointer accent-secondary" id="calc-volume-slider" max="<?php echo esc_attr( $atts['volume_max'] ); ?>" min="<?php echo esc_attr( $atts['volume_min'] ); ?>" step="<?php echo esc_attr( $atts['volume_step'] ); ?>" type="range" value="<?php echo esc_attr( $atts['volume'] ); ?>" aria-label="Monthly billed charges">
				<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant pt-1">
					<span>$50k</span>
					<span>$1M</span>
					<span>$2M+</span>
				</div>
			</div>
			<div>
				<div class="flex justify-between items-center mb-1">
					<span class="font-label-md text-label-md text-on-surface">Current Estimated Denial Rate:</span>
					<span class="font-headline-md text-headline-md text-error font-bold" id="calc-denial-text">18%</span>
				</div>
				<input class="w-full h-2 bg-surface-variant rounded-lg appearance-none cursor-pointer accent-secondary" id="calc-denial-slider" max="35" min="5" step="1" type="range" value="<?php echo esc_attr( $atts['denial'] ); ?>" aria-label="Current estimated denial rate">
				<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant pt-1">
					<span>5% (Optimized)</span>
					<span>18% (Avg US)</span>
					<span>35% (Severe)</span>
				</div>
			</div>
			<div>
				<div class="flex justify-between items-center mb-1">
					<span class="font-label-md text-label-md text-on-surface">Average Days in A/R:</span>
					<span class="font-headline-md text-headline-md text-primary font-bold" id="calc-ar-text">48 Days</span>
				</div>
				<input class="w-full h-2 bg-surface-variant rounded-lg appearance-none cursor-pointer accent-secondary" id="calc-ar-slider" max="90" min="20" step="2" type="range" value="<?php echo esc_attr( $atts['ar'] ); ?>" aria-label="Average days in accounts receivable">
				<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant pt-1">
					<span>20 Days</span>
					<span>45 Days (National Avg)</span>
					<span>90 Days</span>
				</div>
			</div>
		</div>
		<div class="mt-6 pt-5 bg-surface-container-low rounded-lg p-space-md">
			<div class="grid grid-cols-2 gap-space-sm mb-4">
				<div>
					<span class="font-label-sm text-label-sm text-on-surface-variant block">Recoverable Annual Cash</span>
					<span class="font-data-metric text-data-metric text-secondary font-bold" id="calc-recovered-annual">$71,820</span>
				</div>
				<div>
					<span class="font-label-sm text-label-sm text-on-surface-variant block">Projected AR Reduction</span>
					<span class="font-data-metric text-data-metric text-primary font-bold" id="calc-ar-reduction">34 Days Faster</span>
				</div>
			</div>
			<div class="p-3 bg-surface-container-lowest rounded-lg flex items-center justify-between">
				<div>
					<span class="font-label-sm text-label-sm text-on-surface-variant block">Estimated 3-Year Practice Impact:</span>
					<span class="font-headline-md text-headline-md text-tertiary-fixed-dim font-bold font-mono" id="calc-three-year">+$215,460</span>
				</div>
				<a class="px-4 py-2 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md hover:bg-primary transition-colors" href="#schedule-audit">Lock In Guarantee</a>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'entire_rcm_calculator', 'entire_rcm_calculator_shortcode' );

/* -------------------------------------------------------------------------
 * Search and social metadata.
 * ---------------------------------------------------------------------- */

function entire_rcm_meta_description() {
	$fallback = get_bloginfo( 'description' );
	$post     = null;

	if ( is_home() && ! is_front_page() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( $posts_page ) {
			$post = get_post( $posts_page );
		}
	} elseif ( is_singular() ) {
		$candidate = get_queried_object();
		if ( $candidate instanceof WP_Post ) {
			$post = $candidate;
		}
	}

	if ( $post instanceof WP_Post ) {
		$excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
		if ( ! $excerpt ) {
			$excerpt = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		}
		$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
		if ( '' !== $excerpt ) {
			return wp_html_excerpt( $excerpt, 155, '…' );
		}
	}

	return $fallback;
}

function entire_rcm_document_title( $parts ) {
	if ( is_front_page() ) {
		$parts['title'] = get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' );
		unset( $parts['tagline'] );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'entire_rcm_document_title' );

function entire_rcm_meta_tags() {
	$desc  = entire_rcm_meta_description();
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : home_url( '/' );

	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'full' );
	}
	if ( ! $image ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$image = wp_get_attachment_image_url( $logo_id, 'full' );
		}
	}

	echo "\n";
	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );
	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	if ( $image ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
	}
	printf( '<meta name="twitter:card" content="%s" />' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $desc ) );
}
add_action( 'wp_head', 'entire_rcm_meta_tags', 2 );

/**
 * FAQ pairs, pulled out of the rendered accordion so the structured data can
 * never drift from the visible question and answer.
 */
function entire_rcm_extract_faqs( $html ) {
	$faqs = array();
	if ( ! preg_match_all( '#<details\b[^>]*>(.*?)</details>#is', $html, $matches ) ) {
		return $faqs;
	}
	foreach ( $matches[1] as $chunk ) {
		if ( ! preg_match( '#<summary[^>]*>(.*?)</summary>#is', $chunk, $q ) ) {
			continue;
		}
		$question = trim( wp_strip_all_tags( $q[1] ) );
		$answer   = trim( wp_strip_all_tags( preg_replace( '#<summary[^>]*>.*?</summary>#is', '', $chunk ) ) );
		$answer   = trim( preg_replace( '/\s+/', ' ', $answer ) );
		if ( '' !== $question && '' !== $answer ) {
			$faqs[] = array( $question, $answer );
		}
	}
	return $faqs;
}

function entire_rcm_json_ld() {
	$graph = array();

	$graph[] = array(
		'@type'       => 'Organization',
		'@id'         => home_url( '/#organization' ),
		'name'        => get_bloginfo( 'name' ),
		'description' => get_bloginfo( 'description' ),
		'url'         => home_url( '/' ),
		'email'       => get_option( 'admin_email' ),
		'areaServed'  => array( '@type' => 'Country', 'name' => 'United States' ),
		'knowsAbout'  => array( 'Medical billing', 'Revenue cycle management',
			'Medical coding', 'Denial management', 'Prior authorisation' ),
	);

	if ( is_front_page() ) {
		$graph[] = array(
			'@type'      => 'WebSite',
			'@id'        => home_url( '/#website' ),
			'url'        => home_url( '/' ),
			'name'       => get_bloginfo( 'name' ),
			'publisher'  => array( '@id' => home_url( '/#organization' ) ),
			'inLanguage' => get_bloginfo( 'language' ),
		);

		$faqs = array();
		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$faqs = entire_rcm_extract_faqs( $post->post_content );
			}
		}
		if ( $faqs ) {
			$graph[] = array(
				'@type'      => 'FAQPage',
				'@id'        => home_url( '/#faq' ),
				'mainEntity' => array_map(
					function ( $pair ) {
						return array(
							'@type'          => 'Question',
							'name'           => $pair[0],
							'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $pair[1] ),
						);
					},
					$faqs
				),
			);
		}
	}

	if ( is_singular( 'post' ) ) {
		$graph[] = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink() . '#article',
			'headline'         => get_the_title(),
			'description'      => entire_rcm_meta_description(),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'mainEntityOfPage' => get_permalink(),
			'author'           => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ) ),
			'publisher'        => array( '@id' => home_url( '/#organization' ) ),
			'inLanguage'       => get_bloginfo( 'language' ),
		);
		if ( has_post_thumbnail() ) {
			$graph[ count( $graph ) - 1 ]['image'] = get_the_post_thumbnail_url( null, 'full' );
		}
	}

	if ( ! $graph ) {
		return;
	}

	echo '<script type="application/ld+json">'
		. wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ) )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'entire_rcm_json_ld', 5 );
