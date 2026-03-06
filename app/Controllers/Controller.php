<?php
/**
 * Clase base para todos los Controladores
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

abstract class Controller {
    protected $model;
    protected $viewPath;
    
    public function __construct() {
        $this->viewPath = Config::VIEW_PATH;
    }
    
    /**
     * Renderizar una vista
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = $this->viewPath . '/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            $this->error("Vista no encontrada: {$view}");
        }
    }
    
    /**
     * Redireccionar a una URL
     */
    protected function redirect($url) {
        header("Location: " . Config::APP_URL . $url);
        exit;
    }
    
    /**
     * Redireccionar con mensaje
     */
    protected function redirectWith($url, $message, $type = 'success') {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
        $this->redirect($url);
    }
    
    /**
     * Mostrar error 404
     */
    protected function error($message = 'Página no encontrada') {
        http_response_code(404);
        $this->view('error', ['message' => $message]);
        exit;
    }
    
    /**
     * Mostrar error de acceso
     */
    protected function forbidden($message = 'Acceso denegado') {
        http_response_code(403);
        $this->view('error', ['message' => $message, 'code' => 403]);
        exit;
    }
    
    /**
     * Obtener datos del request
     */
    protected function getInput() {
        return $_POST;
    }
    
    /**
     * Obtener un valor específico del request
     */
    protected function getInputValue($key, $default = null) {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    /**
     * Validar datos requeridos
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleArray = explode('|', $rule);
            
            foreach ($ruleArray as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field] = "El campo {$field} es requerido";
                } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "El campo {$field} debe ser un email válido";
                } elseif ($r === 'numeric' && !is_numeric($value)) {
                    $errors[$field] = "El campo {$field} debe ser numérico";
                } elseif (strpos($r, 'min:') === 0) {
                    $min = substr($r, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = "El campo {$field} debe tener al menos {$min} caracteres";
                    }
                } elseif (strpos($r, 'max:') === 0) {
                    $max = substr($r, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = "El campo {$field} debe tener máximo {$max} caracteres";
                    }
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Respuesta JSON
     */
    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Verificar si es método POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Verificar si es método GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * Obtener mensajes de sesión
     */
    protected function getMessage() {
        $message = $_SESSION['message'] ?? null;
        $type = $_SESSION['message_type'] ?? 'info';
        
        unset($_SESSION['message'], $_SESSION['message_type']);
        
        return ['message' => $message, 'type' => $type];
    }
}
