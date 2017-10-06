<?php

require 'app/models/review.php';
  class ReviewController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      //View::make('home.html');
      $reviews = Review::all();
      $isloggedin = self::get_user_logged_in();

    if(!$isloggedin){
      View::make('review/reviews.html', array('reviews' => $reviews, 'isloggedin' => FALSE));
    } else {
      View::make('review/reviews.html', array('reviews' => $reviews, 'isloggedin' => TRUE));  
    }

    }

  // Arvostelun poistaminen
  public static function destroy($id){
    self::check_logged_in();
    // Alustetaan Band-olio annetulla id:llä
    $review = new Review(array('id' => $id));
    // Kutsutaan Band-malliluokan metodia destroy, joka poistaa pelin sen id:llä
    $review->destroy($id);

    // Ohjataan käyttäjä pelien listaussivulle ilmoituksen kera
    Redirect::to('/band', array('message' => 'Arvostelu poistettu onnistuneesti!'));
  }

}