<?PHP

try {

	$db_conn = new PDO('sqlite:/home/maxim/code/php/lcms/phpliteadmin/lcms.sqlite');
	$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
  
  	echo 'Connection failed: ' . $e->getMessage();
}

function get_single_row($db_conn, $table, $key, $val) {
	
	$sql = 'SELECT * FROM "' . $table . '" WHERE "' . $key . '" = "' . $val . '"';
	$obj = $db_conn->query($sql);
	$result = $obj->fetch();
	
	return $result;
}

function get_many_rows($db_conn, $table, $key = 0, $val = 0) {
	
	$sql = NULL;

	if ($key == 0 && $val == 0) {
		
		$sql = "SELECT * FROM  '$table'";
	
	} else {
	
		$sql = 'SELECT * FROM "' . $table . '" WHERE "' . $key . '" = "' . $val . '"';
	}

	$obj = $db_conn->query($sql);
	$result = $obj->fetchAll();
	
	return $result;
}

//close connection
//$db_conn = null;
?>
