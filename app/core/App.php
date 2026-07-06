<?php
/**
 * Main App Class
 * Routing logic: URL format: /controller/method/params
 */
class App {
    protected $currentController = 'Home';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        // Trigger background processes on every request for the demo
        if (file_exists('../app/models/Order_model.php')) {
            require_once '../app/models/Order_model.php';
            if (class_exists('Order_model')) {
                $orderModel = new Order_model();
                if (method_exists($orderModel, 'processAutoUpdates')) {
                    $orderModel->processAutoUpdates();
                }
            }
        }

        $url = $this->getUrl();

        // Check if controller exists
        if (isset($url[0])) {
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            }
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller
        $this->currentController = new $this->currentController;

        // Check for method in URL
        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call method with params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
?>
