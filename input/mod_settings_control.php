<?PHP

include_once("data/mod_settings_data.php");

$files = scandir("theme/");
$views = scandir("output/");

//gets run before the mod's HTML, to populate its feilds with databse values;
if (isset($_GET['id'])) {
	
	//make sure the "id" query string value is only a number from 0-9 
	if (preg_match("/^[0-9]+$/", $_GET['id']) == 1) {
		
		$content = get_single_row($db_conn, 'page', 'page_id', $_GET['id']);
		$mod_list = get_many_rows($db_conn, 'mod');

		//Re-populate an array of values in modlink table based ont the GET page id
		$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $_GET['id']);
	
		if ($content == FALSE || $mod_id_list == FALSE) {
	
			$output[] = "Could not load page_id ".$_GET['id'];
		}
	
	} else {

		//the query stings id value is not a numner 0-9. It is unset to ensure that other functions 
		//that rely on it being set properly ar not effected
		unset($_GET['id']);
		
		$output[] = "Validation failed: Invalid id ".$_GET['id'];
	}
}

//Submit button actions
if (isset($_POST['edit_page'])) {
	
	if (isset($_GET['id'])) {
		
		//Input Validation
		$check = array($_POST['page_title'],$_POST['page_content'], $_POST['page_info']);
		$valid = 0;

		foreach($check as $val) {
		
			if (preg_match("/^[a-z A-Z]+$/", $val) == 1) {
				
				$valid++;
			}
		}

		if ($valid == count($check)) {
		
			edit_page($db_conn,$_POST['page_title'], 
					$_POST['page_content'],
					$_POST['page_info'],
					$_POST['page_view'],
					$_POST['page_menu'],
					$content['page_id']);

		} else {
		
			$output[] = "Input Validation: Failed";
		}

		//remove all mods the selected page is connected too
		delete_mod_link($db_conn,$content);
		
		//loop through enabled checkboxs and add each one to the mod_link table for the selected page
		foreach($_POST['test'] as $val) {
			
			$result = create_modlink($db_conn, $val, $content['page_id']);

			if ($result) {

				$output[] = "Modlink Created for page_id:".$content['page_id'];
			
			} else {
			
				$output[] = "Modlink Failed for page_id". $content['page_id'];
			}
		}
		
	} else {
		
		$output[] = "Please select a page";
	}
}

if (isset($_POST['delete_page'])) {
	
	if (isset($_GET['id'])) {
			
		delete_page($db_conn, $content);
	
	} else {
	
		$output[] = "Please select a page";
	}
}

if (isset($_POST['create_page'])) {
	
	//Input Validation
	$check = array($_POST['page_title'], $_POST['page_content'], $_POST['page_info']);
	$valid = 0;

	foreach($check as $val) {
			
		if (preg_match("/^[a-z A-Z]+$/", $val) == 1) {
			
			$valid++;	
		}
	}

	if ($valid == count($check)) {
		
		create_page($db_conn, $_POST['page_title'], 
				      $_POST['page_content'], 
				      $_POST['page_info'], 
				      $_POST['page_view'], 
				      $_POST['page_menu']);
	
	} else {
	
		$output[] = "Input Validation: Failed";
	}
}

if(isset($_POST['change_title'])) {
	
	//Input Validation
	if (preg_match("/^[a-z A-Z]+$/", $_POST['core_title']) == 1) {
		
		change_title($db_conn, $_POST['core_title']);

	} else {
		
		$output[] = "Input Validation: Failed";
	}
}

if (isset($_POST['add_style'])) {

	$result = insert_style($db_conn, $_POST['style_name']);

	if ($result) {
	
		$GLOBALS['output'][] = "Add Style: Sucessfull";

	} else {
	
		$GLOBALS['output'][] = "Add Style: Failed (databse input)";
	}
}

function change_title($db_conn, $title) {

	$result = set_core_value($db_conn, "title", $title);

	if ($result) {
		
		$GLOBALS['output'][] =  "Title Update: Successfull";

	} else {
		
		$GLOBALS['output'][] =  "Title Update: Failed";
	}
}

function edit_page($db_conn, $name, $content, $info, $view, $menu, $id) {
	
	$result = set_page_details($db_conn, $name, $content, $info, $view, $menu, $id);

	if ($result) {
			
		$GLOBALS['output'][] = "output: Page Details Updated: Succesfull";
		
	} else {
		
		$GLOBALS['output'][] =  "output: Page Details Updated: Failed (database operation)";
	}
}

function delete_mod_link($db_conn, $content) {

	$result = del_row($db_conn, 'modlink', 'modlink_page', $content['page_id']);

	if ($result) {
			
		$GLOBALS['output'][] = "output: mod_link Delete: Successfull";

	} else {
		
		$GLOBALS['output'][] = "output: mod_link Delete: Failed (databse operation)";
	}
}

function delete_page($db_conn, $content) {

	$result = del_row($db_conn, 'page', 'page_id', $content['page_id']);

	if ($result) {
			
		$GLOBALS['output'][] = "output: Page Delete: Successfull";

	} else {
		
		$GLOBALS['output'][] = "output: Page Delete: Failed (databse operation)";
	}
}

function create_page($db_conn, $page_name, $page_content, $page_info, $view, $menu) {
	
	//value for checkbox datase entry
	$menu = ($menu == "on") ? 1 : 0;
	
	$result = insert_page($db_conn, $page_name, $page_content, $page_info, $view, $menu);
	
	if ($result) {
		
		$GLOBALS['output'][] = "New Page Created: Successful";

	} else {
	
		$GLOBALS['output'][] =  "New Page Created: Failed (databse operation)";
	}
}

?>
