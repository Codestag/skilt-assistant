<?php
/**
 * Contact Form Shortcode
 *
 * Displays a contact form.
 *
 * @package Skilt Assistant
 * @subpackage Skilt
 */
function skilt_contact_form_sc() {

	// Get values submitted by form
	if ( isset( $_POST['sendemail'] ) ) {
		$name    = sanitize_text_field( $_POST['cname'] );
		$email   = sanitize_email( $_POST['email'] );
		$website = esc_url( $_POST['website'] );
		$message = sanitize_text_field( $_POST['message'] );
	}

	$sendemail = ! empty( $_POST['sendemail'] );
	$mail_sent = false;

	// Form submitted?
	if ( ! empty( $sendemail ) && ! empty( $name ) && ! empty( $email ) && ! empty( $message ) ) {
		$mailto   = get_bloginfo( 'admin_email' );
		$mailsubj = __( 'Contact Form Submission from ', 'skilt-assistant') . get_bloginfo( 'name' );
		$mailhead = 'From: ' . $name . ' <' . $email . ">\n";

		$mailbody  = "Name: {$name}\n\n";
		$mailbody .= "Email: {$email}\n\n";
		$mailbody .= "Website: {$website}\n\n";
		$mailbody .= "Message:\n {$message}";

		// Send email
		wp_mail( $mailto, $mailsubj, $mailbody, $mailhead );

		// Set message for this page and clear vars
		$msg       = __( 'Your message has been sent.', 'skilt-assistant' );
		$mail_sent = true;

		$name = $email = $website = $message = '';
	} elseif ( ! empty( $sendemail ) && ! is_email( $email ) ) {
		$msg = __( 'Please enter a valid email address.', 'skilt-assistant' );
	} elseif ( ! empty( $sendemail ) && empty( $name ) ) {
		$msg = __( 'Please enter your name.', 'skilt-assistant' );
	} elseif ( ! empty( $sendemail ) && ! empty( $name ) && empty( $email ) ) {
		$msg = __( 'Please enter your email address.', 'skilt-assistant' );
	} elseif ( ! empty( $sendemail ) && empty( $message ) ) {
		$msg = __( 'Please enter your message.', 'skilt-assistant' );
	}
	?>

<div class="form-wrapper">
		<div class="inside">
			<h1 class="contact-form-title"><?php echo apply_filters( 'skilt_contact_form_title', __( 'Send a direct message', 'skilt-assistant' ) ); // WPCS: XSS ok. ?></h1>

			<?php if ( ! empty( $msg ) ) : ?>
			<div class="stag-alert stag-alert--<?php echo ( $mail_sent ) ? 'green' : 'red'; ?>"><?php echo $msg; ?></div>
			<?php endif; ?>

			<form id="contact-form" class="grid contact-form" method="post" action="<?php get_permalink(); ?>">
				<div class="form-row unit one-of-two">
					<input type="text" name="cname" placeholder="<?php esc_attr_e( 'Name', 'skilt-assistant' ); ?>" required>
				</div>

				<div class="form-row unit one-of-two">
					<input type="email" name="email" placeholder="<?php esc_attr_e( 'Email', 'skilt-assistant' ); ?>" required>
				</div>

				<div class="form-row unit span-grid">
					<input type="url" name="website" placeholder="<?php esc_attr_e( 'Website', 'skilt-assistant' ); ?>">
				</div>

				<div class="form-row unit span-grid">
					<textarea name="message" rows="7" placeholder="<?php esc_attr_e( 'Your Message Here', 'skilt-assistant' ); ?>" required></textarea>
				</div>

				<div class="form-row unit span-grid">
					<input type="submit" name="sendemail" value="<?php esc_attr_e( 'Submit', 'skilt-assistant' ); ?>">
				</div>
			</form>
		</div>
	</div>

	<?php
}
add_shortcode( 'skilt_contact_form', 'skilt_contact_form_sc' );
?>
