<?php
/**
 * @var array $submission
 * @var array $settings
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html>
<body style="font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:24px;">
	<table role="presentation" width="100%" style="max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;">
		<tr><td style="background:#b71c2b;padding:20px 24px;color:#fff;">
			<h1 style="margin:0;font-size:20px;">New Quote Request</h1>
		</td></tr>
		<tr><td style="padding:24px;">
			<h2 style="font-size:16px;color:#b71c2b;">Product</h2>
			<p><?php echo esc_html( $product_label ); ?> &mdash; Qty: <?php echo esc_html( $submission['quantity'] ); ?></p>
			<p>Colors: <?php echo esc_html( implode( ', ', $submission['colors'] ) ); ?><br/>
			Sizes: <?php echo esc_html( implode( ', ', $submission['sizes'] ) ); ?></p>
			<p>Side 1: <?php echo esc_html( $submission['side1'] ); ?><br/>
			Side 2: <?php echo esc_html( $submission['side2'] ); ?></p>

			<?php if ( ! empty( $submission['logo_url'] ) ) : ?>
				<h2 style="font-size:16px;color:#b71c2b;">Logo</h2>
				<p><img src="<?php echo esc_url( $submission['logo_url'] ); ?>" alt="Logo" style="max-width:160px;" /></p>
			<?php endif; ?>

			<h2 style="font-size:16px;color:#b71c2b;">Contact</h2>
			<p>
				<?php echo esc_html( $submission['name'] ); ?><br/>
				<?php echo esc_html( $submission['organization'] ); ?><br/>
				<?php echo esc_html( $submission['phone'] ); ?><br/>
				<?php echo esc_html( $submission['email'] ); ?><br/>
				Free Sample: <?php echo esc_html( ucfirst( $submission['free_sample'] ) ); ?>
			</p>

			<h2 style="font-size:16px;color:#b71c2b;">Delivery Address</h2>
			<p><?php echo nl2br( esc_html( $submission['address'] ) ); ?></p>
		</td></tr>
	</table>
</body>
</html>
