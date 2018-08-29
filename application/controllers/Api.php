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
        $this->load->helper('string');
    }

    function categories()
    {
        $categories = $this->api->getCategories();
        echo _success('Success', $categories, '200');
    }

    public function index()
    {
        $data = array();
        echo _error("Bad Request !", 204);
    }

    public function posts($lang = 'tel')
    {
        $lang_id = 0;
        if ($lang == 'tel') {
            $lang_id = 1;
        } else if ($lang == 'en') {
            $lang_id = 2;
        } else if ($lang == 'hi') {
            $lang_id = 3;
        }
        $posts = $this->api->searchPosts(['limit' => '20', 'language' => $lang_id, 'offset' => 0]);
        echo _success('Success', $posts, '200');
    }

    public function posts_short($lang = 'tel')
    {
        $lang_id = 0;
        if ($lang == 'tel') {
            $lang_id = 1;
        } else if ($lang == 'en') {
            $lang_id = 2;
        } else if ($lang == 'hi') {
            $lang_id = 3;
        }
        $categories = $this->api->getCategories();
        if (!empty($categories)) {
            foreach ($categories as &$category) {
                $category_posts = $this->api->searchPosts(['limit' => '4', 'language' => $lang_id, 'offset' => 0]);
                if (!empty($category_posts)) {
                    $tp = [];
                    foreach ($category_posts as $post) {
                        $tpp = [];
                        $tpp['news_title'] = $post['title'];
                        $tpp['news_category'] = $this->getCategoryNameById($post['cat_id']);
                        $tpp['news_keywords'] =  slugify($post['title_keywords']);
                        $tpp['news_description'] = $this->clean($post['description']);
                        $tpp['news_tags'] = $post['tags'];
                        $tpp['news_image'] = "http://www.pslvtv.com/admin/gallery/" . $post['image'];
                        $tpp['news_page_views'] = $post['page_views'];
                        $tpp['created_date'] = date('jS m, Y',strtotime($post['created_date']));
                        array_push($tp, $tpp);
                    }
                    $category['category_posts'] = $tp;
                }
            }
        }
        echo _success('Success', $categories, '200');
    }

    function clean($text)
    {
        return str_replace("\r\n", ' ', strip_tags($text));

    }

    public function scroll_posts($lang = 'tel')
    {
        $lang_id = 0;
        if ($lang == 'tel') {
            $lang_id = 1;
        } else if ($lang == 'en') {
            $lang_id = 2;
        } else if ($lang == 'hi') {
            $lang_id = 3;
        }
        $sposts = $this->api->searchPosts(['limit' => '10', 'language' => $lang_id, 'offset' => 0]);
        if (!empty($sposts)) {
            $posts = [];
            foreach ($sposts as $post) {
                $p = [];
                $p['title'] = $post['title'];
                $p['news_keywords'] =  slugify($post['title_keywords']);
                array_push($posts, $p);
            }
            echo _success('Success', $posts, '200');
        }
    }

    public function cposts( $cat_id = '',$lang = 'tel')
    {
        $lang_id = 0;
        if ($lang == 'tel') {
            $lang_id = 1;
        } else if ($lang == 'en') {
            $lang_id = 2;
        } else if ($lang == 'hi') {
            $lang_id = 3;
        }
        $cposts = $this->api->searchPosts(['limit' => '20', 'language' => $lang_id, 'cat_id' => $cat_id, 'offset' => 0]);
        if (!empty($cposts)) {
            $tp = [];
            foreach ($cposts as $post) {
                $tpp = [];
                $tpp['news_title'] = $post['title'];
                $tpp['news_keywords'] =  slugify($post['title_keywords']);
                $tpp['news_description'] = shortDesc($this->clean($post['description']),50);
                $tpp['news_tags'] = $post['tags'];
                $tpp['news_image'] = "http://www.pslvtv.com/admin/gallery/" . $post['image'];
                $tpp['news_page_views'] = $post['page_views'];
                $tpp['created_date'] = date('jS m, Y',strtotime($post['created_date']));
                array_push($tp, $tpp);
            }
        }
        echo _success('Success', $tp, '200');
    }

    public function post($id)
    {
       $post = $this->api->getPostById($id);

        echo _success('Success', $post, '200');
    }
    function getCategoryNameById($id){
        return $this->api->getCategoryName($id)['category_name'];
    }
    public function post_by_slug($slug)
    {
        $post = $this->api->getPostBySlug($slug);
        $tpp = [];
        if (!empty($post)) {
            $tpp['news_title'] = $post['title'];
            $tpp['news_category'] = $this->getCategoryNameById($post['cat_id']);
            $tpp['news_cat_id'] = $post['cat_id'];
            $tpp['news_keywords'] = slugify($post['title_keywords']);
            $tpp['news_description'] = $this->clean($post['description']);
            $tpp['news_tags'] = $post['tags'];
            $tpp['news_image'] = "http://www.pslvtv.com/admin/gallery/" . $post['image'];
            $tpp['news_page_views'] = $post['page_views'];
            $tpp['created_date'] = $post['created_date'];
        }

    echo _success('Success', $tpp, '200');
    }


    public function _hash($password)
    {
        return hash('sha256', $password);
    }

    public function login()
    {
        $data = array();
        $response = '';
        if (validPostHeader()->status) {
            $loginForm = ConvertObjectToArray(validPostHeader()->data);
            $email = $loginForm['email'];
            $password = $loginForm['password'];
            if (!empty($email)) {
                if (!empty($password)) {
                    $hashed_pwd = $this->_hash($password);
                    $user = $this->api->login($email, $hashed_pwd);
                    if ($user) {
                        if ($user['is_active'] == 'ACTIVE') {
                            $token = bin2hex(openssl_random_pseudo_bytes(64));
                            $user['token'] = $token;
                            echo _success("Success", $user, 200);
                        } else if ($user['is_active'] == 'INACTIVE')
                            echo _error("Your account was in inactive ! Please contact admin", 401);
                        else if ($user['is_active'] == 'BLOCKED')
                            echo _error("Your account was blocked ! Please contact admin", 401);
                        else if ($user['is_active'] == 'DELETED')
                            echo _error("Your account was deleted  !", 401);
                    } else
                        echo _error("Invalid login !", 401);
                } else
                    echo _error("Password is empty !", 404);
            } else
                echo _error("Email Id is empty !", 404);
        } else
            echo _error("Bad Request !", 204);
    }

    public function signup()
    {
        if (validPostHeader()->status) {
            $signupForm = ConvertObjectToArray(validPostHeader()->data);
            $email = $signupForm['email'];
            $password = $signupForm['password'];
            $first_name = $signupForm['first_name'];
            $last_name = $signupForm['last_name'];
            $firebase_token = $signupForm['firebase_token'];
            if (!empty($email)) {
                if (!empty($password)) {
                    $hashed_pwd = $this->_hash($password);
                    $oauth_provider = !empty($signupForm['oauth_provider']) ? $signupForm['oauth_provider'] : '';
                    $created_on = date('Y-m-d h:i:s');
                    if ($this->uapi->isUserNotThere($email)) {
                        $base64_string = !empty($signupForm['image']) ? $signupForm['image'] : '';
                        if (!empty($base64_string)) {
                            $filename_path = md5(time() . uniqid()) . ".jpg";
                            $decoded = base64_decode($base64_string);
                            if (!is_dir("uploads")) {
                                mkdir("uploads");
                            }
                            file_put_contents("uploads/users/" . $filename_path, $decoded);
                            $pdata['image_path'] = base_url('uploads/users/' . $filename_path);
                        }
                        $last_id = $this->uapi->addUser(
                            [
                                'first_name' => $first_name,
                                'last_name' => $last_name,
                                'email' => $email,
                                'password' => $hashed_pwd,
                                'created_on' => $created_on,
                                'oauth_provider' => $oauth_provider,
                                'firebase_token' => $firebase_token
                            ]
                        );
                        if ($last_id) {
                            $mdata = [];
                            $mdata['to_name'] = !empty($first_name) ? $first_name . ' ' . $last_name : $first_name;
                            $mdata['to'] = !empty($email) ? $email : $first_name;
                            $mdata['from'] = "no-reply@saranam.developers.com";
                            $mdata['from_name'] = "Saranam - Support";
                            $mdata['subject'] = "Welcome to Saranam";
                            $user = $this->uapi->getUserById($last_id);
                            $mdata['body'] = $user;
                            $this->sendEmail('email-templates/account-signup', $mdata);
                            $token = bin2hex(openssl_random_pseudo_bytes(64));
                            $user['token'] = $token;
                            echo _success("Hi, " . $first_name . ". Your account has been created successfully !", $user, 200);
                        } else
                            echo _error("Signup failed !", 401);
                    } else
                        echo _error("User already exists with this email !", 409);
                } else
                    echo _error("Password is empty !", 404);
            } else
                echo _error("Email Id is empty !", 404);
        } else
            echo _error("Bad Request !", 204);
    }

    public function oauth()
    {
        $pdata = array();
        if (validPostHeader()->status) {
            $oauthForm = ConvertObjectToArray(validPostHeader()->data);
            $pdata['first_name'] = !empty($oauthForm['first_name']) ? $oauthForm['first_name'] : '';
            $pdata['last_name'] = !empty($oauthForm['last_name']) ? $oauthForm['last_name'] : '';
            $pdata['email'] = !empty($oauthForm['email']) ? $oauthForm['email'] : '';
            $pdata['oauth_provider'] = !empty($oauthForm['oauth_provider']) ? $oauthForm['oauth_provider'] : '';
            $pdata['image_path'] = !empty($oauthForm['image_path']) ? $oauthForm['image_path'] : '';
            $pdata['created_on'] = date('Y-m-d h:i:s');
            if ($this->uapi->isUserNotThere($pdata['email'])) {
                $last_id = insert("tbl_users", $pdata, true);
                $user = $this->uapi->getUserById($last_id);
                echo _success("Hi, " . $pdata['first_name'] . ". Your account has been created successfully !", $user, 200);
            } else {
                $user = $this->uapi->getUserByEmail($pdata['email']);
                echo _success("Success", $user, 200);

            }
        } else {
            echo json_encode(array('msg' => 'The server understood the request, but is refusing to fulfill it.', 'status' => '412'));
        }
    }

    public function update_profile($user_id)
    {
        if (validPostHeader()->status) {
            $profileForm = ConvertObjectToArray(validPostHeader()->data);
            $pdata = array();
            if (!empty($user_id)) {
                if (!empty($profileForm['first_name'])) {
                    $pdata['first_name'] = !empty($profileForm['first_name']) ? $profileForm['first_name'] : '';
                }
                if (!empty($profileForm['last_name'])) {
                    $pdata['last_name'] = !empty($profileForm['last_name']) ? $profileForm['last_name'] : '';
                }
                if (!empty($profileForm['date_of_birth'])) {
                    $pdata['date_of_birth'] = !empty($profileForm['date_of_birth']) ? date('Y-m-d', strtotime($profileForm['date_of_birth'])) : '';
                }
                if (!empty($profileForm['password'])) {
                    $pdata['password'] = !empty($profileForm['password']) ? $this->_hash($profileForm['password']) : '';
                }
                $base64_string = !empty($profileForm['image']) ? $profileForm['image'] : '';
                if (!empty($base64_string)) {
                    $filename_path = md5(time() . uniqid()) . ".jpg";
                    $decoded = base64_decode($base64_string);
                    if (!is_dir("uploads")) {
                        mkdir("uploads");
                    }
                    file_put_contents("uploads/users/" . $filename_path, $decoded);
                    $pdata['image_path'] = base_url('uploads/users/' . $filename_path);
                }
                $this->uapi->updateUser($pdata, $user_id);
                $user = $this->uapi->getUserById($user_id);
                echo _success("Success", $user, 200);
            } else
                echo _error("User id required !", 204);
        } else
            echo _error("Bad Request !", 204);
    }

}
