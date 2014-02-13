<?PHP

include_once("mod/mod_settings/mod_settings_data.php");

$files = scandir("theme/");
$output = array();

//gets run before the HTML, to populate its feilds with databse values;
if (isset($_GET['id'])) {
	//TODO: sanitise db input	
	$content = get_single_row($db_conn, 'page', 'page_id', $_GET['id']);
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
					$_POST['page_menu'],
					$content['page_id']);

		} else {
		
			$output[] = "Input Validation: Failed";
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
		
		create_page($db_conn, $_POST['page_title'], $_POST['page_content'], $_POST['page_info'], $_POST['page_menu']);
	
	} else {
	
		$output[] = "Input Validation: Failed";
		var_dump($check);
	}
}

if(isset($_POST['change_title'])) {
	
	//Input Validation
	if (preg_match("/^[a-z A-Z]+$/", $_POST['core_title']) == 1) {
		
		change_title($db_conn, $_POST['core_title'], $_POST['core_style']);

	} else {
		
		$output[] = "Input Validation: Failed";
	}
}

function change_title($db_conn, $title, $style) {

	$result = set_core_value($db_conn, "title", $title);

	if ($result) {
		
		$GLOBALS['output'][] =  "Title Update: Successfull";//TODO: This output will not be seen if second message
								  // is sucessfull
	} else {
		
		$GLOBALS['output'][] =  "Title Update: Failed";
	}
	
	$result = set_core_value($db_conn, "style", $style);
	
	if ($result) {
		
		$GLOBALS['output'][] =  "Style Update: Successfull";

	} else {

		$GLOBALS['output'][] =  "Style Update: Failed";
	}
}

function edit_page($db_conn, $name, $content, $info, $menu, $id) {
	
	$result = set_page_details($db_conn, $name, $content, $info, $menu, $id);

	if ($result) {
		
		//TODO: fix error when it trys to rename the file
		//when the page name remains unchanged
		$r = rename($content["page_name"].".php", $_POST['page_title'].".php");
		
		if($r) {
			
			$GLOBALS['output'][] = "output: Page Details Updated: Succesfull";
		
		} else {
		
			$GLOBALS['output'][] =  "output: Page Details Updated: Failed (file operation)";
			//TODO: rollback databse operation
		}

	} else {
		
		$GLOBALS['output'][] =  "output: Page Details Updated: Failed (database operation)";
	}
}

function delete_page($db_conn, $content) {

	$result = del_row($db_conn, 'page', 'page_id', $content['page_id']);

		if ($result) {
			
			//delete page file
			$r = unlink($content['page_name'].".php");

			if ($r) {
				
				$GLOBALS['output'][] = "output: Page Delete: Successfull";
			
			} else {
			
				$GLOBALS['output'][] = "output: Page Delete: Failed (file operation)";
				//TODO: rollback databse operation
			}

		} else {
		
			$GLOBALS['output'][] = "output: Page Delete: Failed (databse operation)";
		}
}


function create_page($db_conn, $page_name, $page_content, $page_info, $menu) {
	
	//value for checkbox datase entry
	$menu = ($menu == "on") ? 1 : 0;
	
	$result = insert_page($db_conn, $page_name, $page_content, $page_info, $menu);
	
	if ($result) {

		$new_page = copy("home.php", $page_name.".php");
		//TODO: find solution if 'home' page is deleted from database
		
		if($new_page) {
			
			$GLOBALS['output'][] = "New Page Created: Successful";

		} else {
			
			$GLOBALS['output'][] =  "New Page Created: Failed (file operation)";
			//TODO: roll back page database insert
		}

	} else {
	
		$GLOBALS['output'][] =  "New Page Created: Failed (databse operation)";
	}
}

?>
