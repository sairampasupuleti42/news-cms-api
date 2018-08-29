<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type');
header("Access-Control-Allow-Methods: GET, POST,PUT,DELETE");
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');
//header("X-XSS-Protection: 1; mode=block");
class Post extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Posts_model', 'post', TRUE);
    }

    function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "Valid_POST";
        } else {
            echo "Valid_LIST_GET";
        }
    }

    function get($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            echo "Valid_PUT";
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            echo "Valid_DELETE";
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            echo "Valid_GET";
        } else {
            echo "Invalid";
        }
    }
}