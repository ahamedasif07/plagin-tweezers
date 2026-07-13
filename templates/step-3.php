<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ttq-step-head">
	<h3><?php esc_html_e( 'Contact Details', 'ttq' ); ?></h3>
	<p class="ttq-sub"><?php esc_html_e( 'Provide your details so we can prepare accurate shipping and quote information.', 'ttq' ); ?></p>
</div>

<div class="ttq-contact-card">

	<div class="ttq-contact-card__section">
		<div class="ttq-contact-card__section-header">
			<span class="ttq-section-card__icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
			</span>
			<span><?php esc_html_e( 'Personal & Organization', 'ttq' ); ?></span>
		</div>

		<div class="ttq-field-group">
			<label class="ttq-field-label" for="ttq-org"><?php esc_html_e( 'Organization / Department / School', 'ttq' ); ?></label>
			<input type="text" id="ttq-org" name="organization" placeholder="<?php esc_attr_e( 'Organization name', 'ttq' ); ?>" />
		</div>

		<div class="ttq-field-group--split">
			<div>
				<label class="ttq-field-label" for="ttq-name"><?php esc_html_e( 'Contact Person', 'ttq' ); ?> <span class="req">*</span></label>
				<input type="text" id="ttq-name" name="name" placeholder="<?php esc_attr_e( 'Your full name', 'ttq' ); ?>" required />
				<div class="ttq-field-error" data-error-for="name" role="alert"></div>
			</div>
			<div>
				<label class="ttq-field-label" for="ttq-phone"><?php esc_html_e( 'Telephone', 'ttq' ); ?> <span class="req">*</span></label>
				<input type="tel" id="ttq-phone" name="phone" placeholder="<?php esc_attr_e( 'Contact number', 'ttq' ); ?>" required />
				<div class="ttq-field-error" data-error-for="phone" role="alert"></div>
			</div>
		</div>
	</div>

	<div class="ttq-contact-card__section">
		<div class="ttq-contact-card__section-header">
			<span class="ttq-section-card__icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
			</span>
			<span><?php esc_html_e( 'Email & Notifications', 'ttq' ); ?></span>
		</div>

		<div class="ttq-field-group">
			<label class="ttq-field-label" for="ttq-email"><?php esc_html_e( 'Email Address', 'ttq' ); ?> <span class="req">*</span></label>
			<input type="email" id="ttq-email" name="email" placeholder="example@domain.com" required />
			<div class="ttq-field-error" data-error-for="email" role="alert"></div>
		</div>

		<div class="ttq-field-group">
			<label class="ttq-field-label" for="ttq-freesample">
				<?php esc_html_e( 'Would you like a free sample?', 'ttq' ); ?>
			</label>
			<div class="ttq-radio-row">
				<label class="ttq-radio-pill">
					<input type="radio" name="free_sample" value="yes" />
					<span><?php esc_html_e( 'Yes', 'ttq' ); ?></span>
				</label>
				<label class="ttq-radio-pill">
					<input type="radio" name="free_sample" value="no" checked />
					<span><?php esc_html_e( 'No', 'ttq' ); ?></span>
				</label>
			</div>
		</div>
	</div>

	<div class="ttq-contact-card__section">
		<div class="ttq-contact-card__section-header">
			<span class="ttq-section-card__icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
			</span>
			<span><?php esc_html_e( 'Delivery Address', 'ttq' ); ?> <span class="req">*</span></span>
		</div>

		<div class="ttq-field-group">
			<textarea id="ttq-address" name="address" rows="3" placeholder="<?php esc_attr_e( 'Please enter complete shipping address including City, State and Zip Code', 'ttq' ); ?>" required></textarea>
			<div class="ttq-field-error" data-error-for="address" role="alert"></div>

			<div class="ttq-callout ttq-callout--warning">
				<span aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
				</span>
				<div>
					<strong><?php esc_html_e( 'PO Boxes are not accepted.', 'ttq' ); ?></strong>
					<p><?php esc_html_e( 'Please provide a physical address for delivery.', 'ttq' ); ?></p>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="ttq-nav-row">
	<button type="button" class="ttq-btn ttq-btn--ghost ttq-js-back"><span aria-hidden="true">&larr;</span> <?php esc_html_e( 'Back', 'ttq' ); ?></button>
	<button type="button" class="ttq-btn ttq-btn--primary ttq-js-next"><?php esc_html_e( 'Continue to Review', 'ttq' ); ?> <span aria-hidden="true">&rarr;</span></button>
</div>
