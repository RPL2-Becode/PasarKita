<?php
/**
 * Product Model
 */
class Product_model {
    private $table = 'products';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Search products
    public function searchProducts($keyword) {
        $this->db->query('SELECT p.*, c.name as category_name FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id WHERE p.name LIKE :keyword OR p.description LIKE :keyword ORDER BY p.created_at DESC');
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->resultSet();
    }

    // Get all products
    public function getProducts() {
        $this->db->query('SELECT p.*, c.name as category_name FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC');
        return $this->db->resultSet();
    }

    // Get products by category
    public function getProductsByCategory($category_id) {
        $this->db->query('SELECT p.*, c.name as category_name FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = :cat_id ORDER BY p.created_at DESC');
        $this->db->bind(':cat_id', $category_id);
        return $this->db->resultSet();
    }

    // Get products by seller ID
    public function getProductsBySeller($seller_id) {
        $this->db->query('SELECT p.*, c.name as category_name FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id WHERE p.seller_id = :seller_id ORDER BY p.created_at DESC');
        $this->db->bind(':seller_id', $seller_id);
        return $this->db->resultSet();
    }

    // Get product by ID
    public function getProductById($id) {
        $this->db->query('SELECT p.*, c.name as category_name, u.username as seller_name FROM ' . $this->table . ' p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN users u ON p.seller_id = u.id WHERE p.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get all categories
    public function getCategories() {
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Add Product
    public function addProduct($data) {
        $this->db->query('INSERT INTO ' . $this->table . ' (seller_id, name, description, price, stock, image_url, category_id) VALUES(:seller_id, :name, :description, :price, :stock, :image_url, :category_id)');
        // Bind values
        $this->db->bind(':seller_id', $data['seller_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image_url', $data['image_url']);
        $this->db->bind(':category_id', !empty($data['category_id']) ? $data['category_id'] : null);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update Product
    public function updateProduct($data) {
        $this->db->query('UPDATE ' . $this->table . ' SET name = :name, description = :description, price = :price, stock = :stock, image_url = :image_url, category_id = :category_id WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image_url', $data['image_url']);
        $this->db->bind(':category_id', !empty($data['category_id']) ? $data['category_id'] : null);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get product count
    public function getProductCount() {
        $this->db->query('SELECT COUNT(*) as total FROM ' . $this->table);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Delete Product
    public function deleteProduct($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
