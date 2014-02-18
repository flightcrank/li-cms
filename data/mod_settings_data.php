<?PHP

function set_core_value($db_conn, $setting, $value) {

	$sql = 'UPDATE "core" SET "core_value" = "' . $value . '" WHERE "core_setting" = "' . $setting . '"';
	$rows = $db_conn->exec($sql);

	return $rows;
}

function insert_page($db_conn, $name, $content, $info, $view, $menu) {
	
	$sql = 'INSERT INTO "page" ("page_name", "page_content", "page_info", "page_view", "page_menu") VALUES ("'.
				$name . '", "' .
				$content . '", "' .
				$info . '" , "' .
				$view . '" ,' .
				$menu .')';
	echo $sql;	
	$rows = $db_conn->exec($sql);
	return $rows;
}

function set_page_details($db_conn, $name, $content, $info, $view, $menu, $id) {
	
	$menu = ($menu) ? 1 : 0;
		
	$sql = 'UPDATE "page" SET "page_name" = "'. $name
	.'", "page_content" = "'. $content
	.'", "page_info" = "'.$info
	.'", "page_view" = "'.$view
	.'", "page_menu" = "'.$menu.'" WHERE "page_id" = "'.$id.'"';
	$rows = $db_conn->exec($sql);

	return $rows;
}

function del_row($db_conn, $table, $key, $val) {
	
	$sql = 'DELETE FROM "'.$table.'" WHERE "'.$key.'" = "'.$val.'"';
	$rows = $db_conn->exec($sql);
	
	return $rows;
}

?>
