<?PHP 

include_once("data.php");

$page = substr($_SERVER['PHP_SELF'], 1, -4); 

$title  = get_title($db_conn);
$menu = get_page_name($db_conn);
$content = get_page_content($db_conn, $page);
$page_id = get_page_id($db_conn, $page);
$mod_id_list = get_mod_id_list($db_conn, $page_id);
?>

<html>
<head>
<title><?PHP echo $title . " - " . $page; ?></title>
</head>
<h1><?PHP echo $title; ?></h1>
<ul>

<?PHP

foreach($menu as $val) {

	echo "<li><a href ='/". $val['page_name'] . ".php'>" . $val['page_name'] . "</a></li>\n";
}

echo $content;

foreach ($mod_id_list as $val) {
	
	$mod_name = get_mod_name($db_conn, $val['modlink_mod']);

	include_once("mod/$mod_name/$mod_name.php");
}

?>

</ul>
</html>

