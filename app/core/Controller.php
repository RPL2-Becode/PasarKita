<?php
/**
 * Base Controller Class
 * Loads the models and views
 */
class Controller {
    // Load model
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View does not exist');
        }
    }

    // Load service
    public function service($service) {
        if (file_exists('../app/services/' . $service . '.php')) {
            require_once '../app/services/' . $service . '.php';
            return new $service();
        } else {
            die('Service does not exist: ' . $service);
        }
    }
}
?>
