<?php

/** @var array $settings */
if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="ttq-step-head">
	<h3><?php esc_html_e('Choose Your Tick Prevention Solution', 'ttq'); ?></h3>
	<p class="ttq-sub">
		<?php esc_html_e('Select the product that best fits your needs. Both options can be customized with your logo.', 'ttq'); ?>
	</p>
</div>

<div class="ttq-product-grid" role="radiogroup"
	aria-label="<?php esc_attr_e('Choose your tick prevention solution', 'ttq'); ?>">
	<?php
	$dynamic_products = TTQ_Admin::get_dynamic_products();
	foreach ($dynamic_products as $i => $product) :
		$is_featured = ! empty($product['featured']) && $product['featured'] !== '0' && $product['featured'] !== 'false';
		// Parse features: pipe-separated string from admin, or fallback array
		$features_raw = isset($product['features']) ? $product['features'] : '';
		$features = array_filter(array_map('trim', explode('|', $features_raw)));
		if (empty($features)) {
			// Hardcoded fallback when no features are set
			$features = array(
				__('Removes ticks safely', 'ttq'),
				__('Precision stainless steel tips', 'ttq'),
				__('Personalized with your logo', 'ttq'),
			);
		}
	?>
		<label class="ttq-product-card<?php echo $is_featured ? ' is-featured' : ''; ?>"
			for="ttq-product-<?php echo esc_attr($product['key']); ?>">

			<?php if ($is_featured) : ?>
				<span class="ttq-product-card__ribbon">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path
							d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
					</svg>
					<?php esc_html_e('MOST POPULAR', 'ttq'); ?>
				</span>
			<?php endif; ?>

			<input type="radio" name="product" id="ttq-product-<?php echo esc_attr($product['key']); ?>"
				value="<?php echo esc_attr($product['key']); ?>" <?php checked(0 === $i); ?>
				class="ttq-product-card__radio" />
			<span class="ttq-product-card__radio-ui" aria-hidden="true"></span>

			<span class="ttq-product-card__media">
				<?php if (! empty($product['image'])) : ?>
					<img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['label']); ?>"
						loading="lazy" />
				<?php else : ?>
					<span class="ttq-product-card__placeholder" aria-hidden="true">
						<svg viewBox="0 0 160 120" width="160" height="120">
							<!-- Tick tweezers illustration placeholder -->
							<rect x="10" y="20" width="18" height="80" rx="4" fill="#c62828" transform="rotate(-15 19 60)" />
							<rect x="35" y="20" width="18" height="80" rx="4" fill="#e53935" transform="rotate(-5 44 60)" />
							<rect x="62" y="20" width="18" height="80" rx="4" fill="#1565c0" transform="rotate(5 71 60)" />
							<rect x="88" y="20" width="18" height="80" rx="4" fill="#2e7d32" transform="rotate(15 97 60)" />
							<rect x="114" y="20" width="18" height="80" rx="4" fill="#f57c00" transform="rotate(25 123 60)" />
							<!-- Tick bug -->
							<ellipse cx="130" cy="30" rx="9" ry="11" fill="#333" />
							<circle cx="130" cy="20" r="5" fill="#333" />
							<line x1="122" y1="26" x2="115" y2="22" stroke="#333" stroke-width="1.5" stroke-linecap="round" />
							<line x1="122" y1="30" x2="113" y2="30" stroke="#333" stroke-width="1.5" stroke-linecap="round" />
						</svg>
					</span>
				<?php endif; ?>
			</span>

			<span class="ttq-product-card__body">
				<span class="ttq-product-card__title-row">
					<span class="ttq-product-card__title"><?php echo esc_html($product['label']); ?></span>
					<?php if (! empty($product['price'])) : ?>
						<span class="ttq-product-card__price">
							<?php esc_html_e('Starting at', 'ttq'); ?>
							<strong>$<?php echo esc_html($product['price']); ?></strong> <?php esc_html_e('each', 'ttq'); ?>
						</span>
					<?php endif; ?>
				</span>

				<span class="ttq-product-card__features">
					<?php foreach ($features as $feature) : ?>
						<span class="ttq-product-card__feature">
							<span class="ttq-check" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
									stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="20 6 9 17 4 12" />
								</svg>
							</span>
							<?php echo esc_html($feature); ?>
						</span>
					<?php endforeach; ?>
				</span>

				<?php if (! empty($product['best_for'])) : ?>
					<span class="ttq-product-card__bestfor">
						<span class="ttq-product-card__bestfor-icon" aria-hidden="true">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
								stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
								<circle cx="9" cy="7" r="4" />
								<path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
							</svg>
						</span>
						<span>
							<strong><?php esc_html_e('Best For:', 'ttq'); ?></strong>
							<?php echo esc_html($product['best_for']); ?>
						</span>
					</span>
				<?php endif; ?>

			</span>
			<span class="ttq-product-card__footer">
				<span class="ttq-product-card__footer-text">
					<?php echo $is_featured ? esc_html__('See Kit Contents', 'ttq') : esc_html__("What's Included", 'ttq'); ?>
					<span aria-hidden="true">&rsaquo;</span>
				</span>
				<span class="ttq-product-card__footer-arrow" aria-hidden="true">&rsaquo;</span>
			</span>
		</label>
	<?php endforeach; ?>
</div>

<div class="ttq-field-error" data-error-for="product" role="alert"></div>

<div class="ttq-nav-row ttq-nav-row--end">
	<button type="button" class="ttq-btn ttq-btn--primary ttq-js-next"><?php esc_html_e('Continue', 'ttq'); ?> <span
			aria-hidden="true">&rarr;</span></button>
</div>