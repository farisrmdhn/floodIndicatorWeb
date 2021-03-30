<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'detectors/index';
$route['help'] = 'pages/help';
$route['about'] = 'pages/about';

$route['detectors'] = 'detectors/index';
$route['detector/(:any)'] = 'detectors/detector_details/$1';

$route['notifications'] = 'notifications/index';

$route['add'] = 'pages/add';
$route['manual_input'] = 'pages/manual_input';
$route['get_weather/(:any)'] = 'pages/get_weather/$1';

$route['login'] = 'users/login';
$route['logout'] = 'users/logout';
$route['profile'] = 'users/profile';
$route['edit_profile'] = 'users/edit_profile';
$route['update_profile'] = 'users/update_profile';
$route['change_password'] = 'users/change_password';
$route['forgot_password'] = 'users/forgot_password';
$route['reset_password'] = 'users/reset_password';

$route['admin_dashboard'] = 'users/admin_dashboard';
$route['create_user'] = 'users/create_user';
$route['user_details/(:any)'] = 'users/user_details/$1';
$route['edit_user/(:any)'] = 'users/edit_user/$1';
$route['update_user'] = 'users/update_user';
$route['delete_user'] = 'users/delete_user';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
