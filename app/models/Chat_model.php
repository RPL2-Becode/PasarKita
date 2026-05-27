<?php
/**
 * Chat Model
 * Handles database operations for the chat feature
 */
class Chat_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
        
        // Self-healing: Ensure messages table exists
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sender_id INT NOT NULL,
                receiver_id INT NOT NULL,
                message TEXT,
                product_id INT DEFAULT NULL,
                order_id VARCHAR(20) DEFAULT NULL,
                is_read TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (sender_id) REFERENCES users(id),
                FOREIGN KEY (receiver_id) REFERENCES users(id),
                FOREIGN KEY (product_id) REFERENCES products(id),
                FOREIGN KEY (order_id) REFERENCES orders(id)
            )");
            $this->db->execute();
        } catch (Exception $e) { }
    }

    public function getUnreadCount($user_id) {
        $this->db->query("SELECT COUNT(*) as total FROM messages WHERE receiver_id = :user_id AND is_read = 0");
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getConversations($user_id) {
        // Get all unique users this user has chatted with
        // Get the latest message for each conversation
        $query = "
            SELECT 
                u.id as contact_id, 
                u.username, 
                u.store_name, 
                u.profile_picture,
                u.role,
                m.message as last_message, 
                m.created_at as last_message_time,
                m.is_read,
                m.sender_id,
                (SELECT COUNT(*) FROM messages WHERE sender_id = u.id AND receiver_id = :user_id AND is_read = 0) as unread_count
            FROM users u
            JOIN (
                SELECT 
                    CASE 
                        WHEN sender_id = :user_id THEN receiver_id 
                        ELSE sender_id 
                    END as contact_id,
                    MAX(id) as max_id
                FROM messages 
                WHERE sender_id = :user_id OR receiver_id = :user_id
                GROUP BY contact_id
            ) latest_msg ON u.id = latest_msg.contact_id
            JOIN messages m ON m.id = latest_msg.max_id
            ORDER BY m.created_at DESC
        ";
        
        $this->db->query($query);
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getMessages($user1_id, $user2_id) {
        $this->db->query("
            SELECT m.*, 
                   p.name as product_name, p.price as product_price, p.image_url as product_image,
                   o.total_payment as order_total, o.status as order_status
            FROM messages m
            LEFT JOIN products p ON m.product_id = p.id
            LEFT JOIN orders o ON m.order_id = o.id
            WHERE (m.sender_id = :user1 AND m.receiver_id = :user2)
               OR (m.sender_id = :user2 AND m.receiver_id = :user1)
            ORDER BY m.created_at ASC
        ");
        $this->db->bind(':user1', $user1_id);
        $this->db->bind(':user2', $user2_id);
        return $this->db->resultSet();
    }

    public function sendMessage($data) {
        $this->db->query("INSERT INTO messages (sender_id, receiver_id, message, product_id, order_id) VALUES (:sender_id, :receiver_id, :message, :product_id, :order_id)");
        
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':message', $data['message']);
        
        if (!empty($data['product_id'])) {
            $this->db->bind(':product_id', $data['product_id']);
        } else {
            $this->db->bind(':product_id', null);
        }

        if (!empty($data['order_id'])) {
            $this->db->bind(':order_id', $data['order_id']);
        } else {
            $this->db->bind(':order_id', null);
        }

        return $this->db->execute();
    }

    public function markAsRead($sender_id, $receiver_id) {
        // Marks all messages sent BY sender_id TO receiver_id as read
        $this->db->query("UPDATE messages SET is_read = 1 WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND is_read = 0");
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':receiver_id', $receiver_id);
        return $this->db->execute();
    }
}
?>
