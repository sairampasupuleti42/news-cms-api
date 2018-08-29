<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Api';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/categories']="category/index";
$route['api/categories/(:num)']="category/get/$1";

#POST & GET ALL
$route['api/categories']="category/index";
#GET, PUT and DELETE
$route['api/categories/(:num)']="category/get/$1";

#POST & GET ALL
$route['api/posts']="post/index";
#GET, PUT and DELETE
$route['api/posts/(:num)']="post/get/$1";
