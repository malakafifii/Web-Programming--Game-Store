<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$successMessage = '';
$errorMessage = '';

$imagePath = null;

if (!empty($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

        $imagePath = $targetFile;
    }

    try {
        createProduct([
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? '',
            'category' => $_POST['category'] ?? '',
            'image' => $imagePath,
            'stock_status' => $_POST['stock_status'] ?? 'in_stock'
        ]);

        $_SESSION['success_message'] = "Product added successfully!";
        header("Location: admin.php");
        exit;

    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
}

$recentProducts = getProducts();

include 'includes/header.php';
?>

    <div class="layout">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-pane">
            <section class="page-hero">
                <p class="eyebrow">Admin</p>
                <h1>Create product listings.</h1>
                <p>Add new toys, video games, board games, and accessories to the public catalog.</p>
            </section>

            <section class="content-grid">
                <form class="form-panel two-col" action="admin.php" method="post" enctype="multipart/form-data">
                    <h2>Add product</h2>

                    <?php if ($successMessage !== ''): ?>
                        <p class="form-success"><?php echo htmlspecialchars($successMessage); ?></p>
                    <?php endif; ?>

                    <?php if ($errorMessage !== ''): ?>
                        <p class="form-error"><?php echo htmlspecialchars($errorMessage); ?></p>
                    <?php endif; ?>

                    <div class="form-grid">
                        <label class="field full-col">Name<input type="text" name="name" required></label>
                        <label class="field full-col">Slug<input type="text" name="slug" placeholder="Auto-generated if blank"></label>
                        <label class="field full-col">Description<textarea name="description" rows="5" required></textarea></label>
                        <label class="field">Price<input type="number" name="price" step="0.01" min="0.01" required></label>
                        <label class="field">Category<input type="text" name="category" placeholder="Toys, Video Games, Board Games" required></label>
                        <!-- SKU removed per request -->

                        <label class="field full-col">Product Image
                            <input type="file" name="image" accept="image/*" required>
                        </label>

                        <label class="field full-col">Stock Status
                            <select name="stock_status">
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Save product</button>
                        <a class="btn btn-secondary" href="products.php">View catalog</a>
                    </div>
                </form>

                <aside class="panel two-col">
                    <h2>Recent catalog items</h2>
                    <ul class="stack-list">
                        <?php foreach (array_slice($recentProducts, 0, 6) as $product): ?>
                            <li>
                                <span><?php echo htmlspecialchars($product['category']); ?></span>
                                <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            </section>
        </main>
    </div>

<?php include 'includes/footer.php'; ?>