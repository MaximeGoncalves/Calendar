<?php
require '../vendor/autoload.php';

function dd(...$vars){
    foreach ($vars as $var){
    var_dump($var);
    }
}

function getPDO(){
    return $pdo = new \PDO('mysql:host=localhost;dbname=Events', 'root','root', [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ]);
}

function error404(){
    require '../public/404.php';
    exit();
}

function render(string $view, array $params = []){
    extract($params);
    require "../Views/{$view}.php";
}