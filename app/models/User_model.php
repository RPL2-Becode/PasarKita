<?php
/**
 * User Model
 */
class User_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Login User
    public function login($username, $password) {
        $row = $this->findUserByUsername($username);

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Register User
    public function register($data) {
        $this->db->query('INSERT INTO ' . $this->table . ' (username, password, role, balance) VALUES(:username, :password, :role, :balance)');
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':balance', $data['balance']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get all users
    public function getAllUsers() {
        $this->db->query('SELECT id, username, role, balance, created_at FROM ' . $this->table . ' ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Get user count
    public function getUserCount() {
        $this->db->query('SELECT COUNT(*) as total FROM ' . $this->table);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Delete user
    public function deleteUser($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Update user role
    public function updateRole($id, $role) {
        $this->db->query('UPDATE ' . $this->table . ' SET role = :role WHERE id = :id');
        $this->db->bind(':role', $role);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Update password
    public function updatePassword($username, $new_password_hash) {
        $this->db->query('UPDATE ' . $this->table . ' SET password = :password WHERE username = :username');
        $this->db->bind(':password', $new_password_hash);
        $this->db->bind(':username', $username);
        return $this->db->execute();
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update Profile
    public function updateProfile($data) {
        $this->db->query('UPDATE ' . $this->table . ' SET full_name = :full_name, email = :email, phone = :phone, address = :address, store_name = :store_name, store_description = :store_description WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':store_name', isset($data['store_name']) ? $data['store_name'] : null);
        $this->db->bind(':store_description', isset($data['store_description']) ? $data['store_description'] : null);
        
        return $this->db->execute();
    }

    // Update Profile Picture
    public function updateProfilePicture($id, $file_name) {
        $this->db->query('UPDATE ' . $this->table . ' SET profile_picture = :profile_picture WHERE id = :id');
        $this->db->bind(':profile_picture', $file_name);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Update Store Banner
    public function updateStoreBanner($id, $file_name) {
        $this->db->query('UPDATE ' . $this->table . ' SET store_banner = :store_banner WHERE id = :id');
        $this->db->bind(':store_banner', $file_name);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Deduct user balance for SmartBank
    public function deductBalance($id, $amount) {
        $this->db->query('SELECT balance FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        $user = $this->db->single();
        
        if ($user && $user->balance >= $amount) {
            $new_balance = $user->balance - $amount;
            $this->db->query('UPDATE ' . $this->table . ' SET balance = :balance WHERE id = :id');
            $this->db->bind(':balance', $new_balance);
            $this->db->bind(':id', $id);
            if ($this->db->execute()) {
                return $new_balance;
            }
        }
        return false;
    }

    // Add user balance
    public function addBalance($id, $amount) {
        $this->db->query('UPDATE ' . $this->table . ' SET balance = balance + :amount WHERE id = :id');
        $this->db->bind(':amount', $amount);
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            $this->db->query('SELECT balance FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind(':id', $id);
            $user = $this->db->single();
            return $user->balance;
        }
        return false;
    }
}
?>
