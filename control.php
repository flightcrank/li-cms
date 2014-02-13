<?PHP

//databse function for core site
include_once("data.php");

//the current page the user is on
$page = NULL;

//make sure page has a value
if ($_GET['page']) {

	$page = $_GET['page'];

} else {

	$page = "home";
}

//String of the current site title to be used for the html title value 
$page_current = get_single_row($db_conn,'core','core_setting', 'title');

if ($page_current == FALSE) {
	
	echo "Core Title Value: Failure (databse operation)";
}

//String filename of the CSS file to use for styling
$page_style = get_single_row($db_conn, 'core', 'core_setting', 'style');

//Array of the pages to be used for the main menu
$pages = get_many_rows($db_conn,"page", "page_menu", "0\" OR page_menu = \"1");//TODO: improve functions SQL generation

//String of the content stored for the current page
$content = get_single_row($db_conn,"page", "page_name", $page);

//Array of mods that are to be included on the current page
$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $content['page_id']);

?>
