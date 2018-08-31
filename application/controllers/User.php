<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type');
header("Access-Control-Allow-Methods: GET, POST,PUT,DELETE");
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');

//header("X-XSS-Protection: 1; mode=block");
class User extends MY_Controller
{
    function __construct()
    {

    }
}