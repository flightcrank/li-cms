<?PHP 

include_once("data.php");

$page = substr($_SERVER['PHP_SELF'], 1, -4); 

$page_info = get_single_row($db_conn,'core','core_setting', 'title');
$menu = get_many_rows($db_conn,"page", "page_menu", "1");
$content = get_single_row($db_conn,"page", "page_name", $page);
$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $content['page_id']);

?>
<html>
<head>
<title><?PHP print $page_info['core_value'] . "- $page"; ?></title>
</head>
<h1><?PHP echo $page_info['core_value']; ?></h1>
<ul>
<?PHP

foreach($menu as $val) {

	echo "<li><a href = '" . $val['page_name'] . ".php'>" . $val['page_name'] . "</a></li>\n";
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

