<?PHP
/*

	Master Controller, all pages have this file included so its variables are available to all other files

*/


//String of the current page the user is on
$page = NULL;

//Global scope array to out put any errors or messages all pages
$output = array();

//make sure page has a value
if ($_GET['page']) {

	$page = $_GET['page'];

} else {

	$page = "Home"; //TODO: will fail if user changes page name in database.
}

//String of the current site title to be used for the html title value 
$page_current = get_single_row($db_conn,'core','core_setting', 'title');

if ($page_current == FALSE) {
	
	$output[] = "Core Title Value: Failure (databse operation)";
}

//Array of the pages to be used for the main menu AND settings list
$pages = get_many_rows($db_conn,"page", "page_menu", "0\" OR page_menu = \"1");//TODO: improve functions SQL generation

if ($pages == FALSE) {
	
	$output[] = "Could not load menu list items";
}

//Array of ALL the page content stored for the current page
$content = get_single_row($db_conn,"page", "page_name", $page);

if ($content == FALSE) {
	
	$output[] = "Could not load page: $page";
}

//Array ALL entrys from the style_link table that match the current page_id value
$page_styles = get_many_rows($db_conn, 'style_link', 'page_id', $content['page_id']);

//string of all the CSS filenames associated with the current page
$style_names = array();

if ($page_styles == FALSE) {
	
	$output[] ="Could not load style_id's from the database";

} else {
	
	//loop through all the syles associated with the current page
	foreach($page_styles as $val) {
		
		$row = get_single_row($db_conn, "style", "style_id", $val['style_id']);

		if ($row == FALSE) {

			$output[] = "Could not retrive style file name/s from database";
			break;//exit out of loop so error message isnt printed many times
	
		} else {
		
			$style_names[] = $row['style_name'];
		}
	}
}

//Array of values in modlink table including the mod_id's that the current page uses
$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $content['page_id']);

//list of mod names used on the current page, used to include the mods output file in the current page
$mods = array();

if ($mod_id_list == FALSE) {
	
	$output[] = "Could not load mod_id_list for page_id";

} else {

	foreach ($mod_id_list as $val) {
	
		$result = get_single_row($db_conn, "mod", "mod_id", $val['modlink_mod']);
		$mods[] = $result['mod_name'];
	}
}

?>
