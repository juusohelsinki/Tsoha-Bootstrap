<?php

require 'app/models/band.php';
class BandController extends BaseController{

  public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
    $bands = Band::all();
    $isloggedin = self::get_user_logged_in();

    View::make('band/index.html', array('bands' => $bands, 'isloggedin' => $isloggedin));
    
  }

  public static function bandinfo($id){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
    $bands = Band::find($id);
    $reviews = Review::find($id);

    View::make('band/bandinfo.html', array('bands' => $bands, 'reviews' => $reviews, 'isloggedin' => self::get_user_logged_in()));

  }

  public static function new(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
    self::check_logged_in();
    
    $genres = Genre::genre();
    
    View::make('band/newband.html', array('genres' => $genres, 'isloggedin' => self::get_user_logged_in()));
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
      Redirect::to('/band', array('message' => 'Bändi lisätty onnistuneesti!', 'isloggedin' => self::get_user_logged_in()));
    } else {
      $genres = Genre::genre();
      View::make('band/newband.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres, 'isloggedin' => self::get_user_logged_in()));
    }

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle

  }

  public static function edit($id){
    self::check_logged_in();
    $bands = Band::find($id);
    $genres = Genre::genre();
    $isloggedin = self::get_user_logged_in();
    View::make('band/edit.html', array('bands' => $bands, 'genres' => $genres, 'isloggedin' => $isloggedin));

  }

  public static function review($id){
    self::check_logged_in();
    $user_accountid = $_SESSION['user_accountid'];
    $bands = Band::find($id);
    $isloggedin = self::get_user_logged_in();

    View::make('band/review.html', array('bands' => $bands, 'useraccountid' => $user_accountid, 'isloggedin' => $isloggedin));

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
      $isloggedin = self::get_user_logged_in();
      Redirect::to('/band', array('message' => 'Arvostelu tallennettu onnistuneesti!', self::get_user_logged_in()));
      /*}*/
    }


  // Bändin muokkaaminen (lomakkeen käsittely)
    public static function update($id){
      self::check_logged_in();
      $params = $_POST;

if(isset($params['genre'])){
      $genre = $params['genre'];
    } else {
      $genre = array();
    }

      $attributes = array(
        'bandid' => $id,
        'bandname' => $params['bandname'],
        'description' => $params['description'],
        'genre' => $genre,
        'established' => $params['established'],
        'homecity' => $params['homecity'],
        'country' => $params['country']
      );

    // Alustetaan Band-olio käyttäjän syöttämillä tiedoilla
      $band = new Band($attributes);
      $errors = $band->errors();

      $isloggedin = self::get_user_logged_in();

      if(count($errors) > 0){
        $genres = Genre::genre();
        View::make('band/edit.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres, 'isloggedin' => $isloggedin));
      }else{
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
        $band->update($id);

        Redirect::to('/band', array('message' => 'Bändin tietoja muokattu onnistuneesti!', 'isloggedin' => $isloggedin));
      }
    }

  // Pelin poistaminen
    public static function destroy($id){
      self::check_logged_in();
    // Alustetaan Band-olio annetulla id:llä
      $band = new Band(array('id' => $id));
    // Kutsutaan Band-malliluokan metodia destroy, joka poistaa pelin sen id:llä
      $band->destroy($id);
      $isloggedin = self::get_user_logged_in();
    // Ohjataan käyttäjä pelien listaussivulle ilmoituksen kera
      Redirect::to('/band', array('message' => 'Bändi on poistettu onnistuneesti!', 'isloggedin' => $isloggedin));
    }
  }