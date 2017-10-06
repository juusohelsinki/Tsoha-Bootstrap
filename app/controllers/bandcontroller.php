<?php

require 'app/models/band.php';
  class BandController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      $bands = Band::all();
      $isloggedin = self::get_user_logged_in();

    if(!$isloggedin){
      View::make('band/index.html', array('bands' => $bands, 'isloggedin' => FALSE));
    } else {
      View::make('band/index.html', array('bands' => $bands, 'isloggedin' => TRUE));  
    }

    }

    public static function bandinfo($id){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      $bands = Band::find($id);
      $reviews = Review::find($id);
      
      View::make('band/bandinfo.html', array('bands' => $bands, 'reviews' => $reviews));

    }

        public static function new(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
          self::check_logged_in();
          $genres = Genre::genre();
      View::make('band/newband.html', array('genres' => $genres));
    }

    public static function store(){
      self::check_logged_in();
    // POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
    $params = $_POST;
    // Alustetaan uusi Band-luokan olion käyttäjän syöttämillä arvoilla
    $attributes = array(
      'bandname' => $params['bandname'],
      'description' => $params['description'],
      'established' => $params['established'],
      'homecity' => $params['homecity'],
      'country' => $params['country'],
      'genre' => $params['genre']
    );

    $band = new Band($attributes);
    $errors = $band->errors();

    if(count($errors) == 0){

    $band->save();
        Redirect::to('/band', array('message' => 'Bändi lisätty onnistuneesti!'));
  } else {
      $genres = Genre::genre();
      View::make('band/newband.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres));
  }

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle

  }

    public static function edit($id){
    self::check_logged_in();
    $bands = Band::find($id);
    $genres = Genre::genre();
    View::make('band/edit.html', array('bands' => $bands, 'genres' => $genres));

    $isloggedin = self::get_user_logged_in();
    if(!$isloggedin){
      View::make('band/index.html', array('bands' => $bands, 'isLoggedIn' => FALSE));
    } else {
      View::make('band/index.html', array('bands' => $bands, 'isloggedin' => TRUE));  
    }
  }

  public static function review($id){
    self::check_logged_in();
    $user_accountid = $_SESSION['user_accountid'];
    $bands = Band::find($id);
    View::make('band/review.html', array('bands' => $bands, 'useraccountid' => $user_accountid));

    $isloggedin = self::get_user_logged_in();
    if(!$isloggedin){
      View::make('band/index.html', array('bands' => $bands, 'isLoggedIn' => FALSE));
    } else {
      View::make('band/index.html', array('bands' => $bands, 'isloggedin' => TRUE));  
    }
  }

  public static function storereview($id, $user_accountid){
    self::check_logged_in();
    $params = $_POST;

    $attributes = array(
      'bandid' => $id,
      'user_accountid' => $user_accountid,
      'review' => $params['review'],
      'stars' => $params['stars'],
    );

    $review = new Review($attributes);
    //$errors = $review->errors();


    /*if(count($errors) > 0){
      $genres = Genre::genre();
      View::make('band/edit.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres));
    }else{*/
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
      $review->save();

          Redirect::to('/band', array('message' => 'Arvostelu tallennettu onnistuneesti!'));
  /*}*/
  }


  // Bändin muokkaaminen (lomakkeen käsittely)
  public static function update($id){
    self::check_logged_in();
    $params = $_POST;

    $attributes = array(
      'bandid' => $id,
      'bandname' => $params['bandname'],
      'description' => $params['description'],
      'genre' => $params['genre'],
      'established' => $params['established'],
      'homecity' => $params['homecity'],
      'country' => $params['country']
    );

    // Alustetaan Band-olio käyttäjän syöttämillä tiedoilla
    $band = new Band($attributes);
    $errors = $band->errors();


    if(count($errors) > 0){
      $genres = Genre::genre();
      View::make('band/edit.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres));
    }else{
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
      $band->update($id);

          Redirect::to('/band', array('message' => 'Bändin tietoja muokattu onnistuneesti!'));
  }
  }

  // Pelin poistaminen
  public static function destroy($id){
    self::check_logged_in();
    // Alustetaan Band-olio annetulla id:llä
    $band = new Band(array('id' => $id));
    // Kutsutaan Band-malliluokan metodia destroy, joka poistaa pelin sen id:llä
    $band->destroy($id);

    // Ohjataan käyttäjä pelien listaussivulle ilmoituksen kera
    Redirect::to('/band', array('message' => 'Bändi on poistettu onnistuneesti!'));
  }
}