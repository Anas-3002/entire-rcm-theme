/*!
 * Entire RCM — front-end behaviour.
 *
 * Two small, dependency-free enhancements:
 *   1. the revenue-recovery calculator (progressive: the markup already holds
 *      a complete, readable default result if JS never runs), and
 *   2. the sticky-header shadow.
 */
( function () {
	'use strict';

	var CFG = window.entireRCMConfig || { currency: '$', locale: 'en-US' };

	function money( value ) {
		var rounded = Math.round( value );
		try {
			return CFG.currency + rounded.toLocaleString( CFG.locale );
		} catch ( e ) {
			return CFG.currency + rounded;
		}
	}

	/**
	 * Recovery model, calibrated to the benchmark figures used in the design:
	 * a practice billing $350k/month with an 18% denial rate recovers roughly
	 * $71.8k a year. Recovery scales linearly with billed charges and with the
	 * denial gap above the 5% best-in-class floor.
	 */
	function calculate( monthly, denialRate, arDays ) {
		var annual = monthly * 12;
		var denialGap = Math.max( 0, denialRate - 5 ) / 100;
		var recoverable = annual * denialGap * 0.1315;
		return {
			recoverable: recoverable,
			arReduction: Math.max( 0, Math.round( arDays - 14 ) ),
			threeYear: recoverable * 3
		};
	}

	function initCalculator( root ) {
		var slider = function ( name ) {
			return root.querySelector( '[data-ercm-range="' + name + '"]' );
		};
		var output = function ( name ) {
			return root.querySelector( '[data-ercm-out="' + name + '"]' );
		};

		var charges = slider( 'charges' );
		var denial = slider( 'denial' );
		var ar = slider( 'ar' );

		if ( ! charges || ! denial || ! ar ) {
			return;
		}

		var outputs = {
			charges: output( 'charges' ),
			denial: output( 'denial' ),
			ar: output( 'ar' ),
			recoverable: output( 'recoverable' ),
			arReduction: output( 'arReduction' ),
			threeYear: output( 'threeYear' )
		};

		function render() {
			var monthly = parseFloat( charges.value );
			var denialRate = parseFloat( denial.value );
			var arDays = parseFloat( ar.value );
			var result = calculate( monthly, denialRate, arDays );

			if ( outputs.charges ) {
				outputs.charges.textContent = money( monthly );
			}
			if ( outputs.denial ) {
				outputs.denial.textContent = denialRate + '%';
			}
			if ( outputs.ar ) {
				outputs.ar.textContent = arDays + ( arDays === 1 ? ' Day' : ' Days' );
			}
			if ( outputs.recoverable ) {
				outputs.recoverable.textContent = money( result.recoverable );
			}
			if ( outputs.arReduction ) {
				outputs.arReduction.textContent = result.arReduction + ' Days Faster';
			}
			if ( outputs.threeYear ) {
				outputs.threeYear.textContent = '+' + money( result.threeYear );
			}
		}

		[ charges, denial, ar ].forEach( function ( input ) {
			input.addEventListener( 'input', render );
			input.addEventListener( 'change', render );
		} );

		render();
	}

	function initStickyHeader() {
		var header = document.querySelector( '.ercm-site-header' );
		if ( ! header ) {
			return;
		}
		var update = function () {
			header.classList.toggle( 'is-stuck', window.scrollY > 8 );
		};
		update();
		window.addEventListener( 'scroll', update, { passive: true } );
	}

	function boot() {
		document.querySelectorAll( '.ercm-calc' ).forEach( initCalculator );
		initStickyHeader();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
} )();
