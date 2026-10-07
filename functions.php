<?php
/**
 * Entire RCM child theme.
 *
 * Deliberately thin: the design system (colour palette, font families, type
 * scale, component radii/shadows, per-block styles) lives in theme.json so it
 * stays editable in the WordPress Site Editor. This file only adds the handful
 * of things theme.json cannot express — a front-end stylesheet for the shared
 * utilities, and the small amount of JavaScript the interactive pricing
 * calculator needs.
 *
 * @package Entire_RCM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ENTIRE_RCM_VERSION', '1.0.0' );

/**
 * Front-end assets.
 */
function entire_rcm_enqueue_assets() {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	wp_enqueue_style(
		'entire-rcm',
		$uri . '/assets/css/entire-rcm.css',
		array(),
		filemtime( $dir . '/assets/css/entire-rcm.css' )
	);

	wp_enqueue_script(
		'entire-rcm',
		$uri . '/assets/js/entire-rcm.js',
		array(),
		filemtime( $dir . '/assets/js/entire-rcm.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'entire_rcm_enqueue_assets' );

/**
 * Block styles. These show up in the editor's Styles panel, so every card,
 * panel and button variant on the site is a one-click choice rather than a
 * bespoke class an editor has to remember.
 */
function entire_rcm_register_block_styles() {
	$group_styles = array(
		'ercm-card'          => __( 'Card', 'entire-rcm' ),
		'ercm-card-elevated' => __( 'Card (hover lift)', 'entire-rcm' ),
		'ercm-tint-panel'    => __( 'Tint panel', 'entire-rcm' ),
		'ercm-outline-panel' => __( 'Outlined panel', 'entire-rcm' ),
		'ercm-dark-panel'    => __( 'Dark navy panel', 'entire-rcm' ),
		'ercm-pill'          => __( 'Pill', 'entire-rcm' ),
		'ercm-badge'         => __( 'Badge', 'entire-rcm' ),
		'ercm-tabular'       => __( 'Tabular numerals', 'entire-rcm' ),
	);
	foreach ( $group_styles as $slug => $label ) {
		register_block_style( 'core/group', array( 'name' => $slug, 'label' => $label ) );
	}

	$button_styles = array(
		'ercm-cta-light' => __( 'Light on dark', 'entire-rcm' ),
		'ercm-ghost'     => __( 'Ghost on dark', 'entire-rcm' ),
		'ercm-pill'      => __( 'Pill', 'entire-rcm' ),
		'ercm-emerald'   => __( 'Emerald', 'entire-rcm' ),
	);
	foreach ( $button_styles as $slug => $label ) {
		register_block_style( 'core/button', array( 'name' => $slug, 'label' => $label ) );
	}

	register_block_style(
		'core/paragraph',
		array( 'name' => 'ercm-eyebrow', 'label' => __( 'Eyebrow label', 'entire-rcm' ) )
	);
	register_block_style(
		'core/paragraph',
		array( 'name' => 'ercm-tabular', 'label' => __( 'Tabular numerals', 'entire-rcm' ) )
	);
	register_block_style(
		'core/list',
		array( 'name' => 'ercm-check', 'label' => __( 'Check list', 'entire-rcm' ) )
	);
}
add_action( 'init', 'entire_rcm_register_block_styles' );

/**
 * Slightly longer excerpts for the blog cards.
 */
function entire_rcm_excerpt_length( $length ) {
	return is_admin() ? $length : 26;
}
add_filter( 'excerpt_length', 'entire_rcm_excerpt_length', 20 );

/**
 * Revenue-recovery calculator.
 *
 * Rendered through the native Shortcode block so the widget can be dropped into
 * any page from the editor and re-tuned there, e.g.
 *
 *     [entire_rcm_calculator default_charges="350000" default_denial="18"]
 *
 * Every attribute is optional; the defaults match the published design.
 */
function entire_rcm_calculator_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'charges_min'          => '50000',
			'charges_max'          => '2000000',
			'charges_step'         => '10000',
			'default_charges'      => '350000',
			'denial_min'           => '5',
			'denial_max'           => '35',
			'denial_step'          => '1',
			'default_denial'       => '18',
			'ar_min'               => '14',
			'ar_max'               => '90',
			'ar_step'              => '1',
			'default_ar'           => '48',
			'note'                 => __( 'Illustrative model based on HFMA benchmark recovery ranges. A 30-day audit replaces this with your actual 835 ERA data.', 'entire-rcm' ),
		),
		$atts,
		'entire_rcm_calculator'
	);

	ob_start();
	?>
	<div class="ercm-calc" data-ercm-calc>
		<div class="ercm-calc__row">
			<div class="ercm-calc__label">
				<span><?php esc_html_e( 'Monthly billed charges', 'entire-rcm' ); ?></span>
				<span class="ercm-calc__value" data-ercm-out="charges"></span>
			</div>
			<input type="range" data-ercm-range="charges"
				min="<?php echo esc_attr( $atts['charges_min'] ); ?>"
				max="<?php echo esc_attr( $atts['charges_max'] ); ?>"
				step="<?php echo esc_attr( $atts['charges_step'] ); ?>"
				value="<?php echo esc_attr( $atts['default_charges'] ); ?>"
				aria-label="<?php esc_attr_e( 'Monthly billed charges', 'entire-rcm' ); ?>" />
		</div>

		<div class="ercm-calc__row">
			<div class="ercm-calc__label">
				<span><?php esc_html_e( 'Current estimated denial rate', 'entire-rcm' ); ?></span>
				<span class="ercm-calc__value" data-ercm-out="denial"></span>
			</div>
			<input type="range" data-ercm-range="denial"
				min="<?php echo esc_attr( $atts['denial_min'] ); ?>"
				max="<?php echo esc_attr( $atts['denial_max'] ); ?>"
				step="<?php echo esc_attr( $atts['denial_step'] ); ?>"
				value="<?php echo esc_attr( $atts['default_denial'] ); ?>"
				aria-label="<?php esc_attr_e( 'Current estimated denial rate, percent', 'entire-rcm' ); ?>" />
		</div>

		<div class="ercm-calc__row">
			<div class="ercm-calc__label">
				<span><?php esc_html_e( 'Average days in A/R', 'entire-rcm' ); ?></span>
				<span class="ercm-calc__value" data-ercm-out="ar"></span>
			</div>
			<input type="range" data-ercm-range="ar"
				min="<?php echo esc_attr( $atts['ar_min'] ); ?>"
				max="<?php echo esc_attr( $atts['ar_max'] ); ?>"
				step="<?php echo esc_attr( $atts['ar_step'] ); ?>"
				value="<?php echo esc_attr( $atts['default_ar'] ); ?>"
				aria-label="<?php esc_attr_e( 'Average days in accounts receivable', 'entire-rcm' ); ?>" />
		</div>

		<div class="ercm-calc__out">
			<div class="ercm-calc__out-grid">
				<div>
					<p class="ercm-calc__out-label"><?php esc_html_e( 'Recoverable annual cash', 'entire-rcm' ); ?></p>
					<p class="ercm-calc__out-value" data-ercm-out="recoverable"></p>
				</div>
				<div>
					<p class="ercm-calc__out-label"><?php esc_html_e( 'A/R reduction', 'entire-rcm' ); ?></p>
					<p class="ercm-calc__out-value" data-ercm-out="arReduction"></p>
				</div>
				<div>
					<p class="ercm-calc__out-label"><?php esc_html_e( 'Estimated 3-year impact', 'entire-rcm' ); ?></p>
					<p class="ercm-calc__out-value" data-ercm-out="threeYear"></p>
				</div>
			</div>
			<?php if ( $atts['note'] ) : ?>
				<p class="ercm-calc__note"><?php echo esc_html( $atts['note'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'entire_rcm_calculator', 'entire_rcm_calculator_shortcode' );

/**
 * Give the calculator markup a stable id target and make sure the anchor
 * sections account for the sticky header (handled in CSS via scroll-margin).
 *
 * Also exposes the site's brand strings to the calculator script so the
 * currency/formatting stays in one place.
 */
function entire_rcm_inline_config() {
	$config = array(
		'currency' => '$',
		'locale'   => 'en-US',
	);
	wp_add_inline_script(
		'entire-rcm',
		'window.entireRCMConfig = ' . wp_json_encode( $config ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'entire_rcm_inline_config', 20 );
