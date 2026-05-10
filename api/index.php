<?php
/**
 * REST API Entry Point for PasarKita
 * 
 * All API requests should be routed through this file.
 * Format: /api/index.php?endpoint=<resource>&action=<action>
 * 
 * Responses are returned in JSON format.
 */

// Load Configuration
require_once '../../config/db.php';

// Load Helpers
require_once '../../app/helpers/session_helper.php';

// Set JSON response headers
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get endpoint and action from URL
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
$action   = isset($_GET['action'])   ? $_GET['action']   : '';

// Helper: Send JSON response
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([
        'status'  => $statusCode < 400 ? 'success' : 'error',
        'data'    => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit();
}

// Helper: Get JSON body from POST/PUT
function getRequestBody() {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}

// ---- Route API Endpoints ----
switch ($endpoint) {

    // ---- Products API ----
    case 'products':
        $db = new Database();
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC');
            $products = $db->resultSet();
            jsonResponse($products);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = getRequestBody();
            $db->query('INSERT INTO products (seller_id, name, description, price, stock, image_url, category_id) VALUES(:seller_id, :name, :desc, :price, :stock, :img, :cat_id)');
            $db->bind(':seller_id', $body['seller_id'] ?? null);
            $db->bind(':name', $body['name'] ?? '');
            $db->bind(':desc', $body['description'] ?? '');
            $db->bind(':price', $body['price'] ?? 0);
            $db->bind(':stock', $body['stock'] ?? 0);
            $db->bind(':img', $body['image_url'] ?? '');
            $db->bind(':cat_id', $body['category_id'] ?? null);
            
            if ($db->execute()) {
                jsonResponse(['message' => 'Product created'], 201);
            } else {
                jsonResponse(['message' => 'Failed to create product'], 500);
            }
        }
        break;

    // ---- Categories API ----
    case 'categories':
        $db = new Database();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db->query('SELECT * FROM categories ORDER BY name ASC');
            $categories = $db->resultSet();
            jsonResponse($categories);
        }
        break;

    // ---- Orders API ----
    case 'orders':
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db->query('SELECT o.*, u.username as buyer_name FROM orders o LEFT JOIN users u ON o.buyer_id = u.id ORDER BY o.created_at DESC');
            $orders = $db->resultSet();
            jsonResponse($orders);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action === 'status') {
            $body = getRequestBody();
            $db->query('UPDATE orders SET status = :status WHERE id = :id');
            $db->bind(':status', $body['status'] ?? '');
            $db->bind(':id', $body['order_id'] ?? '');
            if ($db->execute()) {
                jsonResponse(['message' => 'Order status updated']);
            } else {
                jsonResponse(['message' => 'Failed to update status'], 500);
            }
        }
        break;

    // ---- Users API ----
    case 'users':
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db->query('SELECT id, username, role, balance, created_at FROM users ORDER BY created_at DESC');
            $users = $db->resultSet();
            jsonResponse($users);
        }
        break;

    // ---- Default ----
    default:
        jsonResponse([
            'message'   => 'PasarKita REST API',
            'version'   => '1.0',
            'endpoints' => [
                'GET /api/?endpoint=products'                 => 'List all products',
                'POST /api/?endpoint=products'                => 'Create a product',
                'GET /api/?endpoint=categories'               => 'List categories',
                'GET /api/?endpoint=orders'                   => 'List all orders',
                'PUT /api/?endpoint=orders&action=status'     => 'Update order status',
                'GET /api/?endpoint=users'                    => 'List all users',
            ]
        ]);
        break;
}
?>
