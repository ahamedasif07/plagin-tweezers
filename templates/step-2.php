<?php
/** @var array $settings */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ttq-step-head">
	<h3><?php esc_html_e( 'Customize Your Specifications', 'ttq' ); ?></h3>
	<p class="ttq-sub"><?php esc_html_e( 'Set your quantity, choose your colors and size, add your personalization, and optionally upload your logo.', 'ttq' ); ?></p>
</div>

<!-- ── Quantity ──────────────────────────────────────────────────── -->
<div class="ttq-section-card">
	<div class="ttq-section-card__header">
		<span class="ttq-section-card__icon" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
		</span>
		<span class="ttq-section-card__title"><?php esc_html_e( 'Order Quantity', 'ttq' ); ?> <span class="req">*</span></span>
	</div>

	<div class="ttq-field-group">
		<div class="ttq-qty-stepper">
			<button type="button" class="ttq-qty-btn ttq-js-qty-dec" aria-label="<?php esc_attr_e( 'Decrease quantity', 'ttq' ); ?>">&minus;</button>
			<input type="number" id="ttq-quantity" name="quantity" inputmode="numeric"
				value="<?php echo esc_attr( $settings['min_quantity'] ); ?>"
				min="<?php echo esc_attr( $settings['min_quantity'] ); ?>"
				max="<?php echo esc_attr( $settings['max_quantity'] ); ?>" />
			<button type="button" class="ttq-qty-btn ttq-js-qty-inc" aria-label="<?php esc_attr_e( 'Increase quantity', 'ttq' ); ?>">&plus;</button>
		</div>
		<p class="ttq-hint"><?php printf( esc_html__( 'Min %1$s / Max %2$s units', 'ttq' ), esc_html( number_format( $settings['min_quantity'] ) ), esc_html( number_format( $settings['max_quantity'] ) ) ); ?></p>
		<div class="ttq-field-error" data-error-for="quantity" role="alert"></div>
	</div>
</div>

<!-- ── Colors ────────────────────────────────────────────────────── -->
<div class="ttq-section-card">
	<div class="ttq-section-card__header">
		<span class="ttq-section-card__icon" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
		</span>
		<span class="ttq-section-card__title"><?php esc_html_e( 'Select Color(s)', 'ttq' ); ?> <span class="req">*</span></span>
		<span class="ttq-section-card__hint"><?php esc_html_e( 'Choose one or more', 'ttq' ); ?></span>
	</div>

	<div class="ttq-color-grid" role="group" aria-label="<?php esc_attr_e( 'Select colors', 'ttq' ); ?>">
		<?php foreach ( $settings['colors'] as $color ) : ?>
			<label class="ttq-color-swatch" style="--ttq-swatch-color: <?php echo esc_attr( $color['hex'] ); ?>">
				<input type="checkbox" name="colors[]" value="<?php echo esc_attr( $color['key'] ); ?>" />
				<span class="ttq-color-swatch__dot" aria-hidden="true"></span>
				<span class="ttq-color-swatch__label"><?php echo esc_html( $color['label'] ); ?></span>
			</label>
		<?php endforeach; ?>
	</div>

	<div class="ttq-custom-color-wrap">
		<label class="ttq-field-label" for="ttq-custom-color"><?php esc_html_e( 'Other / Custom Color', 'ttq' ); ?> <span class="ttq-optional"><?php esc_html_e( 'Optional', 'ttq' ); ?></span></label>
		<input type="text" id="ttq-custom-color" name="custom_color" class="ttq-input ttq-custom-color-input" placeholder="<?php esc_attr_e( 'e.g. Navy Blue, Forest Green, Maroon...', 'ttq' ); ?>" />
	</div>

	<p class="ttq-color-note"><?php esc_html_e( 'Sample requests are limited to 2 color options. When placing a full order, you may select more than 2 colors.', 'ttq' ); ?></p>

	<div class="ttq-field-error" data-error-for="colors" role="alert"></div>
</div>

<!-- ── Sizes ─────────────────────────────────────────────────────── -->
<div class="ttq-section-card">
	<div class="ttq-section-card__header">
		<span class="ttq-section-card__icon" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 6H3"/><path d="M10 12H3"/><path d="M15 18H3"/></svg>
		</span>
		<span class="ttq-section-card__title"><?php esc_html_e( 'Select Tick Tweezers Size(s)', 'ttq' ); ?> <span class="req">*</span></span>
		<span class="ttq-section-card__hint"><?php esc_html_e( 'Multiple sizes may be selected. Multiple Sizes Allowed.', 'ttq' ); ?></span>
	</div>

	<div class="ttq-size-grid ttq-size-grid--wide" role="group" aria-label="<?php esc_attr_e( 'Select sizes', 'ttq' ); ?>">
		<?php foreach ( $settings['sizes'] as $size ) : ?>
			<label class="ttq-size-chip ttq-size-chip--wide">
				<input type="checkbox" name="sizes[]" value="<?php echo esc_attr( $size['key'] ); ?>" />
				<span><?php echo esc_html( $size['label'] ); ?></span>
			</label>
		<?php endforeach; ?>
	</div>
	<div class="ttq-field-error" data-error-for="sizes" role="alert"></div>
</div>

<!-- ── Personalization ───────────────────────────────────────────── -->
<div class="ttq-section-card">
	<div class="ttq-section-card__header">
		<span class="ttq-section-card__icon" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
		</span>
		<span class="ttq-section-card__title"><?php esc_html_e( 'Stamping / Personalization', 'ttq' ); ?></span>
	</div>

	<div class="ttq-field-group--split">
		<div>
			<label class="ttq-field-label" for="ttq-side1"><?php esc_html_e( 'Side 1 Stamping', 'ttq' ); ?></label>
			<input type="text" id="ttq-side1" name="side1" maxlength="<?php echo esc_attr( $settings['personalization_max_chars'] ); ?>" class="ttq-js-char-counter" data-max="<?php echo esc_attr( $settings['personalization_max_chars'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. your website or phone', 'ttq' ); ?>" />
			<p class="ttq-char-counter"><span class="ttq-js-char-count">0</span>/<?php echo esc_html( $settings['personalization_max_chars'] ); ?> <?php esc_html_e( 'chars', 'ttq' ); ?></p>
			<div class="ttq-field-error" data-error-for="side1" role="alert"></div>
		</div>
		<div>
			<label class="ttq-field-label" for="ttq-side2"><?php esc_html_e( 'Side 2 Stamping', 'ttq' ); ?> <span class="ttq-optional"><?php esc_html_e( 'Optional', 'ttq' ); ?></span></label>
			<input type="text" id="ttq-side2" name="side2" maxlength="<?php echo esc_attr( $settings['personalization_max_chars'] ); ?>" class="ttq-js-char-counter" data-max="<?php echo esc_attr( $settings['personalization_max_chars'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. a tagline or message', 'ttq' ); ?>" />
			<p class="ttq-char-counter"><span class="ttq-js-char-count">0</span>/<?php echo esc_html( $settings['personalization_max_chars'] ); ?> <?php esc_html_e( 'chars', 'ttq' ); ?></p>
			<div class="ttq-field-error" data-error-for="side2" role="alert"></div>
		</div>
	</div>
</div>

<!-- ── Logo Upload ───────────────────────────────────────────────── -->
<div class="ttq-section-card">
	<div class="ttq-section-card__header">
		<span class="ttq-section-card__icon" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
		</span>
		<span class="ttq-section-card__title"><?php esc_html_e( 'Upload Your Logo / Branding Artwork', 'ttq' ); ?></span>
		<span class="ttq-section-card__hint"><?php esc_html_e( 'Optional', 'ttq' ); ?></span>
	</div>

	<div class="ttq-uploader ttq-js-uploader">
		<div class="ttq-uploader__dropzone ttq-js-dropzone" tabindex="0" role="button"
			aria-label="<?php esc_attr_e( 'Drag and drop your logo here, or browse files', 'ttq' ); ?>">
			<svg width="36" height="36" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
			<p><strong><?php esc_html_e( 'Drag & Drop', 'ttq' ); ?></strong> <?php esc_html_e( 'or', 'ttq' ); ?> <span class="ttq-link-btn"><?php esc_html_e( 'Browse File', 'ttq' ); ?></span></p>
			<p class="ttq-hint"><?php printf( esc_html__( 'Accepted: %1$s — Max %2$sMB', 'ttq' ), esc_html( strtoupper( $settings['allowed_file_types'] ) ), esc_html( $settings['max_upload_mb'] ) ); ?></p>
			<input type="file" id="ttq-logo-input" class="ttq-visually-hidden ttq-js-file-input" accept="<?php echo esc_attr( '.' . str_replace( ',', ',.', $settings['allowed_file_types'] ) ); ?>" />
		</div>

		<div class="ttq-uploader__progress ttq-js-upload-progress" hidden>
			<div class="ttq-uploader__progress-bar"><span class="ttq-js-progress-fill"></span></div>
			<span class="ttq-js-progress-label">0%</span>
		</div>

		<div class="ttq-uploader__preview ttq-js-upload-preview" hidden>
			<img class="ttq-js-preview-img" alt="<?php esc_attr_e( 'Logo preview', 'ttq' ); ?>" />
			<span class="ttq-js-preview-name"></span>
			<button type="button" class="ttq-link-btn ttq-js-upload-replace"><?php esc_html_e( 'Replace', 'ttq' ); ?></button>
			<button type="button" class="ttq-link-btn ttq-link-btn--danger ttq-js-upload-remove"><?php esc_html_e( 'Remove', 'ttq' ); ?></button>
		</div>
	</div>
	<div class="ttq-field-error" data-error-for="logo" role="alert"></div>
	<input type="hidden" name="logo_token" class="ttq-js-logo-token" value="" />
</div>

<div class="ttq-nav-row">
	<button type="button" class="ttq-btn ttq-btn--ghost ttq-js-back"><span aria-hidden="true">&larr;</span> <?php esc_html_e( 'Back', 'ttq' ); ?></button>
	<button type="button" class="ttq-btn ttq-btn--primary ttq-js-next"><?php esc_html_e( 'Continue', 'ttq' ); ?> <span aria-hidden="true">&rarr;</span></button>
</div>
