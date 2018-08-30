<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type');
header("Access-Control-Allow-Methods: GET, POST,PUT,DELETE");
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');

//header("X-XSS-Protection: 1; mode=block");
class Language extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Language_model', 'language', TRUE);
    }

    function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = file_get_contents("php://input");
            if (!empty($post)) {
                $raw = (array)json_decode($post);
                $name = $raw['name'];
                if (!$this->language->checkLanguageInDB($name)) {
                    $pdata['name'] = $name;
                    $pdata['permalink'] = !empty($raw['permalink']) ? $raw['permalink']:slugify($name);
                    $pdata['status'] = $raw['status'];
                    $pdata['created_on'] = date('Y-m-d H:i:s');
                    $pdata['created_by'] = $raw['created_by'];
                    $this->language->addLanguage($pdata);
                    echo _success('Successfully added !', 'language', $pdata, 200);
                }else{
                    echo _error("Language already exists !", 409);
                }
            } else {
                echo _error("Invalid parameters", 404);
            }
        } else {
            $languages = $this->language->searchLanguages();
            echo _success('Success', 'languages', $languages, 200);
        }

    }

    function get($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $post = file_get_contents("php://input");
            $raw = (array)json_decode($post);
            if (!empty($post)) {
                $name = $raw['name'];
                $pdata['name'] = $name;
                $pdata['permalink'] = !empty($raw['permalink']) ? $raw['permalink']:slugify($name);
                $pdata['status'] = $raw['status'];
                $this->language->updateLanguage($pdata, $id);
                echo _success('Successfully Updated !', 'language', $pdata, 200);
            } else {
                echo _error("Invalid parameters", 404);
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->language->delLanguage($id);
            echo _success('Successfully Removed !', 'result', [], 200);
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $language = $this->language->getLanguageById($id);
            echo _success('Success', 'language', $language, 200);
        } else {
            echo _error("Invalid request", 401);
        }
    }
}