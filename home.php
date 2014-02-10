<?PHP 

include_once("control.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?PHP print $page_current['core_value'] . " - $file_name"; ?></title>
<link rel="stylesheet" type="text/css" href="theme/<?PHP echo $page_style['core_value']?>" />
<link rel="stylesheet" type="text/css" href="<?PHP echo "mod/mod_settings/mod_settings.css"; ?>" />
</head>
<body>

<div id="wrapper">

<div id="header">
<h1><?PHP echo $page_current['core_value']; ?></h1>
<!--<img src="img/logo.gif" alt="Footbag Freaks" height="77" width="203" />-->

<div id="header_menu">
<?PHP echo $content['page_info']; ?>
<ul>
<?PHP

//nav menu
foreach($pages as $val) {
	
	if ($val['page_menu']) {
		
		printf("<li>| <a href = '%s.php'>%s</a></li>\n", $val['page_name'], $val['page_name']);
	}
}

?>
</ul>
</div><!-- header_menu -->

</div><!-- header -->

<div id="main">

<div id="content">
<?PHP

echo $content['page_content'];

foreach ($mod_id_list as $val) {
	
	$result = get_single_row($db_conn, "mod", "mod_id", $val['modlink_mod']);
	$mod_name = $result['mod_name'];
	include_once("mod/$mod_name/$mod_name.php");
}

?>
</div><!-- content -->

</div><!-- main -->

</div><!-- wrapper -->

</body>
</html>

