<?php
header('Content-Type: text/html; charset=utf-8');
class Test extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model', 'api', TRUE);
        $this->load->model('User_model', 'uapi', TRUE);
    }

    function index($lang='tel')
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
        print_r($posts);
    }
}