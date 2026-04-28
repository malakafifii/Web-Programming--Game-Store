<?php include 'includes/header.php'; ?>

<?php $query = isset($_GET['q']) ? trim($_GET['q']) : ''; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Search</p>
			<h1>Results for <?php echo $query !== '' ? htmlspecialchars($query) : 'your search'; ?>.</h1>
			<p>Use this layout for keyword-based product discovery, and connect the results to your backend later if needed.</p>
		</section>

		<section class="content-grid">
			<article class="panel full-col">
				<h2>Suggested results</h2>
				<div class="product-grid">
					<article class="product-card"><div class="product-art"></div><div class="product-copy"><h3>Search Match One</h3><p>Relevant item based on your query.</p><div class="product-meta"><span class="price">$39.99</span><a href="#" class="btn btn-small">View</a></div></div></article>
					<article class="product-card"><div class="product-art"></div><div class="product-copy"><h3>Search Match Two</h3><p>Another relevant item in the same category.</p><div class="product-meta"><span class="price">$59.99</span><a href="#" class="btn btn-small">View</a></div></div></article>
					<article class="product-card"><div class="product-art"></div><div class="product-copy"><h3>Search Match Three</h3><p>Popular recommendation from the store.</p><div class="product-meta"><span class="price">$24.99</span><a href="#" class="btn btn-small">View</a></div></div></article>
				</div>
			</article>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>
