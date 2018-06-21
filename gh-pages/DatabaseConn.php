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
			
			public function return_details($user){
				$sql = "select first_name, last_name, nickname from users where username = '$user'";
				$result = mysqli_query($this->mysql_con, $sql);
				$user_data = mysqli_fetch_assoc ($result);
				$_SESSION["firstName"] = $user_data["first_name"];
				$_SESSION["lastName"] = $user_data["last_name"];
				$_SESSION["nickname"] = $user_data["nickname"];
				
			}

			public function update_profile($user, $lastName , $firstName , $nickname){

				$sql = "update users set last_name = '$lastName', first_name = '$firstName', nickname = '$nickname' where username = '$user'";

				$result = mysqli_query($this->mysql_con, $sql);
				header('Location: profile.php');

			}

			public function get_fav_story($user)
			{
				$sql = "select title, story_image from story where story_id = (select favorite_stories from profile where user_id = (select user_id from users where username = '$user'))";
				$result = mysqli_query($this->mysql_con, $sql);
				$user_data = mysqli_fetch_assoc ($result);
				$_SESSION["title"] = $user_data["title"];
				$_SESSION["url"] = $user_data["story_image"];
				$title = $user_data["title"];
				$sql = "select story_id from story where title = '$title'";
				$result = mysqli_query($this->mysql_con, $sql);
				$user_data = mysqli_fetch_assoc($result);
				$s_id = $user_data["story_id"];
				$name = "";
				$sql = "select author_id from book_authors where story_id =". $s_id;
				$result = mysqli_query($this->mysql_con, $sql);
				if ($result){
					$user_data = mysqli_fetch_assoc ($result);
					$a_id = $user_data["author_id"];
					$sql1 = "select name, role from authors where id = $a_id";
					$res = mysqli_query($this->mysql_con, $sql1);
					if ($res){
						$data = mysqli_fetch_assoc ($res);
						if (strcmp($data["role"], "primary") == 0){
							$name = $data["name"];
						}
					}
				}
				$_SESSION["author"] = $name;
			}
			
			public function delete_fav($user)
			{
				$sql = "update profile set favorite_stories = NULL where user_id = (select user_id from users where username = '$user')";
				$result = mysqli_query($this->mysql_con, $sql);
			}

			public function get_family($user)
			{
				$sql = "select family_members from profile where user_id = (select user_id from users where username = '$user')";
				$result = mysqli_query($this->mysql_con, $sql);			
				$user_data = mysqli_fetch_assoc ($result);
				$_SESSION["family"] = $user_data["family_members"];	
			}

			public function set_family($user, $family)
			{
				$sql = "update profile set family_members = '$family' where user_id = (select user_id from users where username = '$user')";
				$result = mysqli_query($this->mysql_con, $sql);
			}
	}
?>