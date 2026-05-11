<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$__dbConfig = [
    'host' => 'localhost',
    'dbname' => 'toy_store',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
];

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s;charset=%s', $__dbConfig['host'], $__dbConfig['dbname'], $__dbConfig['charset']),
        $__dbConfig['user'],
        $__dbConfig['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo '<pre>Database connection error: ' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}

function db(): PDO
{
    global $pdo;
    return $pdo;
}

function getProducts(?int $limit = null): array
{
    $sql = 'SELECT * FROM products ORDER BY id ASC';
    if ($limit !== null) {
        $sql .= ' LIMIT :limit';
    }
    $stmt = db()->prepare($sql);
    if ($limit !== null) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCategories(): array
{
    $stmt = db()->query('SELECT DISTINCT category FROM products ORDER BY category ASC');
    return array_column($stmt->fetchAll(), 'category');
}

function getProductsByCategory(?string $category = null): array
{
    if ($category === null || $category === '') {
        return getProducts();
    }

    $stmt = db()->prepare('SELECT * FROM products WHERE category = :category ORDER BY id ASC');
    $stmt->execute(['category' => $category]);
    return $stmt->fetchAll();
}

function getProductById(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
    return $product !== false ? $product : null;
}

function searchProducts(string $query): array
{
    $sql = 'SELECT * FROM products WHERE name LIKE :q1 OR description LIKE :q2 ORDER BY id ASC';
    $stmt = db()->prepare($sql);
    $param = '%' . $query . '%';
    $stmt->execute(['q1' => $param, 'q2' => $param]);
    return $stmt->fetchAll();
}

function getFeaturedProducts(int $limit = 4): array
{
    return getProducts($limit);
}

function getOrders(): array
{
    $stmt = db()->query(
        'SELECT
            o.order_id,
            o.email,
            o.`date`,
            o.payment,
            oi.product_Id,
            oi.quantity,
            oi.price,
            p.name AS product_name
         FROM orders o
         LEFT JOIN order_items oi ON oi.order_id = o.order_id
         LEFT JOIN products p ON p.id = oi.product_Id
         ORDER BY o.`date` DESC, o.order_id DESC, oi.order_item_id ASC'
    );

    $groupedOrders = [];

    foreach ($stmt->fetchAll() as $row) {
        $orderId = (int) $row['order_id'];

        if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = [
                'order_id' => $orderId,
                'email' => $row['email'],
                'date' => $row['date'],
                'payment' => $row['payment'],
                'items' => [],
                'total' => 0.0,
                'item_count' => 0,
            ];
        }

        if (!empty($row['product_Id'])) {
            $quantity = (int) ($row['quantity'] ?? 0);
            $price = (float) ($row['price'] ?? 0);

            $groupedOrders[$orderId]['items'][] = [
                'product_id' => (int) $row['product_Id'],
                'product_name' => $row['product_name'] ?? ('Product #' . (int) $row['product_Id']),
                'quantity' => $quantity,
                'price' => $price,
                'line_total' => $price * $quantity,
            ];
            $groupedOrders[$orderId]['total'] += $price * $quantity;
            $groupedOrders[$orderId]['item_count'] += $quantity;
        }
    }

    return array_values($groupedOrders);
}

function ensureAuthTables(): void
{
    db()->exec(
        'CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role ENUM("admin", "buyer") NOT NULL DEFAULT "buyer",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $adminUsername = 'admin';
    $adminPassword = 'admin';
    $stmt = db()->prepare('INSERT IGNORE INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)');
    $stmt->execute([
        'username' => $adminUsername,
        'password_hash' => password_hash($adminPassword, PASSWORD_DEFAULT),
        'role' => 'admin',
    ]);
}

ensureAuthTables();

function getUserByUsername(string $username): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    return $user !== false ? $user : null;
}


function createBuyerAccount(string $username, string $password ): int
{
    $username = trim($username);
    $password = trim($password);

    if ($username === '' || $password === '') {
        throw new InvalidArgumentException('Username and password are required.');
    }

    if (getUserByUsername($username) !== null) {
        throw new InvalidArgumentException('That username is already taken.');
    }

    $stmt = db()->prepare('INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)');
    $stmt->execute([
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'buyer',
    ]);

    return (int) db()->lastInsertId();
}

function authenticateUser(string $username, string $password): ?array
{
    $user = getUserByUsername($username);
    if ($user === null) {
        return null;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return null;
    }

    return $user;
}

// Slug utilities removed — project now uses numeric IDs.

function createProduct(array $productData): int
{
    $name = trim($productData['name'] ?? '');
    $description = trim($productData['description'] ?? '');
    $price = (float) ($productData['price'] ?? 0);
    $category = trim($productData['category'] ?? '');

    $image = $productData['image'] ?? null;
    $stockStatus = $productData['stock_status'] ?? 'in_stock';

    if ($name === '' || $description === '' || $category === '') {
        throw new InvalidArgumentException('Name, description, category and price are required.');
    }

    if ($price <= 0) {
        throw new InvalidArgumentException('Price must be greater than zero.');
    }

    $stmt = db()->prepare(
        'INSERT INTO products 
        (name, description, price, category, image, stock_status)
        VALUES 
        (:name, :description, :price, :category, :image, :stock_status)'
    );

    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'category' => $category,
        'image' => $image,
        'stock_status' => $stockStatus,
    ]);

    return (int) db()->lastInsertId();
}

function deleteProduct(int $productId): int{
    $stmt = db()->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute(['id' => $productId]);
    return $stmt->rowCount();
}

function updateProduct(int $id, array $productData): bool
{
    $name = trim($productData['name'] ?? '');
    $description = trim($productData['description'] ?? '');
    $price = (float) ($productData['price'] ?? 0);
    $category = trim($productData['category'] ?? '');
    $image = $productData['image'] ?? null;
    $stockStatus = $productData['stock_status'] ?? 'in_stock';

    if ($name === '' || $description === '' || $category === '') {
        throw new InvalidArgumentException('Name, description, category and price are required.');
    }

    if ($price <= 0) {
        throw new InvalidArgumentException('Price must be greater than zero.');
    }

    // If image is null, don't update the image column
    if ($image !== null) {
        $stmt = db()->prepare(
            'UPDATE products SET name = :name, description = :description, price = :price, category = :category, image = :image, stock_status = :stock_status WHERE id = :id'
        );
        $params = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'image' => $image,
            'stock_status' => $stockStatus,
            'id' => $id,
        ];
    } else {
        $stmt = db()->prepare(
            'UPDATE products SET name = :name, description = :description, price = :price, category = :category, stock_status = :stock_status WHERE id = :id'
        );
        $params = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'stock_status' => $stockStatus,
            'id' => $id,
        ];
    }

    return $stmt->execute($params);
}

function isAdmin(): bool
{
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function getOrdersByUserId(int $userId): array
{
    $stmt = db()->prepare(
        'SELECT
            o.order_id,
            o.user_id,
            o.email,
            o.`date`,
            o.payment,
            oi.product_Id,
            oi.quantity,
            oi.price,
            p.name AS product_name
         FROM orders o
         LEFT JOIN order_items oi ON oi.order_id = o.order_id
         LEFT JOIN products p ON p.id = oi.product_Id
         WHERE o.user_id = :user_id
         ORDER BY o.`date` DESC, o.order_id DESC, oi.order_item_id ASC'
    );
    
    $stmt->execute(['user_id' => $userId]);

    $groupedOrders = [];

    foreach ($stmt->fetchAll() as $row) {
        $orderId = (int) $row['order_id'];

        if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = [
                'order_id' => $orderId,
                'email' => $row['email'],
                'date' => $row['date'],
                'payment' => $row['payment'],
                'items' => [],
                'total' => 0.0,
                'item_count' => 0,
            ];
        }

        if (!empty($row['product_Id'])) {
            $quantity = (int) ($row['quantity'] ?? 0);
            $price = (float) ($row['price'] ?? 0);

            $groupedOrders[$orderId]['items'][] = [
                'product_id' => (int) $row['product_Id'],
                'product_name' => $row['product_name'] ?? ('Product #' . (int) $row['product_Id']),
                'quantity' => $quantity,
                'price' => $price,
                'line_total' => $price * $quantity,
            ];
            $groupedOrders[$orderId]['total'] += $price * $quantity;
            $groupedOrders[$orderId]['item_count'] += $quantity;
        }
    }

    return array_values($groupedOrders);
}
