<?php
	header("Access-Control-Allow-Origin: *");
	
	define("username", "root");
	define("password" , "-");
	define("host", "localhost");
	define("database_name", "tw");
	
	class Database {
		private $mysql_con = false;
		private $data = array(); // datele ce vor fi preluate din DB
		private $Error;
		private $Row;
		private $user_data = array(); // datele despre user si parola
			
			public function __construct(){ 
				$this->result = 0;
				$this->Errno = 0;
				$this->Row = -1;
				$this->Error = '';
				$this->mysql_con = new mysqli(host, username, password, database_name);
				if(mysqli_connect_errno()) {
					die("Database error:" . mysqli_connect_error()); // a survenit o eroare la conexiunea cu baza de date
				}
			}
			
			public function __destruct () {
				if ($this->mysql_con) {
					mysqli_close ($this->mysql_con); // inchidem conexiunea
				}  
			}

			public function query ($q) {     
				$this->result = mysqli_query ($this->mysql_con, $q);     
				$this->Row = 0; 
				$this->Errno = mysqli_errno($this->mysql_con); 
				$this->Error = mysqli_error($this->mysql_con);     
				if (!$this->result) { 
					echo ("Bad SQL query: " . $q); 
					echo ($this->Error);
				}	
				return $this->result; 
			}

			public function next_record() { 
				$this->data = mysqli_fetch_array ($this->result); 
				$this->Row++;
				$this->Errno = mysqli_errno($this->mysql_con); 
				$this->Error = mysqli_error($this->mysql_con); 
				$stat = is_array ($this->data); // returnam inregistrarea gasita 
				if (!$stat) { // nu mai exista o alta inregistrare 
					mysqli_free_result ($this->result);
					$this->result = 0; 
				} 
				return $stat; 
			}
			
			public function users_check($user, $pass){
				$sql = "select username, password from users where username = '$user' and password = '$pass'";
				$result = mysqli_query($this->mysql_con, $sql);
				$user_data = mysqli_fetch_assoc ($result);
				if ($user_data["username"] == $user and $user_data["password"] == $pass)
					return 1;
				return 0;
			}
			
	}
?>