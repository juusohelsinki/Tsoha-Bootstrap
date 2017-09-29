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
      View::make('band/bandinfo.html', array('bands' => $bands));

    }

        public static function new(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      View::make('band/newband.html');
    }

    public static function store(){
    // POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
    $params = $_POST;
    // Alustetaan uusi Band-luokan olion käyttäjän syöttämillä arvoilla
    $attributes = array(
      'bandname' => $params['bandname'],
      'description' => $params['description'],
      'genre' => $params['genre'],
      'established' => $params['established'],
      'homecity' => $params['homecity'],
      'country' => $params['country'] 
    );

    $band = new Band($attributes);
    $errors = $band->errors();

    if(count($errors) == 0){

    $band->save();
        Redirect::to('/band', array('message' => 'Bändi lisätty onnistuneesti!'));
  } else {
      View::make('band/newband.html', array('errors' => $errors, 'bands' => $attributes));
  }

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle

  }

    public static function edit($id){

    $bands = Band::find($id);
    View::make('band/edit.html', array('bands' => $bands));

    $isloggedin = self::get_user_logged_in();
    if(!$isloggedin){
      View::make('band/index.html', array('bands' => $bands, 'isLoggedIn' => FALSE));
    } else {
      View::make('band/index.html', array('bands' => $bands, 'isloggedin' => TRUE));  
    }
  }

  // Bändin muokkaaminen (lomakkeen käsittely)
  public static function update($id){
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
      View::make('band/edit.html', array('errors' => $errors, 'bands' => $attributes));
    }else{
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
      $band->update($id);

          Redirect::to('/band', array('message' => 'Bändin tietoja muokattu onnistuneesti!'));
  }
  }

  // Pelin poistaminen
  public static function destroy($id){
    // Alustetaan Band-olio annetulla id:llä
    $band = new Band(array('id' => $id));
    // Kutsutaan Band-malliluokan metodia destroy, joka poistaa pelin sen id:llä
    $band->destroy($id);

    // Ohjataan käyttäjä pelien listaussivulle ilmoituksen kera
    Redirect::to('/band', array('message' => 'Bändi on poistettu onnistuneesti!'));
  }
}