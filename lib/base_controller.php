<?php

  class BaseController{

    public static function get_user_logged_in(){
      // Toteuta kirjautuneen käyttäjän haku tähän
      if(isset($_SESSION['user_accountid'])){
      $user_id = $_SESSION['user_accountid'];
      // Pyydetään User-mallilta käyttäjä session mukaisella id:llä
      $user = User::find($user_id);

      return $user;
    }

    // Käyttäjä ei ole kirjautunut sisään
      return null;
    }

    public static function check_logged_in(){
      // Toteuta kirjautumisen tarkistus tähän.
      // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
    }

  }
