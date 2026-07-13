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
			<h1 style="margin:0;font-size:20px;"><?php echo esc_html( $settings['company_name'] ); ?></h1>
		</td></tr>
		<tr><td style="padding:24px;">
			<p>Hi <?php echo esc_html( $submission['name'] ); ?>,</p>
			<p>Thanks for your quote request! Here's a summary of what you submitted:</p>
			<ul>
				<li>Product: <?php echo esc_html( $product_label ); ?></li>
				<li>Quantity: <?php echo esc_html( $submission['quantity'] ); ?></li>
				<li>Colors: <?php echo esc_html( implode( ', ', $submission['colors'] ) ); ?></li>
				<li>Sizes: <?php echo esc_html( implode( ', ', $submission['sizes'] ) ); ?></li>
				<li>Free Sample Requested: <?php echo esc_html( ucfirst( $submission['free_sample'] ) ); ?></li>
			</ul>
			<p><?php echo esc_html( $settings['success_message'] ); ?></p>
			<p>&mdash; The <?php echo esc_html( $settings['company_name'] ); ?> Team</p>
		</td></tr>
	</table>
</body>
</html>
