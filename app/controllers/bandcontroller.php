<?php

require 'app/models/band.php';
class BandController extends BaseController{

// Etusivun / listan kaikista bändeistä näyttäminen
  public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
    $bands = Band::all();
    $isloggedin = self::get_user_logged_in();

    View::make('band/index.html', array('bands' => $bands));
  }

  // Yhden bändin esittelysivun näyttäminen
  public static function bandinfo($id){
    $bands = Band::find($id);
    $reviews = Review::findbandreviews($id);
    $members = BandMember::find($id);

    View::make('band/bandinfo.html', array('bands' => $bands, 'members' => $members, 'reviews' => $reviews));

  }

// Uuden bändin lisäämissivu
  public static function new(){

    self::check_logged_in();
    
    $genres = Genre::genre();
    
    View::make('band/newband.html', array('genres' => $genres));
  }

// Bändin tietojen tallentaminen
  public static function store(){
    self::check_logged_in();
    // POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
    $params = $_POST;
    // Alustetaan uusi Band-luokan olion käyttäjän syöttämillä arvoilla

    if(isset($params['genre'])){
    $attributes = array(
      'bandname' => $params['bandname'],
      'description' => $params['description'],
      'established' => $params['established'],
      'homecity' => $params['homecity'],
      'country' => $params['country'],
      'genre' => $params['genre']
    );
  } else {
    $attributes = array(
      'bandname' => $params['bandname'],
      'description' => $params['description'],
      'established' => $params['established'],
      'homecity' => $params['homecity'],
      'country' => $params['country']
    );
  }

    $band = new Band($attributes);
    $errors = $band->errors();

    if(count($errors) == 0){

       $isloggedin = self::get_user_logged_in();
      $band->save($isloggedin->user_accountid);
          // Ohjataan käyttäjä lisäyksen jälkeen bändin esittelysivulle
      Redirect::to('/band', array('message' => 'Bändi lisätty onnistuneesti!'));
    } else {
      $genres = Genre::genre();
          // Jos löytyi virheitä, niin näytetään uuden bändin lisäyssivu uudestaan ja listataan virheet.
      View::make('band/newband.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres));
    }
  }


// Bändin tietojen muokkaussivun näyttäminen
  public static function edit($id){
    self::check_logged_in();
    $bands = Band::find($id);
    $genres = Genre::genre();
    $members = BandMember::find($id);
    View::make('band/edit.html', array('bands' => $bands, 'genres' => $genres, 'members' => $members));

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

      if(count($errors) > 0){
        $genres = Genre::genre();
        View::make('band/edit.html', array('errors' => $errors, 'bands' => $attributes, 'genres' => $genres));
      }else{
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
        $band->update($id);
        // Ohjataan takaisin etusivulle ja näytetään viesti
        Redirect::to('/band', array('message' => 'Bändin tietoja muokattu onnistuneesti!'));
      }
    }

  // Bändin poistaminen
    public static function destroy($id){
      self::check_logged_in();
    // Alustetaan Band-olio annetulla id:llä
      $band = new Band(array('id' => $id));
    // Kutsutaan Band-malliluokan metodia destroy, joka poistaa pelin sen id:llä
      $band->destroy($id);
    // Ohjataan käyttäjä  etusivulle ilmoituksen kera
      Redirect::to('/band', array('message' => 'Bändi on poistettu onnistuneesti!'));
    }
  }