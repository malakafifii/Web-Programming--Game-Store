<?php
require_once __DIR__ . '/includes/db.php';

$contactError = '';
$contactSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $topic = $_POST['topic'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
        $contactError = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contactError = 'Please enter a valid email address.';
    } elseif (strlen($message) < 10) {
        $contactError = 'Message must be at least 10 characters long.';
    } else {
        // In a real app, you might save this to DB or send an email.
        // For this demo, we'll just show success.
        $contactSuccess = 'Thank you for reaching out! We will get back to you soon.';
        // Reset fields
        $firstName = $lastName = $email = $message = '';
    }
}

include 'includes/header.php'; 
?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>
		
		<section class="page-hero">
			<p class="eyebrow">Contact</p>
			<h1>Talk to the store team.</h1>
			<p>Use this page for support questions, product requests, order issues, or partnership inquiries.</p>
		</section>

		<section class="content-grid">
			<form class="form-panel two-col" action="contact.php" method="post">
				<h2>Send a message</h2>
				<?php if ($contactSuccess !== ''): ?>
					<p class="form-success"><?php echo htmlspecialchars($contactSuccess); ?></p>
				<?php endif; ?>
				<?php if ($contactError !== ''): ?>
					<p class="form-error"><?php echo htmlspecialchars($contactError); ?></p>
				<?php endif; ?>
				<div class="form-grid">
					<label class="field">First name<input type="text" name="first_name" placeholder="Jordan" required value="<?php echo htmlspecialchars($firstName ?? ''); ?>"></label>
					<label class="field">Last name<input type="text" name="last_name" placeholder="Lee" required value="<?php echo htmlspecialchars($lastName ?? ''); ?>"></label>
					<label class="field">Email<input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($email ?? ''); ?>"></label>
					<label class="field">Topic<select name="topic" required>
						<option value="Order support" <?php echo (isset($topic) && $topic === 'Order support') ? 'selected' : ''; ?>>Order support</option>
						<option value="Product question" <?php echo (isset($topic) && $topic === 'Product question') ? 'selected' : ''; ?>>Product question</option>
						<option value="Partnership" <?php echo (isset($topic) && $topic === 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
					</select></label>
				</div>
				<label class="field" style="display:block; margin-top:14px;">Message<textarea name="message" placeholder="How can we help?" required minlength="10"><?php echo htmlspecialchars($message ?? ''); ?></textarea></label>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Send Message</button>
					<a class="btn btn-secondary" href="mailto:support@gamestore.local">Email Us</a>
				</div>
			</form>

			<aside class="panel two-col">
				<h2>Store info</h2>
				<p class="muted">Support hours</p>
				<p>Monday to Friday, 9:00 AM to 6:00 PM</p>
				<p class="muted">Location</p>
				<p>Online-first retail experience with pickup-friendly support flow.</p>
				<p class="muted">Response time</p>
				<p>Most messages get a reply within one business day.</p>
			</aside>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>