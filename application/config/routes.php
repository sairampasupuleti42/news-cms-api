<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Api';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//---------------Languages------------------
#POST & GET ALL
$route['api/languages']="language/index";
#GET, PUT and DELETE
$route['api/languages/(:num)']="language/get/$1";

//---------------Categories------------------
#POST & GET ALL
$route['api/categories']="category/index";
#GET, PUT and DELETE
$route['api/categories/(:num)']="category/get/$1";
$route['api/categories/parent/(:num)']="category/parent-categories/$1";

//---------------Posts------------------



#POST & GET ALL
$route['api/posts']="post/index";
#GET, PUT and DELETE
$route['api/posts/(:any)']="post/get/$1";

#GET Custom Posts
$route['api/posts/language/(:num)/category/(:num)']="post/custom/$1/$2";
$route['api/posts/category/(:num)']="post/custom-category/$1";
$route['api/posts/language/(:num)']="post/custom-language/$1";

