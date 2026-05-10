<?php
/**
 * Users Controller
 * Handles Login, Register, Logout, and Role-based routing
 */
class Users extends Controller {
    protected $userModel;

    public function __construct() {
        $this->userModel = $this->model('User_model');
    }

    /**
     * Login
     */
    public function login() {
        // Redirect if already logged in
        if (isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/username
            if ($this->userModel->findUserByUsername($data['username'])) {
                // User found
            } else {
                $data['username_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }

        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    /**
     * Register new user
     */
    public function register() {
        // Redirect if already logged in
        if (isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),
                'username_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Username tidak boleh kosong';
            } elseif (strlen($data['username']) < 3) {
                $data['username_err'] = 'Username minimal 3 karakter';
            } elseif ($this->userModel->findUserByUsername($data['username'])) {
                $data['username_err'] = 'Username sudah digunakan';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Password tidak boleh kosong';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password minimal 6 karakter';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Konfirmasi password tidak boleh kosong';
            } elseif ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Password tidak cocok';
            }

            // Validate Role
            if (!in_array($data['role'], ['consumen', 'pelapak'])) {
                $data['role'] = 'consumen';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $data['balance'] = ($data['role'] == 'consumen') ? 1000000 : 0;

                // Register User
                if ($this->userModel->register($data)) {
                    flash('login_errors', 'Registrasi berhasil! Silakan login.', 'bg-green-100 text-green-700');
                    header('location: /users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }

        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'confirm_password' => '',
                'role' => 'consumen',
                'username_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $this->view('users/register', $data);
        }
    }

    /**
     * Forgot Password
     */
    public function forgotpassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'username' => trim($_POST['username']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'username_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Masukkan username Anda';
            } else {
                if (!$this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username tidak ditemukan';
                }
            }

            // Validate New Password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Masukkan password baru';
            } elseif (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Password minimal 6 karakter';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Konfirmasi password baru';
            } else {
                if ($data['new_password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Password tidak sama';
                }
            }

            // Validated
            if (empty($data['username_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                $new_password_hash = password_hash($data['new_password'], PASSWORD_DEFAULT);
                
                if ($this->userModel->updatePassword($data['username'], $new_password_hash)) {
                    flash('user_message', 'Password berhasil di-reset. Silakan login.', 'bg-green-100 text-green-700');
                    header('location: /users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/forgotpassword', $data);
            }
        } else {
            $data = [
                'username' => '',
                'new_password' => '',
                'confirm_password' => '',
                'username_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('users/forgotpassword', $data);
        }
    }

    /**
     * Create user session after login
     */
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_role'] = $user->role;
        
        $this->redirectByRole();
    }

    /**
     * Redirect user based on their role
     */
    private function redirectByRole() {
        $role = $_SESSION['user_role'] ?? 'consumen';

        switch ($role) {
            case 'admin':
                header('location: /admin/dashboard');
                break;
            case 'operator':
                header('location: /admin/orders');
                break;
            case 'pelapak':
                header('location: /products');
                break;
            case 'consumen':
            default:
                header('location: /home');
                break;
        }
    }

    /**
     * Logout / Destroy session
     */
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_role']);
        session_destroy();
        header('location: /users/login');
    }
}
?>
