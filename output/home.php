<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?PHP print $page_current['core_value'] . " - $page"; ?></title>

<?PHP
//print all the style sheets associated with the current page
foreach($style_names as $val) {
	
	echo '<link rel="stylesheet" type="text/css" href="theme/'.$val.'"/>'."\n";
}
?>

</head>
<body>

<div id="wrapper">

<div id="header">
<h1><?PHP echo $page_current['core_value']; ?></h1>

<div id="header_menu">
<?PHP echo $content['page_info']; ?>
<ul>
<?PHP

//nav menu
foreach($pages as $val) {
	
	if ($val['page_menu']) {
		
		printf("<li>| <a href = '%s?page=%s'>%s</a></li>\n",$SERVER['PHP_SELF'],
								$val['page_name'],
								$val['page_name']);
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

//only one instance of each mod allowed due to include_once(). include() will add the same mod more than once
foreach ($mods as $val) {
	
	include_once("output/$val.php");
}

?>
</div><!-- content -->

</div><!-- main -->

</div><!-- wrapper -->

</body>
</html>

