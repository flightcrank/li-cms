<?PHP 

$files = scandir("theme/");
?>

<p>
<form name = "core_settings" action = "" method = "post">
<label for = "style"> Style: </label>
<select name = "style"> 
<?PHP

foreach ($files as $val) {

	if (preg_match("/.css$/", $val) == 1) {
		
		print "<option value='$val'> $val </option>";
	}
}

?>
</select>
<label for = "title"> Title: </label>
<input name = "core_title" type = "text" value ="<?PHP echo $title ?>"/>
<input type = "submit" value = "Save Changes" /> 
</form>
</p>
<?PHP 

if (isset($_POST['core_title'])) {

	$result = set_core_value($db_conn, "title", $_POST['core_title']);


	if ($result) {
		
		echo "title changes saved";
	}
	
	$result = set_core_value($db_conn, "style", $_POST['core_title']);
	
	if ($result) {
		
		echo "style changes saved";
	}
}

?>
