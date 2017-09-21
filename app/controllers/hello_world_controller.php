<?php

require 'app/models/band.php';
  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      echo 'Tämä on etusivu!';
      $rollarit = Band::find(1);
     
    //$bands = Band::all();
    // Kint-luokan dump-metodi tulostaa muuttujan arvon
    //Kint::dump($bands);
//    Kint::dump($rollarit);
    
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
          $bands = Band::all();

     View::make('band/index.html', array('bands' => $bands));

//    $rollarit = Band::find(1);

    }
  }
