<?php include 'includes/header.php'; ?>

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
			<form class="form-panel two-col" action="#" method="post">
				<h2>Send a message</h2>
				<div class="form-grid">
					<label class="field">First name<input type="text" name="first_name" placeholder="Jordan"></label>
					<label class="field">Last name<input type="text" name="last_name" placeholder="Lee"></label>
					<label class="field">Email<input type="email" name="email" placeholder="you@example.com"></label>
					<label class="field">Topic<select name="topic"><option>Order support</option><option>Product question</option><option>Partnership</option></select></label>
				</div>
				<label class="field" style="display:block; margin-top:14px;">Message<textarea name="message" placeholder="How can we help?"></textarea></label>
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