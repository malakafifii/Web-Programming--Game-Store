<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Login</p>
			<h1>Access your account.</h1>
			<p>Keep the login experience simple, focused, and consistent with the rest of the store design.</p>
		</section>

		<section class="content-grid">
			<form class="form-panel two-col" action="#" method="post">
				<h2>Sign in</h2>
				<div class="form-grid">
					<label class="field full-col">Email<input type="email" name="email" placeholder="you@example.com"></label>
					<label class="field full-col">Password<input type="password" name="password" placeholder="Enter your password"></label>
				</div>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Login</button>
					<a class="btn btn-secondary" href="#">Create Account</a>
				</div>
			</form>

			<aside class="panel two-col">
				<h2>Why sign in?</h2>
				<ul class="stack-list">
					<li><span>Faster checkout</span><strong>Saved details and shipping</strong></li>
					<li><span>Track orders</span><strong>See purchase history anytime</strong></li>
					<li><span>Wishlist items</span><strong>Keep favorites in one place</strong></li>
				</ul>
			</aside>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>