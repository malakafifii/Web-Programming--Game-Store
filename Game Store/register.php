<?php
require_once __DIR__ . '/includes/db.php';

$registerError = '';
$registerSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');
	$confirmPassword = trim($_POST['confirm_password'] ?? '');

	if ($password !== $confirmPassword) {
		$registerError = 'Passwords do not match.';
	} else {
		try {
			createBuyerAccount($username, $password);
			$_SESSION['success_message'] = 'Account created successfully! You can now log in.';
			header('Location: login.php');
			exit;
		} catch (Throwable $exception) {
			$registerError = $exception->getMessage();
		}
	}
}

include 'includes/header.php';
?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Create account</p>
			<h1>Join as a buyer.</h1>
			<p>Make an account to shop with a saved identity, while the admin account stays separate for catalog management.</p>
		</section>

		<section class="content-grid">
			<form class="form-panel two-col" action="register.php" method="post">
				<h2>Sign up</h2>
				<?php if ($registerSuccess !== ''): ?>
					<p class="form-success"><?php echo htmlspecialchars($registerSuccess); ?></p>
				<?php endif; ?>
				<?php if ($registerError !== ''): ?>
					<p class="form-error"><?php echo htmlspecialchars($registerError); ?></p>
				<?php endif; ?>
				<div class="form-grid">
					<label class="field full-col">Username<input type="text" name="username" required></label>
					<label class="field full-col">Password<input type="password" name="password" required></label>
					<label class="field full-col">Confirm Password<input type="password" name="confirm_password" required></label>
				</div>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Create Account</button>
					<a class="btn btn-secondary" href="login.php">Back to Login</a>
				</div>
			</form>

			<aside class="panel two-col">
				<h2>How it works</h2>
				<ul class="stack-list">
					<li><span>Buyers</span><strong>Create accounts and shop normally</strong></li>
					<li><span>Admin</span><strong>Uses the default amin / admin credentials</strong></li>
					<li><span>Guest browsing</span><strong>Still available without logging in</strong></li>
				</ul>
			</aside>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>