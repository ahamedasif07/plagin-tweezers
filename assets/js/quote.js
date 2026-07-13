/**
 * TickTweezers Quote Configurator — front-end controller.
 * Vanilla JS, no framework/build step. Relies on TTQ_DATA (localized
 * from PHP) for ajaxUrl / nonce / settings / i18n.
 *
 * Steps:
 *  Panel 1 → "Step 1 of 3" — Choose Product
 *  Panel 2 → "Step 2 of 3" — Customize Specifications
 *  Panel 3 → "Step 3 of 3" — Contact Details
 *  Panel 4 → "Step 3 of 3" (review, same sidebar number) — Review
 */
( function () {
	'use strict';

	if ( typeof TTQ_DATA === 'undefined' ) { return; }

	var STORAGE_KEY = 'ttq_quote_state_v2';

	var wizard = document.getElementById( 'ttq-wizard' );
	if ( ! wizard ) { return; }

	var form          = document.getElementById( 'ttq-form' );
	var railDots      = wizard.querySelectorAll( '.ttq-rail__dot' );
	var railCurrent   = wizard.querySelector( '.ttq-js-rail-current' );
	var railOf        = wizard.querySelector( '.ttq-js-rail-of' );
	var panels        = wizard.querySelectorAll( '.ttq-panel' );
	var overlay       = wizard.querySelector( '.ttq-js-overlay' );
	var topbarEyebrow = wizard.querySelector( '.ttq-js-step-eyebrow' );
	var topbarHeading = wizard.querySelector( '.ttq-js-step-heading' );
	var trustBadge    = wizard.querySelector( '.ttq-js-trust-badge' );
	var completedBadge = wizard.querySelector( '.ttq-js-completed-badge' );
	var trackItems    = wizard.querySelectorAll( '.ttq-step-track__item' );
	var trackLines    = wizard.querySelectorAll( '.ttq-step-track__line' );

	var currentStep = 1;
	var totalPanels = panels.length; // 4

	/* ── Step metadata ──────────────────────────────────────────── */
	// Sidebar dots represent steps 1–3 (panel 4 = review = still "step 3")
	var STEP_META = [
		null, // 1-indexed
		{ eyebrow: 'Step 1 of 3', heading: 'Choose Your Product',            railNum: 1, trackActive: 1 },
		{ eyebrow: 'Step 2 of 3', heading: 'Customize Your Specifications',  railNum: 2, trackActive: 2 },
		{ eyebrow: 'Step 3 of 3', heading: 'Contact Details',                railNum: 3, trackActive: 3 },
		{ eyebrow: 'Step 3 of 3', heading: 'Review Your Specifications',      railNum: 3, trackActive: 3 }
	];

	/* ── State ──────────────────────────────────────────────────── */
	var state = {
		product:      '',
		quantity:     TTQ_DATA.minQuantity || 25,
		colors:       [],
		sizes:        [],
		side1:        '',
		side2:        '',
		organization: '',
		name:         '',
		phone:        '',
		email:        '',
		free_sample:  'no',
		address:      '',
		logoToken:    '',
		logoPreviewUrl: '',
		logoFileName:   ''
	};

	/* ── Session persistence ────────────────────────────────────── */
	function saveState() {
		try {
			sessionStorage.setItem( STORAGE_KEY, JSON.stringify( { state: state, step: currentStep } ) );
		} catch ( e ) {}
	}

	function loadState() {
		try {
			var raw = sessionStorage.getItem( STORAGE_KEY );
			if ( ! raw ) { return; }
			var parsed = JSON.parse( raw );
			if ( parsed && parsed.state ) {
				state = Object.assign( state, parsed.state );
				currentStep = parsed.step || 1;
			}
		} catch ( e ) {}
	}

	function applyStateToForm() {
		var productInput = form.querySelector( 'input[name="product"][value="' + cssEscape( state.product ) + '"]' );
		if ( productInput ) { productInput.checked = true; }

		setVal( 'quantity', state.quantity );

		state.colors.forEach( function ( c ) {
			var el = form.querySelector( 'input[name="colors[]"][value="' + cssEscape( c ) + '"]' );
			if ( el ) { el.checked = true; }
		} );
		state.sizes.forEach( function ( s ) {
			var el = form.querySelector( 'input[name="sizes[]"][value="' + cssEscape( s ) + '"]' );
			if ( el ) { el.checked = true; }
		} );

		setVal( 'side1', state.side1 );
		setVal( 'side2', state.side2 );
		updateCharCounters();

		setVal( 'organization', state.organization );
		setVal( 'name', state.name );
		setVal( 'phone', state.phone );
		setVal( 'email', state.email );
		setVal( 'address', state.address );

		// Free sample radio
		if ( state.free_sample ) {
			var fsEl = form.querySelector( 'input[name="free_sample"][value="' + state.free_sample + '"]' );
			if ( fsEl ) { fsEl.checked = true; }
		}

		if ( state.logoToken ) {
			showUploadPreview( state.logoPreviewUrl, state.logoFileName );
		}

		filterColorsAndSizes();
	}

	function filterColorsAndSizes() {
		var selectedProductKey = state.product;
		var product = productFor( selectedProductKey );
		if ( ! product ) { return; }

		// Available color keys and size keys for this product (stored as comma strings)
		var availableColors = ( product.colors || '' ).split( ',' ).map( function ( s ) { return s.trim(); } ).filter( Boolean );
		var availableSizes  = ( product.sizes  || '' ).split( ',' ).map( function ( s ) { return s.trim(); } ).filter( Boolean );

		// If colors or sizes are empty, show all. Otherwise show only selected ones.
		var colorSwatches = wizard.querySelectorAll( '.ttq-color-swatch' );
		colorSwatches.forEach( function ( swatch ) {
			var input = swatch.querySelector( 'input[name="colors[]"]' );
			if ( ! input ) { return; }
			var key = input.value;
			var isAvailable = ( availableColors.length === 0 ) || ( availableColors.indexOf( key ) !== -1 );

			if ( isAvailable ) {
				swatch.style.display = '';
			} else {
				swatch.style.display = 'none';
				input.checked = false; // Uncheck hidden inputs
			}
		} );

		var sizeChips = wizard.querySelectorAll( '.ttq-size-chip' );
		sizeChips.forEach( function ( chip ) {
			var input = chip.querySelector( 'input[name="sizes[]"]' );
			if ( ! input ) { return; }
			var key = input.value;
			var isAvailable = ( availableSizes.length === 0 ) || ( availableSizes.indexOf( key ) !== -1 );

			if ( isAvailable ) {
				chip.style.display = '';
			} else {
				chip.style.display = 'none';
				input.checked = false; // Uncheck hidden inputs
			}
		} );
	}

	function setVal( name, value ) {
		var el = form.querySelector( '[name="' + name + '"]' );
		if ( el && value !== undefined && value !== null ) { el.value = value; }
	}

	function cssEscape( str ) {
		return String( str ).replace( /["\\]/g, '\\$&' );
	}

	function readFormIntoState() {
		state.product      = ( form.querySelector( 'input[name="product"]:checked' ) || {} ).value || '';
		state.quantity     = parseInt( ( form.querySelector( '[name="quantity"]' ) || {} ).value, 10 ) || 0;
		state.colors       = Array.prototype.map.call( form.querySelectorAll( 'input[name="colors[]"]:checked' ), function ( el ) { return el.value; } );
		state.sizes        = Array.prototype.map.call( form.querySelectorAll( 'input[name="sizes[]"]:checked' ), function ( el ) { return el.value; } );
		state.side1        = ( form.querySelector( '[name="side1"]' ) || {} ).value || '';
		state.side2        = ( form.querySelector( '[name="side2"]' ) || {} ).value || '';
		state.organization = ( form.querySelector( '[name="organization"]' ) || {} ).value || '';
		state.name         = ( form.querySelector( '[name="name"]' ) || {} ).value || '';
		state.phone        = ( form.querySelector( '[name="phone"]' ) || {} ).value || '';
		state.email        = ( form.querySelector( '[name="email"]' ) || {} ).value || '';
		state.address      = ( form.querySelector( '[name="address"]' ) || {} ).value || '';
		var freeEl = form.querySelector( 'input[name="free_sample"]:checked' );
		state.free_sample  = freeEl ? freeEl.value : 'no';
	}

	/* ── Navigation ─────────────────────────────────────────────── */
	function goToStep( n ) {
		currentStep = Math.min( Math.max( n, 1 ), totalPanels );
		wizard.setAttribute( 'data-step', currentStep );

		// Show/hide panels
		panels.forEach( function ( panel ) {
			var idx = parseInt( panel.getAttribute( 'data-panel' ), 10 );
			panel.hidden = idx !== currentStep;
		} );

		var meta = STEP_META[ currentStep ];
		if ( ! meta ) { return; }

		// Sidebar step number
		if ( railCurrent ) { railCurrent.textContent = meta.railNum; }

		// Sidebar dots — 3 dots representing steps 1-3
		// Panel 4 (review) keeps dot 3 active
		var dotStep = Math.min( currentStep, 3 );
		railDots.forEach( function ( dot ) {
			var idx = parseInt( dot.getAttribute( 'data-step-index' ), 10 );
			dot.classList.remove( 'is-current', 'is-complete', 'is-disabled' );
			if ( idx < dotStep ) {
				dot.classList.add( 'is-complete' );
			} else if ( idx === dotStep ) {
				dot.classList.add( 'is-current' );
			} else {
				dot.classList.add( 'is-disabled' );
			}
		} );

		// Top bar eyebrow + heading
		if ( topbarEyebrow ) { topbarEyebrow.textContent = meta.eyebrow; }
		if ( topbarHeading )  { topbarHeading.textContent = meta.heading; }

		// Step progress track (1 = Product, 2 = Customization, 3 = Review)
		// Panel 4 corresponds to track step 3 (review)
		var trackStep = ( currentStep === 4 ) ? 3 : currentStep;
		updateTrack( trackStep );

		// 100% Completed badge + trust badge toggle on review
		if ( currentStep === 4 ) {
			if ( trustBadge )    { trustBadge.hidden = true; }
			if ( completedBadge ) { completedBadge.hidden = false; }
		} else {
			if ( trustBadge )    { trustBadge.hidden = false; }
			if ( completedBadge ) { completedBadge.hidden = true; }
		}

		// Populate review panel
		if ( currentStep === 4 ) { populateReview(); }
		if ( currentStep === 2 ) { filterColorsAndSizes(); }

		// Focus heading for accessibility
		if ( topbarHeading ) {
			topbarHeading.setAttribute( 'tabindex', '-1' );
			topbarHeading.focus();
		}

		// Scroll to top of wizard
		wizard.scrollIntoView( { behavior: 'smooth', block: 'start' } );

		saveState();
	}

	function updateTrack( activeTrackStep ) {
		trackItems.forEach( function ( item ) {
			var ts = parseInt( item.getAttribute( 'data-track-step' ), 10 );
			item.classList.remove( 'is-active', 'is-complete' );
			if ( ts < activeTrackStep ) {
				item.classList.add( 'is-complete' );
			} else if ( ts === activeTrackStep ) {
				item.classList.add( 'is-active' );
			}
		} );
		trackLines.forEach( function ( line, i ) {
			// i=0 connects step1→step2, i=1 connects step2→step3
			line.classList.toggle( 'is-complete', ( i + 1 ) < activeTrackStep );
		} );
	}

	// Allow clicking completed rail dots to jump back
	railDots.forEach( function ( dot ) {
		dot.addEventListener( 'click', function () {
			if ( dot.classList.contains( 'is-disabled' ) ) { return; }
			readFormIntoState();
			var idx = parseInt( dot.getAttribute( 'data-step-index' ), 10 );
			goToStep( idx );
		} );
	} );

	/* ── Error helpers ──────────────────────────────────────────── */
	function clearErrors( panel ) {
		panel.querySelectorAll( '.ttq-field-error' ).forEach( function ( el ) { el.textContent = ''; } );
		panel.querySelectorAll( '.ttq-invalid' ).forEach( function ( el ) { el.classList.remove( 'ttq-invalid' ); } );
	}

	function showErrors( panel, errors ) {
		Object.keys( errors ).forEach( function ( field ) {
			var errEl = panel.querySelector( '[data-error-for="' + field + '"]' );
			if ( errEl ) { errEl.textContent = errors[ field ]; }
			var input = panel.querySelector( '[name="' + field + '"], [name="' + field + '[]"]' );
			if ( input ) { input.classList.add( 'ttq-invalid' ); }
		} );
	}

	/* ── AJAX step validation ───────────────────────────────────── */
	function ajaxValidateStep( stepKey, fields, panel, onValid ) {
		var body = new FormData();
		body.append( 'action', 'ttq_validate_step' );
		body.append( 'nonce', TTQ_DATA.nonce );
		body.append( 'step', stepKey );
		Object.keys( fields ).forEach( function ( key ) {
			var val = fields[ key ];
			if ( Array.isArray( val ) ) {
				val.forEach( function ( v ) { body.append( 'fields[' + key + '][]', v ); } );
			} else {
				body.append( 'fields[' + key + ']', val );
			}
		} );

		fetch( TTQ_DATA.ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( r ) { return r.json(); } )
			.then( function ( res ) {
				clearErrors( panel );
				if ( res.success ) {
					onValid();
				} else if ( res.data && res.data.errors ) {
					showErrors( panel, res.data.errors );
					// Shake invalid fields to draw attention
					panel.querySelectorAll( '.ttq-invalid' ).forEach( function ( el ) {
						el.classList.add( 'ttq-shake' );
						setTimeout( function () { el.classList.remove( 'ttq-shake' ); }, 600 );
					} );
				} else {
					showErrors( panel, { submit: TTQ_DATA.i18n.genericError } );
				}
			} )
			.catch( function () {
				showErrors( panel, { submit: TTQ_DATA.i18n.genericError } );
			} );
	}

	function currentPanel() {
		return wizard.querySelector( '.ttq-panel[data-panel="' + currentStep + '"]' );
	}

	/* ── Next / Back ────────────────────────────────────────────── */
	wizard.addEventListener( 'click', function ( e ) {
		var nextBtn = e.target.closest( '.ttq-js-next' );
		var backBtn = e.target.closest( '.ttq-js-back' );

		if ( nextBtn ) {
			readFormIntoState();
			var panel = currentPanel();

			if ( currentStep === 1 ) {
				ajaxValidateStep( 'step-1', { product: state.product }, panel, function () { goToStep( 2 ); } );
			} else if ( currentStep === 2 ) {
				ajaxValidateStep( 'step-2', {
					quantity: state.quantity, colors: state.colors, sizes: state.sizes,
					side1: state.side1, side2: state.side2
				}, panel, function () { goToStep( 3 ); } );
			} else if ( currentStep === 3 ) {
				ajaxValidateStep( 'step-3', {
					organization: state.organization, name: state.name,
					phone: state.phone, email: state.email, address: state.address
				}, panel, function () { goToStep( 4 ); } );
			}
		}

		if ( backBtn ) {
			readFormIntoState();
			goToStep( currentStep - 1 );
		}
	} );

	/* ── Step 1: "What's Included" toggle ───────────────────────── */
	wizard.addEventListener( 'click', function ( e ) {
		var toggle = e.target.closest( '.ttq-js-toggle-details' );
		if ( toggle ) {
			e.preventDefault();
			var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', String( ! expanded ) );
		}
	} );

	/* ── Step 2: quantity stepper ───────────────────────────────── */
	wizard.addEventListener( 'click', function ( e ) {
		var qtyInput = form.querySelector( '[name="quantity"]' );
		if ( ! qtyInput ) { return; }
		var min = parseInt( qtyInput.min, 10 ) || 1;
		var max = parseInt( qtyInput.max, 10 ) || 999999;

		if ( e.target.closest( '.ttq-js-qty-inc' ) ) {
			qtyInput.value = Math.min( max, ( parseInt( qtyInput.value, 10 ) || 0 ) + 1 );
		}
		if ( e.target.closest( '.ttq-js-qty-dec' ) ) {
			qtyInput.value = Math.max( min, ( parseInt( qtyInput.value, 10 ) || 0 ) - 1 );
		}
	} );

	/* Character counters */
	function updateCharCounters() {
		wizard.querySelectorAll( '.ttq-js-char-counter' ).forEach( function ( input ) {
			var wrap = input.closest( 'div' ) || input.parentElement;
			var counter = wrap ? wrap.querySelector( '.ttq-js-char-count' ) : null;
			if ( counter ) { counter.textContent = input.value.length; }
		} );
	}
	wizard.addEventListener( 'input', function ( e ) {
		if ( e.target.classList.contains( 'ttq-js-char-counter' ) ) { updateCharCounters(); }
	} );

	/* ── Logo upload ────────────────────────────────────────────── */
	var dropzone      = wizard.querySelector( '.ttq-js-dropzone' );
	var fileInput     = wizard.querySelector( '.ttq-js-file-input' );
	var progressEl    = wizard.querySelector( '.ttq-js-upload-progress' );
	var progressFill  = wizard.querySelector( '.ttq-js-progress-fill' );
	var progressLabel = wizard.querySelector( '.ttq-js-progress-label' );
	var previewEl     = wizard.querySelector( '.ttq-js-upload-preview' );
	var previewImg    = wizard.querySelector( '.ttq-js-preview-img' );
	var previewName   = wizard.querySelector( '.ttq-js-preview-name' );
	var tokenInput    = wizard.querySelector( '.ttq-js-logo-token' );

	function showUploadPreview( url, name ) {
		if ( previewImg )  { previewImg.src = url; }
		if ( previewName ) { previewName.textContent = name; }
		if ( previewEl )   { previewEl.hidden = false; }
		if ( dropzone )    { dropzone.hidden = true; }
	}

	function resetUpload() {
		state.logoToken = '';
		state.logoPreviewUrl = '';
		state.logoFileName = '';
		if ( tokenInput )  { tokenInput.value = ''; }
		if ( previewEl )   { previewEl.hidden = true; }
		if ( dropzone )    { dropzone.hidden = false; }
		if ( fileInput )   { fileInput.value = ''; }
	}

	function uploadLogo( file ) {
		var errEl = wizard.querySelector( '[data-error-for="logo"]' );
		if ( errEl ) { errEl.textContent = ''; }

		var body = new FormData();
		body.append( 'action', 'ttq_upload_logo' );
		body.append( 'nonce', TTQ_DATA.nonce );
		body.append( 'logo', file );

		var xhr = new XMLHttpRequest();
		xhr.open( 'POST', TTQ_DATA.ajaxUrl, true );
		if ( progressEl ) { progressEl.hidden = false; }

		xhr.upload.onprogress = function ( evt ) {
			if ( ! evt.lengthComputable ) { return; }
			var pct = Math.round( ( evt.loaded / evt.total ) * 100 );
			if ( progressFill )  { progressFill.style.width = pct + '%'; }
			if ( progressLabel ) { progressLabel.textContent = pct + '%'; }
		};

		xhr.onload = function () {
			if ( progressEl ) { progressEl.hidden = true; }
			var res;
			try { res = JSON.parse( xhr.responseText ); } catch ( e ) { res = null; }
			if ( res && res.success ) {
				state.logoToken = res.data.token;
				state.logoPreviewUrl = res.data.previewUrl;
				state.logoFileName = res.data.fileName;
				if ( tokenInput ) { tokenInput.value = state.logoToken; }
				showUploadPreview( state.logoPreviewUrl, state.logoFileName );
				saveState();
			} else if ( errEl ) {
				errEl.textContent = ( res && res.data && res.data.message ) ? res.data.message : TTQ_DATA.i18n.genericError;
			}
		};

		xhr.onerror = function () {
			if ( progressEl ) { progressEl.hidden = true; }
			if ( errEl ) { errEl.textContent = TTQ_DATA.i18n.genericError; }
		};

		xhr.send( body );
	}

	if ( dropzone ) {
		dropzone.addEventListener( 'click', function () { fileInput.click(); } );
		dropzone.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); fileInput.click(); }
		} );
		[ 'dragenter', 'dragover' ].forEach( function ( evt ) {
			dropzone.addEventListener( evt, function ( e ) { e.preventDefault(); dropzone.classList.add( 'is-dragover' ); } );
		} );
		[ 'dragleave', 'drop' ].forEach( function ( evt ) {
			dropzone.addEventListener( evt, function ( e ) { e.preventDefault(); dropzone.classList.remove( 'is-dragover' ); } );
		} );
		dropzone.addEventListener( 'drop', function ( e ) {
			var file = e.dataTransfer.files && e.dataTransfer.files[0];
			if ( file ) { uploadLogo( file ); }
		} );
	}
	if ( fileInput ) {
		fileInput.addEventListener( 'change', function () {
			if ( fileInput.files && fileInput.files[0] ) { uploadLogo( fileInput.files[0] ); }
		} );
	}
	wizard.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( '.ttq-js-upload-remove' ) )  { resetUpload(); }
		if ( e.target.closest( '.ttq-js-upload-replace' ) ) { resetUpload(); fileInput.click(); }
	} );

	/* ── Review population ──────────────────────────────────────── */
	function labelFor( list, key ) {
		var match = list.filter( function ( item ) { return item.key === key; } )[0];
		return match ? match.label : key;
	}

	function productFor( key ) {
		return ( TTQ_DATA.products || [] ).filter( function ( p ) { return p.key === key; } )[0] || null;
	}

	function populateReview() {
		readFormIntoState();

		var product = productFor( state.product );
		var productLabel = product ? product.label : state.product;

		setReview( 'product', productLabel );
		setReview( 'quantity', state.quantity + ' units' );
		setReview( 'sizes',    state.sizes.map( function ( s )  { return labelFor( TTQ_DATA.sizes, s ); } ).join( ', ' ) || '—' );
		setReview( 'colors',   state.colors.map( function ( c ) { return labelFor( TTQ_DATA.colors, c ); } ).join( ', ' ) || '—' );
		setReview( 'side1',    state.side1 || '—' );
		setReview( 'side2',    state.side2 || '—' );
		setReview( 'name',     state.name );
		setReview( 'organization', state.organization || '—' );
		setReview( 'phone',    state.phone );
		setReview( 'email',    state.email );
		setReview( 'address',  state.address );
		setReview( 'free_sample', state.free_sample === 'yes' ? 'Yes' : 'No' );

		// Product thumbnail in review
		var thumb = wizard.querySelector( '.ttq-js-review-product-img' );
		var nameEl = wizard.querySelector( '.ttq-js-review-product-name' );
		var tagEl  = wizard.querySelector( '.ttq-js-review-product-tag' );

		if ( thumb ) {
			if ( product && product.image ) {
				thumb.src = product.image;
				thumb.style.display = 'block';
			} else {
				thumb.style.display = 'none';
			}
		}
		if ( nameEl ) { nameEl.textContent = productLabel; }
		if ( tagEl && product ) {
			var featured = product.featured && product.featured !== '0' && product.featured !== '';
			tagEl.textContent = featured ? '★ Most Popular' : ( product.key === 'tweezers_only' ? 'Tick Tweezers Only' : '' );
		}

		// Logo
		var logoImg = wizard.querySelector( '.ttq-js-review-logo' );
		var noLogo  = wizard.querySelector( '.ttq-js-review-no-logo' );
		if ( state.logoToken && state.logoPreviewUrl ) {
			if ( logoImg ) { logoImg.src = state.logoPreviewUrl; logoImg.style.display = 'block'; }
			if ( noLogo )  { noLogo.style.display = 'none'; }
		} else {
			if ( logoImg ) { logoImg.style.display = 'none'; }
			if ( noLogo )  { noLogo.style.display = 'block'; }
		}
	}

	function setReview( field, value ) {
		var el = wizard.querySelector( '[data-review="' + field + '"]' );
		if ( el ) { el.textContent = value; }
	}

	/* ── Final submission ───────────────────────────────────────── */
	function showOverlay( which ) {
		overlay.hidden = false;
		[ 'submitting', 'success', 'error' ].forEach( function ( name ) {
			var panel = wizard.querySelector( '.ttq-js-overlay-' + name );
			if ( panel ) { panel.hidden = name !== which; }
		} );
	}
	function hideOverlay() { overlay.hidden = true; }

	wizard.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( '.ttq-js-overlay-close' ) ) { hideOverlay(); }

		if ( e.target.closest( '.ttq-js-submit' ) ) {
			readFormIntoState();
			showOverlay( 'submitting' );

			var body = new FormData();
			body.append( 'action', 'ttq_submit_quote' );
			body.append( 'nonce', TTQ_DATA.nonce );
			body.append( 'logo_token', state.logoToken );

			appendGroup( body, 'step1', { product: state.product } );
			appendGroup( body, 'step2', {
				quantity: state.quantity, colors: state.colors, sizes: state.sizes,
				side1: state.side1, side2: state.side2
			} );
			appendGroup( body, 'step3', {
				organization: state.organization, name: state.name,
				phone: state.phone, email: state.email, address: state.address,
				free_sample: state.free_sample
			} );

			var started = Date.now();
			var MIN_MS  = 900;

			fetch( TTQ_DATA.ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' } )
				.then( function ( r ) { return r.json(); } )
				.then( function ( res ) {
					var elapsed = Date.now() - started;
					var wait = Math.max( 0, MIN_MS - elapsed );
					setTimeout( function () {
						if ( res.success ) {
							var msgEl = wizard.querySelector( '.ttq-js-overlay-success-message' );
							if ( msgEl && res.data && res.data.message ) { msgEl.textContent = res.data.message; }
							showOverlay( 'success' );
							try { sessionStorage.removeItem( STORAGE_KEY ); } catch ( e ) {}
						} else {
							var errMsgEl = wizard.querySelector( '.ttq-js-overlay-error-message' );
							var message = ( res.data && res.data.message ) ? res.data.message : TTQ_DATA.i18n.genericError;
							if ( errMsgEl ) { errMsgEl.textContent = message; }
							showOverlay( 'error' );
							if ( res.data && res.data.errors ) { showErrors( currentPanel(), res.data.errors ); }
						}
					}, wait );
				} )
				.catch( function () {
					showOverlay( 'error' );
					var errMsgEl = wizard.querySelector( '.ttq-js-overlay-error-message' );
					if ( errMsgEl ) { errMsgEl.textContent = TTQ_DATA.i18n.genericError; }
				} );
		}
	} );

	function appendGroup( body, group, obj ) {
		Object.keys( obj ).forEach( function ( key ) {
			var val = obj[ key ];
			if ( Array.isArray( val ) ) {
				val.forEach( function ( v ) { body.append( group + '[' + key + '][]', v ); } );
			} else {
				body.append( group + '[' + key + ']', val );
			}
		} );
	}

	/* ── Init ───────────────────────────────────────────────────── */
	loadState();
	applyStateToForm();
	goToStep( currentStep );

	// Keep state fresh as user types
	form.addEventListener( 'input',  function () { readFormIntoState(); saveState(); } );
	form.addEventListener( 'change', function () { readFormIntoState(); saveState(); } );
	form.addEventListener( 'submit', function ( e ) { e.preventDefault(); } );

} )();
