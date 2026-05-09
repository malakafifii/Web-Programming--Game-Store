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

function getProductBySlug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT * FROM products WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $product = $stmt->fetch();
    return $product !== false ? $product : null;
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
    $stmt = db()->prepare('SELECT * FROM products WHERE name LIKE :query OR short_description LIKE :query OR description LIKE :query ORDER BY id ASC');
    $stmt->execute(['query' => '%' . $query . '%']);
    return $stmt->fetchAll();
}

function getFeaturedProducts(int $limit = 4): array
{
    return getProducts($limit);
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

    $adminUsername = 'amin';
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

function createBuyerAccount(string $username, string $password): int
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

function normalizeSlug(string $value): string
{
    $slug = strtolower(trim($value));
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    $slug = trim($slug, '-');

    return $slug !== '' ? $slug : 'product';
}

function slugExists(string $slug): bool
{
    $stmt = db()->prepare('SELECT 1 FROM products WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetchColumn() !== false;
}

function makeUniqueSlug(string $value): string
{
    $baseSlug = normalizeSlug($value);
    $slug = $baseSlug;
    $suffix = 2;

    while (slugExists($slug)) {
        $slug = $baseSlug . '-' . $suffix;
        $suffix++;
    }

    return $slug;
}

function createProduct(array $productData): int
{
    $name = trim($productData['name'] ?? '');
    $shortDescription = trim($productData['short_description'] ?? '');
    $description = trim($productData['description'] ?? '');
    $price = (float) ($productData['price'] ?? 0);
    $category = trim($productData['category'] ?? '');
    $sku = trim($productData['sku'] ?? '');
    $slug = trim($productData['slug'] ?? '');

    if ($name === '' || $shortDescription === '' || $description === '' || $category === '') {
        throw new InvalidArgumentException('All product fields except SKU are required.');
    }

    if ($price <= 0) {
        throw new InvalidArgumentException('Price must be greater than zero.');
    }

    $slug = $slug !== '' ? normalizeSlug($slug) : makeUniqueSlug($name);
    if ($slug === '') {
        $slug = makeUniqueSlug($name);
    }

    if (slugExists($slug)) {
        $slug = makeUniqueSlug($slug);
    }

    $stmt = db()->prepare(
        'INSERT INTO products (slug, name, short_description, description, price, category, sku)
         VALUES (:slug, :name, :short_description, :description, :price, :category, :sku)'
    );
    $stmt->execute([
        'slug' => $slug,
        'name' => $name,
        'short_description' => $shortDescription,
        'description' => $description,
        'price' => $price,
        'category' => $category,
        'sku' => $sku !== '' ? $sku : null,
    ]);

    return (int) db()->lastInsertId();
}

function isAdmin(): bool
{
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
