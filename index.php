<?php

//? session start
session_start();


//config
define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomain() . '/runing/project');
define('DISPLAY_ERROR', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'project');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

// require_once('database/DataBase.php');//? for connection to server 
// $db = new database\Database(); //? create new class for connection 
// require_once 'database/DataBase.php';//? for create table in database
// require_once ('database/CreateDB.php');//? for create table in database
// $db = new database\CreateDB();//? for create table in database
// $db ->run();//? for create table in database
//? helpers
// uri('admin/category/{id}','Category','index','GET');
// uri('admin/login','login','index','post');
function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
    //? current url array
    $currentUrl = explode('?', currentUrl())[0]; // convert to array 
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);  // delete CURRENT_DOMAIN in url
    $currentUrl =  trim($currentUrl, '/'); //delete / first  and last 
    $currentUrlArray = explode('/', $currentUrl); //  convert to array
    $currentUrlArray = array_filter($currentUrlArray); // delete empty house in array 
    //? reserved Url array
    $reservedUrl = trim($reservedUrl, '/'); //delete / first  and last 
    $reservedUrlArray = explode('/', $reservedUrl); //  convert to array 
    $reservedUrlArray = array_filter($reservedUrlArray); // delete empty house in array 
    //start if users url = admin url 
    if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
        return false;
    }
    $parameters = [];//my parameters for sent batwing pages 
    for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
        if ($reservedUrlArray[$key][0] == "{" && $currentUrlArray[$key][strlen($reservedUrlArray[$key] - 1 == "")]) {
            array_push($parameters, $currentUrlArray[$key]);//push parameters to target page 
        } elseif ($currentUrlArray[$key] != $reservedUrlArray[$key]) {
            return false;
        }
    }
    if (methodField()== 'POST') {
       $request = isset($_FILES) ? array_merge($_FILES , $_POST) : $_POST;
       $parameters = array_merge([$request], $parameters);
    }
    $object = new $class ;//create a new object of class
    call_user_func_array(array($object , $method), $parameters);//push parameters to class 
    exit ;// finis
}
uri('admin/index', 'Category', 'index');
//! HTTP OR HTTPS
function protocol()
{
    return  stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}

//! htttps or http + domian
function currentDomain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}
// echo currentDomain();
//! for css & js & img

function asset($src)
{

    $domain = trim(CURRENT_DOMAIN, ' /');
    $src = $domain . '/' . trim($src, '/');
    return $src;
}
//! for <a>
function url($url)
{

    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim($url, '/');
    return $url;
}
//! url user
function currentUrl()
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}

//! get or post
function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}
//! shoe errors
function disolayError($disolayError)
{
    if ($disolayError) {

        ini_set('display_errors', 1); //open php.ini and set 
        ini_set('display_errors', 1); //open php.ini and set 
        error_reporting(E_ALL); // all errors
    } else {
        ini_set('display_errors', 0); //open php.ini and set 
        ini_set('display_errors', 0); //open php.ini and set 
        error_reporting(0); // all errors
    }
}
//! for create error 
disolayError(DISPLAY_ERROR);
global $flashMessage;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

//! for print error set and get first time for set and scend time for get
function flash($name, $value = null)
{
    if ($value === null) {
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    } else {
        $_SESSION['flash_message'][$name] = $value;
    }
}

//?flash('login_error') seter // create error 
//?flash('login_error', 'email is not true') geter // print error of ths name 
function dd($var) 
{
    echo "<pre>";
    var_dump($var);
}
