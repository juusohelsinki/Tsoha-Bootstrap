<?php
class Review extends BaseModel{

  public $bandid, $reviewid, $stars, $bandname, $review, $user_accountid, $userfullname, $errors;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array(
      'validate_review'
    );
  }

// Tallennetaan uusi arvostelu
  public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO review (reviewid, user_accountid, bandid, review, stars) VALUES (DEFAULT, :user_accountid, :bandid, :review, :stars)');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi

    $stars = $this->stars;

// Varmistetaan, että tietokantaan ei pääse arvosteluja, joissa arvostana olisi isompi kuin 5...
    if($this->stars > 5){
      $stars = 5;
    }
// ...tai pienempi kuin 1.
    if($this->stars < 1){
      $stars = 1;
    }

    $query->execute(array(
      'user_accountid' => $this->user_accountid, 
      'bandid' => $this->bandid,
      'review' => $this->review,
      'stars' => $stars
    ));

  }

// Hakee kaikki tietyn bändin arviot
  public static function findbandreviews($id){
    $query = DB::connection()->prepare('SELECT review.*, user_account.firstname, user_account.lastname FROM review LEFT JOIN user_account ON review.user_accountid = user_account.user_accountid WHERE bandid = :bandid');
    $query->execute(array('bandid' => $id));
    $rows = $query->fetchAll();

    $reviews = array();
    
    if($rows){
      foreach($rows as $row){
        $reviews[] = new Review(array(
          'reviewid' => $row['reviewid'],
          'stars' => $row['stars'],
          'review' => $row['review'],
          'user_accountid' => $row['user_accountid'],
          'userfullname' => $row['firstname']." ".$row['lastname']
        ));

      }
      return $reviews;
    }
    return null;
  }

// Hakee yhden tietyllä ID:llä olevan arvion tiedot
  public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM review WHERE reviewid = :reviewid');
    $query->execute(array('reviewid' => $id));
    $rows = $query->fetch();

    if($rows){
      $reviews = new Review(array(
        'reviewid' => $rows['reviewid'],
        'stars' => $rows['stars'],
        'review' => $rows['review'],
        'user_accountid' => $rows['user_accountid']
      ));
      
      return $reviews;
      
    }

    return null;
  }

// Hakee kaikki arvostelut listana (EI KÄYTÖSSÄ, JÄTETTY VARALLE)
  public static function all(){
    $query = DB::connection()->prepare('SELECT review.*, band.bandname FROM review LEFT JOIN band ON band.bandid = review.bandid ORDER BY bandid');
    $query->execute();
    $rows = $query->fetchAll();

    $reviews = array();
    
    if($rows){
      foreach($rows as $row){
        $reviews[] = new Review(array(
          'reviewid' => $row['reviewid'],
          'bandid' => $row['bandid'],
          'bandname' => $row['bandname'],
          'stars' => $row['stars'],
          'review' => $row['review'],
          'user_accountid' => $row['user_accountid']
        ));

      }
      return $reviews;
    }
    return null;
  }


// Päivittää arvostelun tiedot.
  public function update($id){

    $query = DB::connection()->prepare('UPDATE review SET (stars, review) = (:stars, :review) WHERE reviewid=:reviewid');

    $query->execute(array(
      'reviewid' => $id,
      'stars' => $this->stars, 
      'review' => $this->review,
    ));

  }

// Poistaa arvoselun
  public static function destroy($id){

    $query = DB::connection()->prepare('
      DELETE FROM review WHERE reviewid = :reviewid;
      ');
    $query->execute(array('reviewid' => $id));

  }

  // Bändin kotikaupungin validoiminen
  public function validate_review(){

    $errors = $this->validate_not_too_short_or_null('arvio', $this->review, 2);

    return $errors;
  }
}
?>