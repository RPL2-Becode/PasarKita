<?php
/**
 * Chat Controller
 * Handles chat functionality between users
 */
class Chat extends Controller {
    protected $chatModel;
    protected $userModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location: /users/login');
            exit();
        }
        $this->chatModel = $this->model('Chat_model');
        $this->userModel = $this->model('User_model');
    }

    public function index($contact_id = null) {
        $user_id = $_SESSION['user_id'];
        $conversations = $this->chatModel->getConversations($user_id);
        
        $messages = [];
        $active_contact = null;
        $product_context = null;
        $order_context = null;

        if ($contact_id) {
            // Mark messages as read
            $this->chatModel->markAsRead($contact_id, $user_id);
            $messages = $this->chatModel->getMessages($user_id, $contact_id);
            $active_contact = $this->userModel->getUserById($contact_id);
            
            // Check if there is product context in GET
            if (isset($_GET['product_id'])) {
                $productModel = $this->model('Product_model');
                $product_context = $productModel->getProductById($_GET['product_id']);
            }
            
            if (isset($_GET['order_id'])) {
                $orderModel = $this->model('Order_model');
                $order_context = $orderModel->getOrderById($_GET['order_id']);
            }
        } elseif (!empty($conversations)) {
            // Default to the most recent conversation if none selected
            $contact_id = $conversations[0]->contact_id;
            header('location: /chat/index/' . $contact_id);
            exit();
        }

        $data = [
            'title' => 'Pesan - PasarKita',
            'conversations' => $conversations,
            'messages' => $messages,
            'active_contact' => $active_contact,
            'current_user_id' => $user_id,
            'product_context' => $product_context,
            'order_context' => $order_context
        ];

        $this->view('chat/index', $data);
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => filter_input(INPUT_POST, 'receiver_id', FILTER_SANITIZE_NUMBER_INT),
                'message' => trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS)),
                'product_id' => filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT),
                'order_id' => trim(filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            ];

            if (!empty($data['message']) && !empty($data['receiver_id'])) {
                $this->chatModel->sendMessage($data);
            }

            // Redirect back to the chat with this contact
            header('location: /chat/index/' . $data['receiver_id']);
        } else {
            header('location: /chat');
        }
    }
}
?>
