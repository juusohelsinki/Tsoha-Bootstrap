<?php
class User extends BaseModel{

public $user_accountid, $username, $email, $usertype, $firstname, $lastname, $password;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function authenticate($name, $password){

    $query = DB::connection()->prepare('SELECT * FROM user_account WHERE username = :name AND password = :password LIMIT 1');
$query->execute(array('name' => $name, 'password' => $password));
$row = $query->fetch();

if($row){

  $user = new User(array(
  'user_accountid' => $row['user_accountid'],
  'username' => $row['username'],
  'email' => $row['email'],
  'usertype' => $row['usertype'],
  'firstname' => $row['firstname'],
  'lastname' => $row['lastname'],
  'password' => $row['password']
));

  return $user;

} else {
  return null;
}
}

public static function find($id){
   
    $query = DB::connection()->prepare('SELECT * FROM user_account WHERE user_accountid = :user_accountid LIMIT 1');
    $query->execute(array('user_accountid' => $id));

    $row = $query->fetch();

if($row){

  $user = new User(array(
  'user_accountid' => $row['user_accountid'],
  'username' => $row['username'],
  'email' => $row['email'],
  'usertype' => $row['usertype'],
  'firstname' => $row['firstname'],
  'lastname' => $row['lastname'],
  'password' => $row['password']
));

      return $user;
    } 
    else {
      return null;
    }
}
}
?>