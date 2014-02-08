<?PHP 

include_once("data.php");
session_start();

$file_name = substr($_SERVER['PHP_SELF'], 1, -4); 

$page_current = get_single_row($db_conn,'core','core_setting', 'title');
$pages = get_many_rows($db_conn,"page", "page_menu", "0\" OR page_menu = \"1");//TODO: improve functions SQL generation
$content = get_single_row($db_conn,"page", "page_name", $file_name);
$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $content['page_id']);

?>
<html>
<head>
<title><?PHP print $page_current['core_value'] . " - $file_name"; ?></title>
</head>
<h1><?PHP echo $page_current['core_value']; ?></h1>
<ul>
<?PHP
//nav menu
foreach($pages as $val) {
	
	if ($val['page_menu']) {
		
		printf("<li><a href = '%s.php'>%s</a></li>\n", $val['page_name'], $val['page_name']);
	}
}

?>
</ul>
<?PHP

echo $content['page_content'];

foreach ($mod_id_list as $val) {
	
	$result = get_single_row($db_conn, "mod", "mod_id", $val['modlink_mod']);
	$mod_name = $result['mod_name'];
	include_once("mod/$mod_name/$mod_name.php");
}

?>
</html>

