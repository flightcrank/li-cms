<?PHP

$files = scandir("theme/");

function set_core_value($db_conn, $setting, $value) {

	$sql = 'UPDATE "core" SET "core_value" = "' . $value . '" WHERE "core_setting" = "' . $setting . '"';
	$rows = $db_conn->exec($sql);

	return $rows;
}

function insert_page($db_conn, $name, $content, $menu) {
	
	$sql = 'INSERT INTO "page" ("page_name", "page_content", "page_menu") VALUES ("' . $name . '", "' .$content . '",'. $menu .')';
	$rows = $db_conn->exec($sql);
	
	return $rows;
}

function set_page_details($db_conn, $val1, $val2, $val3, $val4) {
	
	if($val3) {
		
		$val3 = 1;

	} else {
		
		$val3 = 0;
	}

	$sql = 'UPDATE "page" SET "page_name" = "'.$val1
	.'", "page_content" = "'.$val2
	.'", "page_menu" = "'.$val3.'" WHERE "page_id" = "'.$val4.'"';
	$rows = $db_conn->exec($sql);

	return $rows;
}

function del_row($db_conn, $table, $key, $val) {
	
	$sql = 'DELETE FROM "'.$table.'" WHERE "'.$key.'" = "'.$val.'"';
	$rows = $db_conn->exec($sql);
	
	return $rows;
}

//close connection
//$db_conn = null;
?>
