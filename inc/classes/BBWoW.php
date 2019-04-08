<?php
class BBWoW
{
	private $host = "89.38.146.86";
	private $port = 3306;
  private $user = "tahir";
  private $password = "Ayg9!@xq";
  private $dbname = "wotlk_auth";
  private $wpdb;

  function __construct($db = "wotlk_auth")
  {
    $this->wpdb = new wpdb($this->user, $this->password, $db, $this->host);
  }// constructor end here

  function __destruct()
	{
    if ( $this->wpdb->use_mysqli ) {
        @mysqli_close( $this->wpdb->dbh );
    } else {
        @mysql_close( $this->wpdb->dbh );
    }
    unset($this->wpdb);
	}// destructor functoin end here

  private function sha_password($user,$pass)
  {
      $user = strtoupper($user);
      $pass = strtoupper($pass);
      return strtoupper(SHA1($user.':'.$pass));
  }

  public function UpdateUser($username, $password, $email){
		if($username && $password && $email){

	    $username = strtoupper($username);
	    $passup = strtoupper($password);
	    $pass = $this->sha_password($username, $passup);

			$sql = $this->wpdb->prepare('SELECT username FROM account WHERE username = %s LIMIT 1', $username);
			$results = $this->wpdb->get_results($sql, ARRAY_A);

			if($results){
				$update_id = $this->wpdb->update('account',
					array(
						'username' => $username,
						'sha_pass_hash' => $pass,
						'email' => $email,
						'v' => '0',
						's' => '0'
					),
					array( 'username' => $username ),
					array('%s','%s','%s', '%s', '%s'),
					array('%s')
				);
				//alert($update_id);
				//exit();
			}
			else{
				$this->wpdb->insert('account',
					array(
						'username' => $username,
						'sha_pass_hash' => $pass,
						'email' => $email,
						'reg_mail' => $email,
					),
					array('%s','%s','%s','%s')
				);
			}
		}// if end here
  }


  public function displayAllUsers(){
    $rows = $this->wpdb->get_results("select * FROM account", ARRAY_A);
    echo "<ul>";
    foreach ($rows as $row) :
       echo "<li>".$row['username']."</li>";
    endforeach;
    echo "</ul>";
  }
}





//https://community.trinitycore.org/topic/63-php-utilities/
//displayAllUsers();
/*
//db($wotlk);
//@mysql_close( $wotlk->dbh );
*/
