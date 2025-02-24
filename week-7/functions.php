<?php

use Core\Session;
use Core\Response;

function dd($val)
{
    echo '<pre>';
    var_dump($val);
    echo '</pre>';
    die();
}
function urlIs($val)
{
    return $_SERVER['REQUEST_URI'] === $val;
}
function abort($code = 404)
{
    http_response_code($code);
    require base_path("views/{$code}.php");
    die();
}
function authorize($condition, $status = Response::FORBIDDEN)
{
    if (!$condition) {
        abort($status);
    }
}
function base_path($path)
{
    return BASE_PATH . $path;
}
function view($path, $attributes = [])
{
    extract($attributes);
    require base_path('views/' . $path);
}
// function redirect($path){
//     header("Location: {$path}");
//     exit();
// }
// function old($key, $default = '')
// {
//     return Core\Session::get('old')[$key] ?? $default;
// }
