<?PHP 

include_once("mod/mod_settings/mod_settings_control.php");

?>
<div id = "settings">
<form name = "core_settings" action = "" method = "post">
<fieldset>
<legend>Core Settings</legend>
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
</fieldset> <!--core settings-->
<fieldset>
<legend>Page Settings</legend>
<label> Select Page: </label>
<ul>
<?PHP

foreach($pages as $val) {
		
	printf( "<li>%s <a href='%s?id=%s'>select</a>", $val['page_name'], $_SERVER['PHP_SELF'], $val['page_id']);
}

?>
</ul>
<ul>
<li>
<label for = "page_title"> Page Name: </label>
<input name = "page_title" type = "text" value = "<?PHP if($_GET['id']){ echo $content['page_name'];} ?>" />
</li>
<li>
<label for = "page_info"> Page Info: </label>
<input name = "page_info" type = "text" value = "<?PHP if($_GET['id']){ echo $content['page_info'];} ?>" />
</li>
<li>
<label for = "page_content"> Page description: </label>
<textarea name = "page_content" type = "text">
<?PHP if($_GET['id']){ echo $content['page_content'];} ?>
</textarea>
</li>
<li>
<label for = "page_menu"> Include In Menu: </label>
<input name = "page_menu" type = "checkbox" /><!--TODO: select check box when a valid GET id = is selected-->
</li>
<li>
<label for = "">Options: </label>
<input type = "submit" name = "edit_page" value = "Edit Page" /> 
<input type = "submit" name = "delete_page" value = "Delete Page" /> 
<input type = "submit" name = "create_page" value = "Create Page" /> 
</li>
</ul>
</fieldset><!--page settings-->
<fieldset>
<legend>Output</legend>
<?PHP 
	echo print_r($output); 
?>
</fieldset><!--output-->
</form>
</div><!--settings-->
