<?PHP

//the current page the user is on
$page = NULL;

//array to out put any errors or message to the current page
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

//String filename of the CSS file to use for styling
$page_style = get_single_row($db_conn, 'core', 'core_setting', 'style');

if ($page_style == FALSE) {
	
	$output[] ="Could not load current site style sheet";
}

//Array of the pages to be used for the main menu AND settings list
$pages = get_many_rows($db_conn,"page", "page_menu", "0\" OR page_menu = \"1");//TODO: improve functions SQL generation

if ($pages == FALSE) {
	
	$output[] = "Could not load menu list items";
}

//Array of page content stored for the current page
$content = get_single_row($db_conn,"page", "page_name", $page);

if ($content == FALSE) {
	
	$output[] = "Could not load page: $page";
}

//Array of values in modlink table including the mod_id's that are link to this page
$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $content['page_id']);

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
