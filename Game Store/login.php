<?php
require_once __DIR__ . '/includes/db.php';

$loginError = '';
$successMessage = '';

if (!empty($_SESSION['success_message'])) {
	$successMessage = $_SESSION['success_message'];
	unset($_SESSION['success_message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');

	$user = authenticateUser($username, $password);
	if ($user !== null) {
		$_SESSION['user'] = $user['username'];
		$_SESSION['user_role'] = $user['role'];
		$_SESSION['user_id'] = (int) $user['id'];
		header('Location: ' . ($user['role'] === 'admin' ? 'admin.php' : 'index.php'));
		exit;
	}

	$loginError = 'Invalid username or password.';
}

include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Login</p>
			<h1>Access your account.</h1>
			<p>Keep the login experience simple, focused, and consistent with the rest of the store design.</p>
		</section>

		<section class="content-grid">
			<form class="form-panel two-col" action="login.php" method="post">
				<h2>Sign in</h2>
				<p>Use your buyer account or the default admin account. Admin defaults to username <strong>amin</strong> and password <strong>admin</strong>.</p>
				<?php if ($successMessage !== ''): ?>
					<p class="form-success"><?php echo htmlspecialchars($successMessage); ?></p>
				<?php endif; ?>
				<?php if ($loginError !== ''): ?>
					<p class="form-error"><?php echo htmlspecialchars($loginError); ?></p>
				<?php endif; ?>
				<div class="form-grid">
					<label class="field full-col">Username<input type="text" name="username" placeholder="amin"></label>
					<label class="field full-col">Password<input type="password" name="password" placeholder="admin"></label>
				</div>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Login</button>
					<a class="btn btn-secondary" href="register.php">Create Account</a>
				</div>
			</form>

		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>