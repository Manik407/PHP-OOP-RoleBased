<?php

session_start();

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'php_oop');
define('DB_USER', 'root');
define('DB_PASS', '');


require_once __DIR__ . '/Database.php';


define('BASE_URL', '/'); 
spl_autoload_register(function ($class) {
    static $map = null;
    if ($map === null) {
        $map = [];
        $baseDir = __DIR__ . '/../src/';
        
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
        foreach ($rii as $file) {
            if (!$file->isFile()) continue;
            $fname = $file->getFilename();
            
            if (substr($fname, -4) !== '.php') continue;
            $basename = pathinfo($fname, PATHINFO_FILENAME); 
            $full = $file->getPathname(); 
            if (!isset($map[$basename])) $map[$basename] = $full;
        }
    }

   
    $baseName = $class;
   
    if (strpos($class, '\\') !== false) {
        $parts = explode('\\', $class);
        $baseName = end($parts);
    }

    if (isset($map[$baseName]) && file_exists($map[$baseName])) {
        require_once $map[$baseName];
        return;
    }

    
    $try = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($try)) {
        require_once $try;
        return;
    }
});




function flash_set($key, $message) {
    $_SESSION['flash'][$key] = $message;
}
function flash_get($key) {
    if (!isset($_SESSION['flash'][$key])) return null;
    $val = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $val;
}
