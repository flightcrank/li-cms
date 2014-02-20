<?PHP

include_once("data/mod_settings_data.php");

$files = scandir("theme/");
$views = scandir("output/");

//gets run before the HTML, to populate its feilds with databse values;
if (isset($_GET['id'])) {
	
	//TODO: sanitise ALL db input	
	$content = get_single_row($db_conn, 'page', 'page_id', $_GET['id']);
	$mod_list = get_many_rows($db_conn, 'mod');

	//Re-populate an array of values in modlink table based ont the GET page id
	$mod_id_list = get_many_rows($db_conn,"modlink", "modlink_page", $_GET['id']);

	if ($mod_id_list == FALSE) {
	
		$output[] = "Could not load mod_id_list for page_id";
	}
}

//Submit button actions
if (isset($_POST['edit_page'])) {
	
	print_r($_POST['test']);

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
		
		$GLOBALS['output'][] =  "Title Update: Successfull";

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

function edit_page($db_conn, $name, $content, $info, $view, $menu, $id) {
	
	$result = set_page_details($db_conn, $name, $content, $info, $view, $menu, $id);

	if ($result) {
			
		$GLOBALS['output'][] = "output: Page Details Updated: Succesfull";
		
	} else {
		
		$GLOBALS['output'][] =  "output: Page Details Updated: Failed (database operation)";
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
