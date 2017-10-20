<?php
class UserController extends BaseController{
  public static function login(){
      View::make('user/login.html');
  }

  // Käsitellään sisäänkirjautuminen
  public static function handle_login(){
    $params = $_POST;

    $user = User::authenticate($params['username'], $params['password']);

  // Jos väärä tunnus / salasana, niin ohjataan takaisin login-sivulle.
    if(!$user){
      View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
    }else{
  // Jos oikeat tunnukset, niin ohjataan etusivulle.
      $_SESSION['user_accountid'] = $user->user_accountid;

      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->firstname . ' ' . $user->lastname . '!'));
    }
  }

  // Uloskirjautumisen käsittely.
  public static function logout(){
    $_SESSION['user_accountid'] = null;
    Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
  }
}
?>