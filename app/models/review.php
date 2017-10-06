<?php
class Review extends BaseModel{

  public $bandid, $reviewid, $stars, $bandname, $review, $user_accountid;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO review (reviewid, user_accountid, bandid, review, stars) VALUES (DEFAULT, :user_accountid, :bandid, :review, :stars)');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi

    $query->execute(array(
      'user_accountid' => $this->user_accountid, 
      'bandid' => $this->bandid,
      'review' => $this->review,
      'stars' => $this->stars
    ));

    }

     public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM review WHERE bandid = :bandid');
    $query->execute(array('bandid' => $id));
    $rows = $query->fetchAll();

    $reviews = array();
    
    if($rows){
    foreach($rows as $row){
      $reviews[] = new Review(array(
        'reviewid' => $row['reviewid'],
        'stars' => $row['stars'],
        'review' => $row['review'],
        'user_accountid' => $row['user_accountid']
      ));

}
      return $reviews;
    }
    return null;
  }

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



  public static function destroy($id){

        $query = DB::connection()->prepare('
      DELETE FROM review WHERE reviewid = :reviewid;
      ');
    $query->execute(array('reviewid' => $id));

  }

  }
?>