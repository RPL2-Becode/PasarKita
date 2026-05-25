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

    // Advanced search with filters
    public function advancedSearch($filters = []) {
        $sql = 'SELECT p.*, c.name as category_name, u.username as seller_name,
                COALESCE((SELECT AVG(r.rating) FROM reviews r WHERE r.product_id = p.id), 0) as avg_rating,
                COALESCE((SELECT COUNT(r.id) FROM reviews r WHERE r.product_id = p.id), 0) as review_count
                FROM ' . $this->table . ' p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.seller_id = u.id 
                WHERE 1=1';

        // Keyword filter
        if (!empty($filters['keyword'])) {
            $sql .= ' AND (p.name LIKE :keyword OR p.description LIKE :keyword)';
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $sql .= ' AND p.category_id = :category_id';
        }

        // Min price filter
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $sql .= ' AND p.price >= :min_price';
        }

        // Max price filter
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $sql .= ' AND p.price <= :max_price';
        }

        // Min rating filter (using HAVING would be simpler but WHERE with subquery is safer here)
        if (!empty($filters['min_rating'])) {
            $sql .= ' AND (SELECT AVG(r2.rating) FROM reviews r2 WHERE r2.product_id = p.id) >= :min_rating';
        }

        // Sorting
        $sort = $filters['sort'] ?? 'terbaru';
        switch ($sort) {
            case 'termurah':
                $sql .= ' ORDER BY p.price ASC';
                break;
            case 'termahal':
                $sql .= ' ORDER BY p.price DESC';
                break;
            case 'rating':
                $sql .= ' ORDER BY avg_rating DESC';
                break;
            case 'terbaru':
            default:
                $sql .= ' ORDER BY p.created_at DESC';
                break;
        }

        $this->db->query($sql);

        // Bind parameters
        if (!empty($filters['keyword'])) {
            $this->db->bind(':keyword', '%' . $filters['keyword'] . '%');
        }
        if (!empty($filters['category_id'])) {
            $this->db->bind(':category_id', $filters['category_id']);
        }
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $this->db->bind(':min_price', $filters['min_price']);
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $this->db->bind(':max_price', $filters['max_price']);
        }
        if (!empty($filters['min_rating'])) {
            $this->db->bind(':min_rating', $filters['min_rating']);
        }

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
    // Reduce Product Stock
    public function reduceStock($id, $quantity) {
        $this->db->query('UPDATE ' . $this->table . ' SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity');
        $this->db->bind(':id', $id);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    // Increase Product Stock (e.g. for cancelled orders)
    public function increaseStock($id, $quantity) {
        $this->db->query('UPDATE ' . $this->table . ' SET stock = stock + :quantity WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }
}
?>
