<?php

class dbWrapper {
	protected $_mysqli;
 
	public function __construct() {
		$database = "mysqldatabase";
		$host = "localhost";
		$username = "umysqlsername";
		$password = "mysqlpassword";
		$this->_mysqli = new mysqli($host,$username,$password,$database);
		if ($this->_mysqli->connect_errno)
			throw new Exception("MySQLi Error: Could not connect to database.".$this->_mysqli->connect_error);
	}
	
	private function fetch($result) {
		//function from: http://www.php.net/manual/en/mysqli-stmt.bind-result.php  
		$array = array();
		
		if($result instanceof mysqli_stmt) {
			$result->store_result();
			
			$variables = array();
			$data = array();
			$meta = $result->result_metadata();
			
			while($field = $meta->fetch_field())
			$variables[] = &$data[$field->name]; // pass by reference
			
			call_user_func_array(array($result, 'bind_result'), $variables);
			
			$i=0;
			while($result->fetch())
			{
				$array[$i] = array();
				foreach($data as $k=>$v)
					$array[$i][$k] = $v;
				$i++;
				
				// don't know why, but when I tried $array[] = $data, I got the same one result in all rows
			}
		} elseif($result instanceof mysqli_result) {
			while($row = $result->fetch_assoc())
				$array[] = $row;
		}
		
		return $array;
	}

	public function q($query) {
		if (!($statement = $this->_mysqli->prepare($query)))
			throw new Exception("Could not prepare Query".$this->_mysqli->error);
		if (func_num_args() > 1) {
			$x = func_get_args();
			$args = array_merge(array(func_get_arg(1)),
			array_slice($x, 2));
			$args_ref = array();
			foreach($args as $k => &$arg)
				$args_ref[$k] = &$arg;
			
			call_user_func_array(array($statement, 'bind_param'), $args_ref);
		}
		if (!$statement->execute())
			throw new Exception("Could not execute query.\n".$statement->error);
			
		if ($statement->affected_rows > -1)
			return $query->affected_rows;
		
		$result = $this->fetch($statement);	
		$statement->close(); 
		return $result;
	}
	
	public function handle() {
		return $this->_mysqli;
	}
}
?>