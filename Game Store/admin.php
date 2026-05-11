<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// Hardcoded product categories
$productCategories = [
    'Console',
    'Video Games',
    'Collectibles',
    'Toys',
    'Accessories',
    'Board Games',
    'Trading Cards',
    'Merchandise'
];

$successMessage = '';
$errorMessage = '';

$imagePath = null;

if (!empty($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$editMode = false;
$editProduct = null;

// If editing, load product to prefill form
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editProduct = getProductById($editId);
    if ($editProduct) {
        $editMode = true;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    try {
        delteProduct($deleteId);
        $_SESSION['success_message'] = "Product deleted successfully!";
        header("Location: admin.php");
        exit;
    } catch (Throwable $e) {
        $errorMessage = "Error deleting product: " . $e->getMessage();
    }
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
        if (!empty($_POST['edit_id'])) {
            $id = (int) $_POST['edit_id'];
            // If no new image uploaded, pass null so existing image stays
            $img = $imagePath !== null ? $imagePath : null;
            updateProduct($id, [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? '',
                'category' => $_POST['category'] ?? '',
                'image' => $img,
                'stock_status' => $_POST['stock_status'] ?? 'in_stock'
            ]);

            $_SESSION['success_message'] = "Product updated successfully!";
            header("Location: admin.php");
            exit;
        } else {
            createProduct([
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? '',
                'category' => $_POST['category'] ?? '',
                'image' => $imagePath,
                'stock_status' => $_POST['stock_status'] ?? 'in_stock'
            ]);

            $_SESSION['success_message'] = "Product added successfully!";
            header("Location: admin.php");
            exit;
        }

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
                        <label class="field full-col">Name<input type="text" name="name" required value="<?php echo isset($editProduct) ? htmlspecialchars($editProduct['name']) : ''; ?>"></label>
                        <label class="field full-col">Description<textarea name="description" rows="5" required><?php echo isset($editProduct) ? htmlspecialchars($editProduct['description']) : ''; ?></textarea></label>
                        <label class="field">Price<input type="number" name="price" step="0.01" min="0.01" required value="<?php echo isset($editProduct) ? htmlspecialchars($editProduct['price']) : ''; ?>"></label>
                        <label class="field">Category<select name="category" required>
                            <option value="">Select a category</option>
                            <?php foreach ($productCategories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>"<?php echo (isset($editProduct) && $editProduct['category'] === $cat) ? ' selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                            <?php endforeach; ?>
                        </select></label>

                        <label class="field full-col">Product Image
                            <input type="file" name="image" accept="image/*">
                            <?php if (isset($editProduct) && !empty($editProduct['image'])): ?>
                                <div>Current image: <?php echo htmlspecialchars($editProduct['image']); ?></div>
                            <?php endif; ?>
                        </label>

                        <label class="field full-col">Stock Status
                            <select name="stock_status">
                                <option value="in_stock"<?php echo (isset($editProduct) && ($editProduct['stock_status'] ?? '') === 'in_stock') ? ' selected' : ''; ?>>In Stock</option>
                                <option value="out_of_stock"<?php echo (isset($editProduct) && ($editProduct['stock_status'] ?? '') === 'out_of_stock') ? ' selected' : ''; ?>>Out of stock</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-actions">
                        <?php if ($editMode): ?>
                            <input type="hidden" name="edit_id" value="<?php echo (int) $editProduct['id']; ?>">
                            <button class="btn btn-primary" type="submit">Update product</button>
                            <a class="btn btn-secondary" href="admin.php">Cancel</a>
                        <?php else: ?>
                            <button class="btn btn-primary" type="submit">Save product</button>
                            <a class="btn btn-secondary" href="products.php">View catalog</a>
                        <?php endif; ?>
                    </div>
                </form>

                <aside class="panel two-col">
                    <h2>Recent catalog items</h2>
                    <ul class="stack-list">
                        <?php foreach (array_slice($recentProducts, 0, 6) as $product): ?>
                            <li style="display: flex; align-items: center; gap: 16px; justify-content: space-between;">
                                <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                <span style="flex: 1; text-align: center; font-size: 0.9em; color: var(--text-secondary);"><?php echo htmlspecialchars($product['category']); ?></span>
                                <div style="display: flex; gap: 8px;">
                                    <a class="btn btn-small" href="admin.php?edit=<?php echo (int)$product['id']; ?>">Edit</a>
                                    <a class="btn btn-small" href="admin.php?delete=<?php echo (int)$product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            </section>
        </main>
    </div>

<?php include 'includes/footer.php'; ?>