<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type');
header("Access-Control-Allow-Methods: GET, POST,PUT,DELETE");
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');
//header("X-XSS-Protection: 1; mode=block");
class Api extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model', 'api', TRUE);
        $this->load->model('User_model', 'uapi', TRUE);

    }
    public function index()
    {
        $data = array();
        echo _error("Bad Request !", 204);
    }





}
