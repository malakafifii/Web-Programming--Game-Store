<?php
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

function getProducts(int $limit = null): array
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
