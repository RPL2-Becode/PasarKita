<?php
class Profile extends Controller {
    protected $userModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit;
        }
        $this->userModel = $this->model('User_model');
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        $data = [
            'title' => 'Profil Saya - PasarKita',
            'user' => $user
        ];
        
        $this->view('templates/header', $data);
        $this->view('profile/index', $data);
        $this->view('templates/footer');
    }
    
    public function edit() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'id' => $_SESSION['user_id'],
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'store_name' => trim($_POST['store_name'] ?? ''),
                'store_description' => trim($_POST['store_description'] ?? ''),
                'user' => $user, 
                'title' => 'Edit Profil - PasarKita'
            ];

            if ($this->userModel->updateProfile($data)) {
                // Base path for uploads (relative to public/)
                $base_upload = dirname(__DIR__, 2) . '/public/uploads';

                // Handle Profile Picture
                if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] == 0) {
                    $target_dir = $base_upload . '/profile/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    
                    $file_name = time() . '_p_' . basename($_FILES["profile_picture"]["name"]);
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                        $this->userModel->updateProfilePicture($_SESSION['user_id'], $file_name);
                        $_SESSION['user_profile_picture'] = $file_name;
                    }
                }
                
                // Handle Store Banner (only if user is pelapak)
                if ($user->role == 'pelapak' && !empty($_FILES['store_banner']['name']) && $_FILES['store_banner']['error'] == 0) {
                    $target_dir = $base_upload . '/banner/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    
                    $file_name = time() . '_b_' . basename($_FILES["store_banner"]["name"]);
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES["store_banner"]["tmp_name"], $target_file)) {
                        $this->userModel->updateStoreBanner($_SESSION['user_id'], $file_name);
                    }
                }

                flash('profile_message', 'Profil berhasil diperbarui.', 'bg-green-100 text-green-700 border border-green-400');
                header('location: /profile');
            } else {
                flash('profile_message', 'Terjadi kesalahan saat memperbarui profil.', 'bg-red-100 text-red-700 border border-red-400');
                header('location: /profile/edit');
            }
        } else {
            $data = [
                'title' => 'Edit Profil - PasarKita',
                'user' => $user
            ];
            
            $this->view('templates/header', $data);
            $this->view('profile/edit', $data);
            $this->view('templates/footer');
        }
    }
}
?>
