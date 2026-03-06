<?php
/**
 * Punto de entrada principal
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once __DIR__ . '/../config/database.php';

// Obtener la URL solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Limpiar la URI
$path = str_replace($scriptName, '', $requestUri);
$path = trim($path, '/');

// Si está vacío, usar página de inicio
if (empty($path)) {
    $path = 'personas/index';
}

// Parsear la ruta
$parts = explode('/', $path);
$controllerName = isset($parts[0]) ? ucfirst($parts[0]) : 'Persona';
$method = isset($parts[1]) ? $parts[1] : 'index';
$params = array_slice($parts, 2);

// Definir controlador por defecto
$defaultControllers = [
    'personas' => 'PersonaController',
    'persona' => 'PersonaController'
];

// Verificar si es un controlador válido
$controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . 'Controller.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controllerName . 'Controller';
    
    // Instanciar controlador
    $controller = new $controllerClass();
    
    // Verificar que el método existe
    if (method_exists($controller, $method)) {
        // Llamar al método con parámetros
        call_user_func_array([$controller, $method], $params);
    } else {
        // Método no encontrado
        echo "<h1>404 - Método no encontrado</h1>";
        echo "<p>El método '{$method}' no existe en el controlador '{$controllerClass}'</p>";
    }
} else {
    // Controlador no encontrado
    echo "<h1>404 - Controlador no encontrado</h1>";
    echo "<p>El controlador '{$controllerName}Controller' no existe</p>";
}
