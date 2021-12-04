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

$route['default_controller'] = "home";
$route['404_override'] = 'error/_404';

$route['dashboard'] = 'admin/dashboard';

require_once( BASEPATH .'database/DB'. EXT );
$db =& DB();
$query = $db->get( 'nd_menu' );
$menu = $query->result();
$query2 = $db->get( 'nd_menu_detail' );
$menu_detail = $query2->result();
foreach( $menu as $row )
{
	foreach ($menu_detail as $isi) {
		if ($row->id == $isi->menu_id) {
			// $link = base64_encode();
			$route[ rtrim(base64_encode($isi->controller.'/'.$isi->page_link),'=') ] = $isi->controller.'/'.$isi->page_link;
			$route[ rtrim(base64_encode($isi->controller.'/'.$isi->page_link),'=').'/(:any)' ] = $isi->controller.'/'.$isi->page_link.'/(:any)';
			// $route[ rtrim(base64_encode($link),'=') ] = $isi->controller.'/'.$isi->page_link;
			// $route[ $isi->controller.'/(:any)' ]   = 'admin/dashboard';
			// $route[ $row->slug.'/:any' ]         = $row->controller;
			// $route[ $isi->controller ]           = 'admin/dashboard';

		}
	}
}

// $route['(:any)'] = 'admin/dashboard';



/* End of file routes.php */
/* Location: ./application/config/routes.php */