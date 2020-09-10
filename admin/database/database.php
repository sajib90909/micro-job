<?php

$prefix = 'wp_';
$wp_posts_table = $prefix.'posts';
$wp_postmeta_table = $prefix.'postmeta';
$wp_terms_table = $prefix.'terms';
$wp_terms_taxonomy_table = $prefix.'term_taxonomy';
$wp_terms_relationships_table = $prefix.'term_relationships';
class database{
	public $host = DB_HOST;
	public $user = DB_USER;
	public $pass = DB_PASS;
	public $dbname = DB_NAME;


	public $link;
	public $error;

	public function __construct(){
		$this->connectDB();
	}

	private function connectDB(){
		$this ->link = new mysqli($this ->host,$this ->user, $this ->pass , $this ->dbname) ;
		$this ->link->set_charset('utf8mb4');
		if(!$this ->link){
			$this ->error = "connection fail".$this->link->connect_error;
			return false;
		}
	}
	public function select($query){
		$result = $this->link->query($query) or die ($this->link->error.__LINE__);
		if($result){
			return $result;
		}else{
			return false;
		}
	}
  public function insert($query){
    $insert_row = $this->link->query($query) or die ($this->link->error.__LINE__);
    if($insert_row){
      return $insert_row;
    }else{
      return false;
    }
  }
  public function update($query){
    $update_row = $this->link->query($query) or die ($this->link->error.__LINE__);
    if($update_row){
      return $update_row;
    }else{
      return false;
    }
  }
  public function delete($query){
    $delete_row = $this->link->query($query) or die ($this->link->error.__LINE__);
    if($delete_row){
      return $delete_row;
    }else{
      return false;
    }
  }


}


?>
