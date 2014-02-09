<?PHP 

include_once("mod/mod_settings/mod_settings_control.php");

?>
<p>
<form name = "core_settings" action = "" method = "post">
<label for = "core_style"> Style: </label>
<select name = "core_style"> 
<?PHP

foreach ($files as $val) {

	if (preg_match("/.css$/", $val) == 1) {
		
		print "<option value='$val'> $val </option>\n";
	}
}

?>
</select>
<label for = "core_title"> Title: </label>
<input name = "core_title" type = "text" value ="<?PHP echo $page_current['core_value'];?>"/>
<input type = "submit" name = "change_title" value = "Save Changes" />
<br/>
<label> Select Page: </label>
<ul>
<?PHP

foreach($pages as $val) {
		
	printf( "<li>%s <a href='%s?id=%s'>select</a>", $val['page_name'], $_SERVER['PHP_SELF'], $val['page_id']);
}

?>
</ul>
<label for = "">Options: </label>
<input type = "submit" name = "edit_page" value = "Edit Page" /> 
<input type = "submit" name = "delete_page" value = "Delete Page" /> 
<input type = "submit" name = "create_page" value = "Create Page" /> 
<br />
<label for = "page_title"> Page Name: </label>
<input name = "page_title" type = "text" value = "<?PHP echo $content['page_name']; ?>" />
<label for = "page_content"> Page description: </label>
<input name = "page_content" type = "text" value = "<?PHP echo $content['page_content']; ?>" />
<label for = "page_menu"> Include In Menu: </label>
<input name = "page_menu" type = "checkbox" />
</form>
</p>
<?PHP 

echo $output;

if(isset($_POST['change_title'])) {
	
	$result = set_core_value($db_conn, "title", $_POST['core_title']);

	if ($result) {
		
		echo "title changes saved";
	}
	
	$result = set_core_value($db_conn, "style", $_POST['core_style']);
	
	if ($result) {
		
		echo "style changes saved";
	}
}

?>
