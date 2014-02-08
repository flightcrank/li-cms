<?PHP 

include_once("mod/mod_settings/mod_settings_data.php");

if (isset($_GET['id'])) {
	//TODO: sanitise db input	
	$content = get_single_row($db_conn, 'page', 'page_id', $_GET['id']);
}
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

if (isset($_GET['id'])) {
	
	//set databse row from $contents selected data
	if (isset($_POST['edit_page'])) {
			
		$result = set_page_details($db_conn,
					$_POST['page_title'],
					$_POST['page_content'],
					$_POST['page_menu'],
					$content['page_id']);
		if ($result) {
		
			//rename page file to its new name
			$r = rename($content["page_name"].".php", $_POST['page_title'].".php");
	
			if($r) {
				
				echo "Page Details Updated: Succesfull";

			} else {
				
				echo "Page Details Updated: Failed";
				//TODO: roll back the changes in databse
			}
		}
	}
}
//button press
if(isset($_POST['create_page'])) {
	
	//new page input check
	$a = array($_POST['page_title'], $_POST['page_content']);
	
	foreach($a as $val) {

		if (preg_match("/^[a-z A-Z]+$/", $val) != 1) {
		
			die("enter txt feilds");
		}
	}

	$menu = 0;
		
	if (isset($_POST['page_menu'])) {
	
		$menu = 1;
	}
	
	$result = insert_page($db_conn, $_POST['page_title'], $_POST['page_content'], $menu);
	
	if($result) {
		
		$new_page = copy("home.php", $_POST['page_title'].".php");
		echo $_SERVER["PHP_SELF"];
		//copy unreliable return value
		//can return 1 without copying file	
		
		if($new_page) {
			
			echo "New Page Created: Successful";

		} else {
			
			echo "New Page Created: Failed";
			//roll back page database insert
		}
	}
}

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

if (isset($_POST['delete_page'])) {
}
?>
