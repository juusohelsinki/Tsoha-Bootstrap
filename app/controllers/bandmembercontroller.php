<?php

require 'app/models/band.php';
class BandMemberController extends BaseController{

  public static function edit($id){
    self::check_logged_in();
    $bands = Band::find($id);
    $members = BandMember::find($id);
    View::make('band/editmembers.html', array('bands' => $bands, 'members' => $members));
  }

  // Näytetään lista käyttäjän omista bändeistä
  public static function memberbands(){
    self::check_logged_in();
      $isloggedin = self::get_user_logged_in();
    $bands = BandMember::all($isloggedin->user_accountid);
    View::make('band/ownbands.html', array('bands' => $bands)); 
  }

  // Funktio käyttäjän lisäämiseksi bändin jäseneksi
  public static function addmember(){
    self::check_logged_in();

    $params = $_POST;
    $bandid = $params['bandid'];
    $username = $params['username'];

    $returnCode = BandMember::addbandmember($username, $bandid);

    // Toimitaan funtion addbandmember palautuskoodin mukaisesti:
    if($returnCode == 1) {
      // Käyttäjä oli jo jäsen, pysytään jäsenten muokkaussivulla, näytetään virheilmoitus.
      $isloggedin = self::get_user_logged_in();
      $bands = Band::find($bandid);
      $members = BandMember::find($bandid);
      View::make('band/editmembers.html', array('bands' => $bands, 'errors' => array("Kyseinen käyttäjä on jo bändin jäsen."), 'members' => $members));
    } else if ($returnCode == 2) {
      // Käyttäjä lisätty onnistuneesti. Pysytään jäsenten muokkaussivulla, näytetään viesti onnistumisesta.
      $isloggedin = self::get_user_logged_in();
      $bands = Band::find($bandid);
      $members = BandMember::find($bandid);
      View::make('band/editmembers.html', array('bands' => $bands, 'message' => "Jäsen lisätty onnistuneesti!", 'members' => $members));
    } else if ($returnCode == 3) {
       // Käyttäjää ei löytynyt user_account-taulusta, pysytään jäsenten muokkaussivulla, näytetään virheilmoitus.
      $isloggedin = self::get_user_logged_in();
      $bands = Band::find($bandid);
      $members = BandMember::find($bandid);
      View::make('band/editmembers.html', array('bands' => $bands, 'errors' => array("Kyseistä osoitetta ei löytynyt palvelusta."), 'members' => $members));
    } else {
       // Tapahtui joku muu virhe tietokantaan yhdistettäessä, pysytään jäsenten muokkaussivulla, näytetään virheilmoitus.
      $isloggedin = self::get_user_logged_in();
      $bands = Band::find($bandid);
      $members = BandMember::find($bandid);
      View::make('band/editmembers.html', array('bands' => $bands, 'errors' => array("Tapahtui virhe, yritä myöhemmin uudelleen."), 'members' => $members));
    }
  }

  // Jäsenen poistaminen bändistä.
  public static function destroy($bandid,$user_accountid){
    self::check_logged_in();
    // Alustetaan BandMember-olio annetulla id:llä
    $member = new BandMember(array('id' => $user_accountid));
    // Kutsutaan Band-malliluokan metodia destroy, joka poistaa pelin sen id:llä
    $member->destroy($bandid,$user_accountid);

    $isloggedin = self::get_user_logged_in();
    $bands = Band::find($bandid);
    $members = BandMember::find($bandid);

    View::make('band/editmembers.html', array('bands' => $bands, 'message' => "Jäsen poistettu onnistuneesti!", 'members' => $members));

  }
}