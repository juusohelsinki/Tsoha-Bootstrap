<?php

require 'app/models/review.php';
  class ReviewController extends BaseController{

// Näetään kaikki arvostelut listana (EI KÄYTÖSSÄ, JÄTETTY VARALLE)
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

// Näytetään bändin arvostelulomake
  public static function review($id){
    self::check_logged_in();
    $user_accountid = $_SESSION['user_accountid'];
    $bands = Band::find($id);

    View::make('review/review.html', array('bands' => $bands, 'useraccountid' => $user_accountid));

  }

// Tallennetaan arvostelu
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
    $errors = $review->errors();

    if(count($errors) > 0){
      $user_accountid = $_SESSION['user_accountid'];
      $bands = Band::find($id);
      $review = Review::find($id);
      View::make('review/editreview.html', array('errors' => $errors, 'bands' => $bands, 'review' => $review, 'useraccountid' => $user_accountid));
    }else{
      // Kutsutaan alustetun olion save-metodia, joka päivittää pelin tiedot tietokannassa
      $review->save();
      $isloggedin = self::get_user_logged_in();
      Redirect::to('/band', array('message' => 'Arvostelu tallennettu onnistuneesti!'));
      }
    }

  // Arvostelun muokkaaminen 
  public static function edit($id, $bandid){
    self::check_logged_in();
    $review = Review::find($id);
    $bands = Band::find($bandid);
    View::make('review/editreview.html', array('review' => $review, 'bands' => $bands));

  }


  // Arvostelun päivittäminen
   public static function update($id, $bandid){
      self::check_logged_in();
      $params = $_POST;

      $attributes = array(
        'reviewid' => $id,
        'stars' => $params['stars'],
        'review' => $params['review']
      );

    // Alustetaan Review-olio käyttäjän syöttämillä tiedoilla
      $review = new Review($attributes);
      $errors = $review->errors();

    if(count($errors) > 0){
      $user_accountid = $_SESSION['user_accountid'];
      $bands = Band::find($bandid);
      $review = Review::find($id);
      View::make('review/editreview.html', array('errors' => $errors, 'bands' => $bands, 'useraccountid' => $user_accountid, 'review' => $review));
    }else{
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
      $review->update($id,$bandid);
      $isloggedin = self::get_user_logged_in();
      Redirect::to('/band', array('message' => 'Arvostelu tallennettu onnistuneesti!'));
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