<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

try {
    // SQL query with JOIN
    $query = "SELECT 
                p.product_id,
                p.product_title AS name,
                p.product_desc AS description,
                p.product_price AS price,
                p.product_img1 AS image,
                c.cat_title AS category
              FROM products p
              JOIN categories c ON p.cat_id = c.cat_id
              WHERE p.status = 'product'";
    
    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch results
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Add additional formatting if needed
    foreach ($products as &$product) {
        $product['price'] = number_format($product['price'], 2);
        $product['image'] = $product['image'] ?: 'default-product.jpg';
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
    
} catch(PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch(Exception $e) {
    // Handle other errors
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>