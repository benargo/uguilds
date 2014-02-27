<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';

// themes
//$route['theme/css/controller/([\w\/\-]+)'] = "theme/css/controller/$1";

// roster
$route['roster'] = "roster/table";
$route['roster(.*)\/(race|class|level|rank)=([\w\-]+)(.*)'] = "roster/table/filter";
$route['roster\/.+\/dump'] = "roster/character/dump";
$route['roster\/.+\/profile-picture'] = "roster/character/profile_picture";
$route['roster\/.+\/[a-zA-Z\-]+(\/)?'] = "roster/character/profession";
$route['roster\/.+(\/)?'] = "roster/character";

// Account
$route['account/activate/(.*)'] = "account/activate/verify";
$route['account/logout'] = "account/login/logout";
$route['account\/?'] = "account/manage";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
