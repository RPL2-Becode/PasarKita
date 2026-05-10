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
}
?>
