<?php
/* ----------------------------------- */ 
/* Class: MySQL						   */
/* ----------------------------------- */
class MySQL {    
	var $host	= "";
	var $user	= "";
	var $pass	= "";
	var $base	= "";
	var $conn	= NULL;
	var $debug	= true;

	function MySQL ($host=DB_HOST, $base=DB_NAME, $user=DB_USER, $pass=DB_PASS) {
		$this->host = $host;
		$this->base = $base;
		$this->user = $user;
		$this->pass = $pass;
		
		return $this->open();
	}
	function error($message = "") {
		if ($this->debug) {
			if ($message != "") {
				die($message);
			} else {
				die(mysql_error());
			}
		} else {
			die("");
		}
	}
	function open() {
		if (!is_resource($this->conn)) {
			$this->conn = mysql_connect($this->host, $this->user, $this->pass);
			if (is_resource($this->conn)) {
				if (!mysql_select_db($this->base, $this->conn)) {
					$this->error();
				} else {
					mysql_query("SET NAMES utf8");
				}
			} else {
				$this->error();
			}
		}
		return $this;
	}
	function close() {
		if (is_resource($this->conn)) {		    
			mysql_close($this->conn);
			$this->conn = NULL;	
		}		
		return $this;
	}
	function status() {
		return ( $this->conn ? 'open': 'close' );
	}
	function insert($sql = "") {
		if ($sql != "") {
			mysql_query($sql, $this->conn) or $this->error();
			return mysql_insert_id($this->conn);
		} else {
			return $this->error('Empty query');
		}
	}
	function execute($sql = "") {
		if ($sql != "") {
			return mysql_query($sql, $this->conn) or $this->error();
		} else {
			return $this->error('Empty query');
		}
	}
	function query($sql = "") {
		if ($sql != "") {
			if (is_resource($this->conn)) {
				return new MySQLData($sql, $this->conn);
			}
		} else {
			$this->error('Empty query');
		}
	}
}
/* ----------------------------------- */ 
/* Class: MySQLData					   */
/* ----------------------------------- */
class MySQLData {		
	var $conn = NULL;
	var $data = NULL;
	var $item = NULL;
	var $rows = 0;
	var $curr = 0;
	var $size = 0;
	
	var $query = "";
	var $debug = true;
	
	function MySQLData($query, $conn) {
		$this->query = $query;
		$this->conn = $conn;
	}
	function paginate($curr, $size) {
		$this->curr = $curr;
		$this->size = $size;
	}
	function read() {
		if ($this->curr != 0) {		
			$this->data = mysql_query("SELECT count(*) as total FROM (".$this->query.") as total") or $this->error();
			$this->next();
			$this->rows = $this->field('total');
			
			$this->data = mysql_query($this->query." LIMIT ".(($this->curr-1)*$this->size).", ".$this->size, $this->conn) or $this->error();
		} else {
			$this->data = mysql_query($this->query, $this->conn) or $this->error();
			$this->rows = mysql_num_rows($this->data);
		}
	}
	function error($message = "") {
		if ($this->debug) {
			if ($message != "") {
				die($message);
			} else {
				die(mysql_error());
			}
		} else {
			die("");
		}
	}
	function total() {
		return $this->rows;	
	}
	function page() {
		return $this->curr;	
	}
	function pages() {
		$tpages = ceil($this->rows/$this->size);
		return $tpages;
	}
	function count() {
		if (is_resource($this->data)) {
			return mysql_num_rows($this->data);
		} else {
			$this->error('No Data');	
		}
	}
	function next() {
		if (is_resource($this->data)) {
			$this->item = mysql_fetch_assoc($this->data);
			return $this->item;
		} else {
			$this->error('No Data');	
		}
	}
	function field($field) {
		return $this->item[$field];	
	}
	
	function seek($num) {
		if (is_resource($this->data)) {
	    	mysql_data_seek($this->data, $num-1);
			$this->item = mysql_fetch_assoc($this->data);
			return $this->item;
			//return $this->read();
		} else {
			$this->error('No Data');
		}
	}
	function first() {
		return $this->seek(0);	
	}
}
?>