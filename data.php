<?php

try {

	$db_conn = new PDO('sqlite:/home/maxim/code/php/lcms/phpliteadmin/lcms.sqlite');
	//$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
  
  	echo 'Connection failed: ' . $e->getMessage();
}

function get_title($db_conn) {
	
	$obj = $db_conn->query('SELECT "core_value" FROM "core" WHERE "core_setting" = "title"');
	$result = $obj->fetch();

	return $result['core_value'];
}

function get_page_name($db_conn) {
	
	$obj = $db_conn->query('SELECT "page_name" FROM "page" WHERE "page_menu" = 1');
	$result = $obj->fetchAll();

	return $result;
}

function get_page_content($db_conn, $p_name) {
	
	$obj = $db_conn->query('SELECT "page_content" FROM "page" WHERE "page_name" ="' . $p_name . '"');
	$result = $obj->fetch();

	return $result['page_content'];
}

function get_page_id($db_conn, $p_name) {
	
	$obj = $db_conn->query('SELECT "page_id" FROM "page" WHERE "page_name" = "' . $p_name . '"');
	$result = $obj->fetch();
	
	return $result['page_id'];
}

function get_mod_id_list($db_conn, $page_id) {
	
	$obj = $db_conn->query('SELECT "modlink_mod" FROM "modlink" WHERE "modlink_page" = ' . $page_id);
	$result = $obj->fetchAll();
	
	return $result;
}

function get_mod_name($db_conn, $mod_id) {
	
	$obj = $db_conn->query('SELECT "mod_name" FROM "mod" WHERE "mod_id" = ' . $mod_id);
	$result = $obj->fetch();
	
	return $result['mod_name'];
}

//close connection
//$db_conn = null;
?>
