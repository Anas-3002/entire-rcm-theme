/*!
 * Entire RCM — front-end behaviour.
 *
 * Ported from the exported Stitch design's inline script: the hero quick-audit
 * estimate, the ROI calculator, the case-study filter, the pricing toggle and
 * the FAQ accordion all behave as designed. Element ids match the design's own
 * ids, and Contact Form 7 submissions raise the design's confirmation banners.
 */
( function () {
	'use strict';

	var $ = function ( id ) {
		return document.getElementById( id );
	};

	function money( value ) {
		return '$' + Math.round( value ).toLocaleString();
	}

	function show( el, display ) {
		if ( el ) {
			el.classList.remove( 'hidden' );
			if ( display ) {
				el.style.display = display;
			}
		}
	}

	/* ------------------------------------------------ 1. hero quick audit --- */

	window.runQuickAuditCompute = function () {
		var specialtyField = $( 'audit-specialty' );
		var volumeField = $( 'audit-monthly-volume' );
		var banner = $( 'quick-audit-result-banner' );
		var lift = $( 'quick-result-lift' );
		var summary = $( 'quick-result-summary' );

		if ( ! volumeField || ! banner || ! lift || ! summary ) {
			return;
		}

		var specialty = specialtyField && specialtyField.value ? specialtyField.value : 'comparable specialty';
		var monthly = parseFloat( volumeField.value ) || 0;
		var estimated = Math.round( monthly * 12 * 0.148 );

		lift.innerText = money( estimated ) + ' / yr';
		summary.innerText = 'Based on average ' + specialty + ' claim denial leakages, Entire RCM recovers up to ' +
			'14.8% of previously write-off-prone revenue within 60 days.';
		show( banner );
		banner.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
	};

	function bindHeroAudit() {
		var volumeField = $( 'audit-monthly-volume' );
		var display = $( 'audit-volume-display' );

		if ( volumeField && display ) {
			var sync = function () {
				display.innerText = money( parseFloat( volumeField.value ) || 0 ) + ' / mo';
			};
			volumeField.addEventListener( 'input', sync );
			sync();
		}
	}

	/* ------------------------------------------------ 2. ROI calculator ---- */

	window.updateROICalculator = function () {
		var vol = $( 'calc-volume-slider' );
		var denial = $( 'calc-denial-slider' );
		var ar = $( 'calc-ar-slider' );

		if ( ! vol || ! denial || ! ar ) {
			return;
		}

		var volume = parseFloat( vol.value );
		var denialPct = parseFloat( denial.value );
		var arDays = parseFloat( ar.value );

		var volumeText = $( 'calc-volume-text' );
		var denialText = $( 'calc-denial-text' );
		var arText = $( 'calc-ar-text' );
		if ( volumeText ) {
			volumeText.innerText = money( volume );
		}
		if ( denialText ) {
			denialText.innerText = denialPct + '%';
		}
		if ( arText ) {
			arText.innerText = arDays + ' Days';
		}

		var annual = volume * 12;
		var lostToDenials = annual * ( denialPct / 100 );
		var recoverable = Math.round( lostToDenials * 0.72 );
		var threeYear = Math.round( recoverable * 3 );
		var faster = Math.max( 12, Math.round( arDays - 14 ) );

		var recovered = $( 'calc-recovered-annual' );
		var three = $( 'calc-three-year' );
		var reduction = $( 'calc-ar-reduction' );
		if ( recovered ) {
			recovered.innerText = money( recoverable );
		}
		if ( three ) {
			three.innerText = '+' + money( threeYear );
		}
		if ( reduction ) {
			reduction.innerText = faster + ' Days Faster';
		}
	};

	/* ------------------------------------------- 3. case study filtering --- */

	function cardSpecialty( card ) {
		var tag = card.querySelector( '[data-specialty]' );
		return tag ? tag.getAttribute( 'data-specialty' ) : 'all';
	}

	window.filterCaseStudies = function ( specialty ) {
		document.querySelectorAll( '.ercm-case-filter' ).forEach( function ( btn ) {
			var active = btn.getAttribute( 'data-filter' ) === specialty;
			btn.classList.toggle( 'bg-surface-container-lowest', active );
			btn.classList.toggle( 'text-primary', active );
			btn.classList.toggle( 'shadow-sm', active );
			btn.classList.toggle( 'text-on-surface-variant', ! active );
		} );

		document.querySelectorAll( '.ercm-case-card' ).forEach( function ( card ) {
			var match = specialty === 'all' || cardSpecialty( card ) === specialty;
			card.style.display = match ? 'flex' : 'none';
		} );
	};

	/* -------------------------------------------- 4. pricing volume toggle - */

	var RATES = {
		standard: [ '4.2%', '4.8%', 'Custom %' ],
		growth: [ '3.8%', '4.4%', '2.9% - 3.5%' ]
	};

	window.setPricingScale = function ( scale ) {
		document.querySelectorAll( '.ercm-price-toggle' ).forEach( function ( btn ) {
			var active = btn.getAttribute( 'data-scale' ) === scale;
			btn.classList.toggle( 'bg-surface-container-lowest', active );
			btn.classList.toggle( 'text-primary', active );
			btn.classList.toggle( 'shadow-sm', active );
			btn.classList.toggle( 'text-on-surface-variant', ! active );
		} );

		var rates = RATES[ scale ] || RATES.standard;
		[ 1, 2, 3 ].forEach( function ( i ) {
			var el = $( 'tier-' + i + '-rate' );
			if ( el ) {
				el.innerText = rates[ i - 1 ];
			}
		} );
	};

	/* ------------------------------------------------- 5. FAQ accordion ---- */

	function initFaq() {
		var items = document.querySelectorAll( '.ercm-faq' );
		items.forEach( function ( item ) {
			item.addEventListener( 'toggle', function () {
				var icon = item.querySelector( '.ercm-faq-icon' );
				if ( icon ) {
					icon.textContent = item.open ? 'remove' : 'add';
				}
				if ( item.open ) {
					items.forEach( function ( other ) {
						if ( other !== item && other.open ) {
							other.open = false;
						}
					} );
				}
			} );
		} );
	}

	/* ------------------------------------------------------- chrome bits --- */

	function initChrome() {
		var open = document.querySelector( '.ercm-drawer-open' );
		var close = document.querySelector( '.ercm-drawer-close' );
		var drawer = $( 'mobile-drawer' );

		if ( open && drawer ) {
			open.addEventListener( 'click', function () {
				drawer.classList.toggle( 'hidden' );
				document.body.classList.toggle( 'overflow-hidden' );
			} );
		}
		if ( close && drawer ) {
			close.addEventListener( 'click', function () {
				drawer.classList.add( 'hidden' );
				document.body.classList.remove( 'overflow-hidden' );
			} );
		}
		if ( drawer ) {
			drawer.addEventListener( 'click', function ( event ) {
				if ( event.target === drawer ) {
					drawer.classList.add( 'hidden' );
					document.body.classList.remove( 'overflow-hidden' );
				}
			} );
		}

		var dismiss = document.querySelector( '.ercm-announcement-dismiss' );
		var bar = $( 'announcement-bar' );
		if ( dismiss && bar ) {
			dismiss.addEventListener( 'click', function () {
				bar.style.display = 'none';
			} );
		}

		document.querySelectorAll( '.ercm-case-filter' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				window.filterCaseStudies( btn.getAttribute( 'data-filter' ) || 'all' );
			} );
		} );

		document.querySelectorAll( '.ercm-price-toggle' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				window.setPricingScale( btn.getAttribute( 'data-scale' ) || 'standard' );
			} );
		} );

		// Smooth in-page navigation for the design's anchor links.
		document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( event ) {
				var target = document.getElementById( link.getAttribute( 'href' ).slice( 1 ) );
				if ( ! target ) {
					return;
				}
				event.preventDefault();
				target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			} );
		} );
	}

	/* ------------------------------------------- 6. Contact Form 7 hooks ---- */

	// Contact Form 7 renders the <form> element itself, so a form is identified
	// by the shortcode's own id (or the wrapper's data-wpcf7-id) rather than by
	// anything the template declares.
	var FORM_KEYS = {
		13: 'hero-quick-audit-form',
		14: 'full-audit-booking-form',
		15: 'footer-audit-form'
	};

	function formKey( form ) {
		if ( ! form ) {
			return null;
		}
		if ( form.id ) {
			return form.id;
		}
		var wrap = form.closest ? form.closest( '[data-wpcf7-id]' ) : null;
		var fid = wrap ? wrap.getAttribute( 'data-wpcf7-id' ) : null;
		return fid ? ( FORM_KEYS[ fid ] || null ) : null;
	}

	function initForms() {
		document.addEventListener( 'wpcf7mailsent', function ( event ) {
			var target = event.target;
			var form = target && target.closest ? target.closest( '.wpcf7-form' ) : null;
			var key = formKey( form );

			if ( key === 'hero-quick-audit-form' ) {
				window.runQuickAuditCompute();
				show( $( 'hero-quick-audit-success' ) );
			}
			if ( key === 'full-audit-booking-form' ) {
				show( $( 'booking-confirmation-alert' ), 'block' );
			}
			if ( key === 'footer-audit-form' && form && form.parentNode ) {
				if ( ! document.querySelector( '.ercm-footer-thanks' ) ) {
					var span = document.createElement( 'span' );
					span.className = 'ercm-footer-thanks text-label-sm font-label-sm text-tertiary-fixed';
					span.innerText = 'Request received — a revenue director will be in touch.';
					form.parentNode.insertBefore( span, form.nextSibling );
				}
			}
		} );
	}

	function boot() {
		bindHeroAudit();
		window.updateROICalculator();
		[ 'calc-volume-slider', 'calc-denial-slider', 'calc-ar-slider' ].forEach( function ( id ) {
			var el = $( id );
			if ( el ) {
				el.addEventListener( 'input', window.updateROICalculator );
			}
		} );
		initFaq();
		initChrome();
		initForms();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
} )();
