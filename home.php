<?PHP 

include_once("data.php"); 

?>

<html>
<head>
<title><?PHP echo get_title($db_conn); ?></title>
</head>
<h1><?PHP echo get_title($db_conn); ?></h1>
<ul>
<?PHP

$pages = get_page_name($db_conn);

foreach($pages as $val) {

	echo "<li>" . $val['page_name'] . "</li>";
}

$content = get_page_content($db_conn, 'home');

echo $content;

?>

</ul>
</html>

