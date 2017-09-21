<?php

require 'app/models/band.php';
  class BandController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      $bands = Band::all();
      View::make('band/index.html', array('bands' => $bands));

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
    $band = new Band(array(
      'bandname' => $params['bandname'],
      'description' => $params['description'],
    ));

    // Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
    $band->save();

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
    Redirect::to('/band' . $band->bandid, array('message' => 'Bändi lisätty onnistuneesti!'));
  }

  }
