<?PHP

include_once("mod/mod_settings/mod_settings_data.php");

$files = scandir("theme/");
$output = "output: ";

//gets run before the <form> to populate its feilds with databse values;
if (isset($_GET['id'])) {
	//TODO: sanitise db input	
	$content = get_single_row($db_conn, 'page', 'page_id', $_GET['id']);
}

//Submit button actions
if (isset($_POST['edit_page'])) {

	if (isset($_GET['id'])) {
		
		edit_page($db_conn,$_POST['page_title'],$_POST['page_content'],$_POST['page_menu'],$content['page_id']);
		
	} else {
		
		$output = "output: Please select a page to edit first";
	}
}

if (isset($_POST['delete_page'])) {
	
	if (isset($_GET['id'])) {
			
		delete_page($db_conn, $content);
	
	} else {
	
		$output = "output: Please select page to delete.";
	}
}

if (isset($_POST['create_page'])) {
	
	create_page($db_conn, $_POST['page_title'], $_POST['page_content'], $_POST['page_menu']);
}

function edit_page($db_conn, $name, $content, $menu, $id) {
	
	$result = set_page_details($db_conn, $name, $content, $menu, $id);

	if ($result) {
		
		//TODO: fix error when it trys to rename the file
		//when the page name remains unchanged
		$r = rename($content["page_name"].".php", $_POST['page_title'].".php");
		
		if($r) {
			
			$GLOBALS['output'] = "output: Page Details Updated: Succesfull";
		
		} else {
		
			$GLOBALS['output'] =  "output: Page Details Updated: Failed (file operation)";
			//TODO: rollback databse operation
		}

	} else {
		
		$GLOBALS['output'] =  "output: Page Details Updated: Failed (database operation)";
	}
}

function delete_page($db_conn, $content) {

	$result = del_row($db_conn, 'page', 'page_id', $content['page_id']);

		if ($result) {
			
			//delete page file
			$r = unlink($content['page_name'].".php");

			if ($r) {
				
				$GLOBALS['output'] = "output: Page Delete: Successfull";
			
			} else {
			
				$GLOBALS['output'] = "output: Page Delete: Failed (file operation)";
				//TODO: rollback databse operation
			}

		} else {
		
			$GLOBALS['output'] = "output: Page Delete: Failed (databse operation)";
		}
}


function create_page($db_conn, $page_name, $page_content, $menu) {
	
	//new page input check
	$check = array($page_name, $page_content);
	
	foreach($check as $val) {
		
		if (preg_match("/^[a-z A-Z]+$/", $val) != 1) {
			
			$GLOBALS['output'] = "output: validation failed, try again.";
			
			return 0;
		}
	}
	
	//value for checkbox datase entry
	$menu = ($menu == "on") ? 1 : 0;
	
	$result = insert_page($db_conn, $page_name, $page_content, $menu);
	
	if ($result) {

		$new_page = copy("home.php", $page_name.".php");
		//TODO: find solution if 'home' page is deleted from database
		
		if($new_page) {
			
			$GLOBALS['output'] = "output: New Page Created: Successful";

		} else {
			
			$GLOBALS['output'] =  "output: New Page Created: Failed (file operation)";
			//TODO: roll back page database insert
		}

	} else {
	
		$GLOBALS['output'] =  "output: New Page Created: Failed (databse operation)";
	}
}

?>
