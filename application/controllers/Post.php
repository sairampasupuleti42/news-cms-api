<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers:Content-Type,NG-AUTH');
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
        $this->load->model('Language_model', 'language', TRUE);
        $this->load->model('Category_model', 'category', TRUE);
    }

    function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = file_get_contents("php://input");
            if (!empty($post)) {
                $raw = (array)json_decode($post);
                $title = $raw['title'];
                $permalink = !empty($raw['permalink']) ? $raw['permalink'] : $title;
                $pdata['is_highlight'] = (!empty($raw['is_highlight']) && $raw['is_highlight']=='1') ? 'Yes' : 'No';
                $l_id = $raw['language_id'];
                $c_id = $raw['category_id'];

                $pdata['title'] = $title;
                $pdata['permalink'] = slugify($permalink);
                $complete_url = slugify($permalink);
                $pdata['permalink_url'] = $complete_url;
                $target = "./uploads/posts/";
                $image = $raw['image'];
                $exploded_img = explode(',', $image);

                $image_name = $complete_url . time();
                $image_path=base64ToImg($exploded_img[1], $target, $image_name, 'jpg');
                $pdata['image'] = $image_path;
                $pdata['language_id'] = $l_id;
                $pdata['description'] = !empty($raw['description']) ? $raw['description'] : '';
                $tags = !empty($raw['tags']) ? $raw['tags'] : '';
                $tss=array();
                foreach ($tags as $tag){
                    $ts=$tag->value;
                    array_push($tss,$ts);
                }
                $pdata['tags']=implode(',',$tss);
                $pdata['video'] = !empty($raw['video']) ? $raw['video'] : '';
                if (!$this->post->checkPostInDB($complete_url)) {
                    $last_id=$this->post->addPost($pdata);
                    if(!empty($last_id)){
                        if(!empty($c_id)) {
                            foreach ($c_id as $cat_id) {
                                $this->post->addCategoryPost(array('category_id' => $cat_id, 'post_id' => $last_id));
                            }
                        }
                    }
                    echo _success('Successfully added !', 'post', $pdata, 201);
                } else {
                    echo _error("This post already exists in DB. If Content is different then change permalink text !", 409);
                }
            } else {
                echo _error("Invalid parameters", 404);
            }
        } else {
            $posts = $this->post->searchPosts(array('limit' => '20', 'offset' => '0'));
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $row = array();
                    $row['id'] = $post['language_id'];
                    $row['title'] = $post['title'];
                    $row['permalink'] = $post['permalink'];
                    $row['permalink_url'] = $post['permalink_url'];
                    $row['description'] = $post['description'];
                    $row['video'] = $post['video'];
                    $row['image'] = $post['image'];
                    $row['tags'] = $post['tags'];
                    $row['status'] = $post['status'];
                    $row['created_by'] = $post['created_by'];
                    $row['created_on'] = strtotime($post['created_on']);
                    $rows[] = $row;
                }
            }
            echo _success('Success', 'posts', $rows, 200);
        }
    }

    function get($id_or_permalink)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $post = file_get_contents("php://input");
            if (!empty($post)) {
                $raw = (array)json_decode($post);
                $title = $raw['title'];
                $permalink = !empty($raw['permalink']) ? $raw['permalink'] : $title;
                $l_id = $raw['language_id'];
                $c_id = $raw['category_id'];
                $c = $this->category->getCategoryById($c_id);
                $pdata['title'] = $title;
                $pdata['permalink'] = slugify($permalink);
                $complete_url = $c['permalink'] . '/' . slugify($permalink);
                $pdata['permalink_url'] = $complete_url;
                $target = "./uploads/posts/";
                $image = $raw['image'];
                $exploded_img = explode(',', $image);
                $image_name = $complete_url . time();
                $image_path=base64ToImg($exploded_img[1], $target, $image_name, 'jpg');
                $pdata['image'] = $image_path;
                $pdata['language_id'] = $l_id;
                $pdata['category_id'] = $c_id;
                $pdata['description'] = !empty($raw['description']) ? $raw['description'] : '';
                $pdata['tags'] = !empty($raw['tags']) ? $raw['tags'] : '';
                $pdata['video'] = !empty($raw['video']) ? $raw['video'] : '';
                if (!$this->post->checkPostInDB($complete_url)) {
                    if (is_numeric($id_or_permalink)) {
                        $id = $id_or_permalink;
                        $this->post->udpatePost($pdata, $id);
                        echo _success('Successfully updated !', 'post', $pdata, 200);
                    }
                } else {
                    echo _error("This post already exists in DB. If Content is different then change permalink text !", 409);
                }
            } else {
                echo _error("Invalid parameters", 404);
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            echo "Valid_DELETE";
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (is_numeric($id_or_permalink)) {
                $id = $id_or_permalink;
                $post = $this->post->getPostById($id);
                echo _success('Success', 'post', $post, 200);
            } else {
                $permalink = $id_or_permalink;
                $post = $this->post->getPostBySlug($permalink);
                echo _success('Success', 'post', $post, 200);
            }
        } else {
            echo "Invalid";
        }
    }

    function custom($language_id = '', $category_id = '')
    {
        $posts = $this->post->searchPosts(array('category_id' => $category_id, 'language_id' => $language_id));
        echo _success('Success', 'posts', $posts, 200);
    }

    function custom_category($category_id)
    {
        $posts = $this->post->searchPosts(array('category_id' => $category_id));
        echo _success('Success', 'posts', $posts, 200);
    }

    function custom_language($language_id)
    {
        $posts = $this->post->searchPosts(array('language_id' => $language_id));
        echo _success('Success', 'posts', $posts, 200);
    }
}