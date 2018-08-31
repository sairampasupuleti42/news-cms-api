<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type,NG-AUTH');
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
        $this->load->model('Language_model', 'language', TRUE);
    }

    function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = file_get_contents("php://input");
            if (!empty($post)) {
                $raw = (array)json_decode($post);
                $category_en = $raw['name'];
                if (!$this->category->checkCategoryInDB($category_en)) {
                    $pdata['name'] = $category_en;
                    $lang_id = $raw['language_id'];
                    $language = $this->language->getLanguageById($lang_id);
                    $permalink = !empty($raw['permalink']) ? $raw['permalink'] : $category_en;
                    $pdata['permalink'] = $language['permalink'] . '/' . slugify($permalink);
                    $pdata['status'] = $raw['status'];
                    $pdata['parent_id'] = !empty($raw['parent_id']) ? $raw['parent_id'] : '0';
                    $pdata['language_id'] = $lang_id;
                    $pdata['created_on'] = date('Y-m-d H:i:s A');
                    $pdata['created_by'] = $raw['created_by'];
                    $this->category->addCategory($pdata);
                    echo _success('Successfully added !', 'category', $pdata, 201);
                } else {
                    echo _error("Category already exists !", 409);
                }
            } else {
                echo _error("Invalid parameters", 404);
            }
        } else {
            $categories = $this->category->searchCategories();
            foreach ($categories as $category) {

                $row['id'] = $category['id'];
                $row['parent'] =  $this->category->getCategoryById($category['parent_id'])['name'];
                $row['name'] = $category['name'];
                $row['language'] = $this->language->getLanguageById($category['language_id'])['name'];
                $row['permalink'] = $category['permalink'];
                $row['status'] = $category['status'];
                $row['created_on'] = $category['created_on'];

                $rows[] = $row;
            }
            echo _success('Success', 'categories', $rows, 200);
        }

    }

    function get($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $post = file_get_contents("php://input");
            $raw = (array)json_decode($post);
            if (!empty($post)) {
                $category_en = $raw['name'];
                $pdata['name'] = $category_en;
                $pdata['permalink'] = slugify($category_en);
                $pdata['status'] = $raw['status'];
                $lang_id = $raw['language_id'];
                $language = $this->language->getLanguageById($lang_id);
                $permalink = !empty($raw['permalink']) ? $raw['permalink'] : $category_en;
                $pdata['permalink'] = $language['permalink'] . '/' . slugify($permalink);
                $pdata['language_id'] = $lang_id;
                $pdata['parent_id'] = !empty($raw['parent_id']) ? $raw['parent_id'] : '0';
                $this->category->updateCategory($pdata, $id);
                echo _success('Successfully Updated !', 'category', $pdata, 200);
            } else {
                echo _error("Invalid parameters", 404);
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->category->delCategory($id);
            echo _success('Successfully Removed !', 'result', [], 200);
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $category = $this->category->getCategoryById($id);
            echo _success('Success', 'category', $category, 200);
        } else {
            echo "Invalid";
        }
    }
    function parent_categories($language_id){
        $categories = $this->category->searchCategories(array('parent'=>'true','language_id'=>$language_id));
        echo _success('Success', 'categories', $categories, 200);
    }
}