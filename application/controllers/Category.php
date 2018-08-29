<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type');
header("Access-Control-Allow-Methods: GET, POST,PUT,DELETE");
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');

//header("X-XSS-Protection: 1; mode=block");
class Category extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model', 'category', TRUE);
    }

    function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $raw = array();
            $category_en = $raw['category_name'];
            $category_te = $raw['category_te_name'];
            $category_hi = $raw['category_hi_name'];
            if (!$this->category->checkCategoryInDB($category_en)) {
                $pdata['category_name'] = $category_en;
                $pdata['category_permalink'] = slugify($category_en);
                $pdata['category_status'] = '1';
                $pdata['category_te_name'] = $category_te;
                $pdata['category_hi_name'] = $category_hi;
                $pdata['category_created_on'] = $raw['category_created_on'];
                $pdata['category_created_by'] = $raw['category_created_by'];
                $this->category->addCategory($pdata);
            }
        } else {
            $categories = $this->category->searchCategories();
            echo _success('Success', 'categories', $categories, 200);


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