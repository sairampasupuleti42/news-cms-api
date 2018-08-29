<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Api';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/categories']="api/categories";
$route['api/scroll/(:any)/posts']="api/scroll-posts/$1";
$route['api/(:any)/posts']="api/posts/$1";
$route['api/post-by-slug/$1']="api/post-by-slug/$1";
$route['api/(:any)/posts-short']="api/posts-short/$1";
$route['api/(:any)/(:any)/posts']="api/cposts/$1/$2";
$route['api/post/(:any)']="api/post/$1";
