<?php
class BandMember extends BaseModel{

  public $bandid, $user_accountid, $username, $fullname;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  // Lisätään jäsen bändiin, jos sähköpostiosoite löytyy tietokannasta ja kyseinen käyttäjä ei jo ole bändin jäsen.
  public static function addbandmember($username, $bandid){

    //Tarkistetaan löytyykö kyseinen käyttäjä user_account-taulusta.
   $query = DB::connection()->prepare('
    SELECT * FROM user_account WHERE username = :username;
    ');
   $query->execute(array('username' => $username));
   $rows = $query->fetch();

   // Jos käyttäjä löytyy, niin yritetään lisätä kyseinen käyttäjäid userband-tauluun.
   if($rows){
    $user_accountid = $rows['user_accountid'];

    $query = DB::connection()->prepare('
      INSERT INTO userband (user_accountid, bandid) VALUES (:user_accountid, :bandid)
      ');

    // Yritetään lisätä käyttäjä ja bändi userband-tauluun.
    try {
      $query->execute(array(
        'user_accountid' => $user_accountid,
        'bandid' => $bandid
      ));
    }

    // Käsitellään mahdollinen virhekoodi.
    catch (\PDOException $e) {

      // Jos bändin jäsen löytyy jo kannasta, palautuu virhekoodi 23505.
      if($query->errorCode() == "23505")
      {
        return 1;
      } else {
        // Joku muu kyselyssä ilmennyt virhe.
        return 0;
      }
    }
    // Kaikki kunnossa, jäsen lisätty onnistuneesti.
    return 2;
  } else {
    // Osoitetta ei löytynyt user_account-taulusta.
    return 3;    
  }
}

// Haetaan lista bändin jäsenistä.
public static function find($id){
  $query = DB::connection()->prepare('Select userband.*, user_account.username, user_account.firstname, user_account.lastname from userband LEFT JOIN user_account ON userband.user_accountid = user_account.user_accountid WHERE bandid = :bandid;');
  $query->execute(array('bandid' => $id));
  $rows = $query->fetchAll();

  $members = array();

  if($rows){
    foreach($rows as $row){
      $members[] = new Bandmember(array(
        'user_accountid' => $row['user_accountid'],
        'username' => $row['username'],
        'fullname' => $row['firstname']." ".$row['lastname']
      ));

    }
    return $members;
  }
  return null;
}

// Haetaan lista / tiedot bändeistä, joissa käyttäjä on itse jäsenenä
public static function all($user_accountid){
  $query = DB::connection()->prepare('SELECT band.*, array_agg(DISTINCT genre.name) AS genres, AVG(reviewaverage.stars) AS stars
    FROM userband LEFT JOIN band ON userband.bandid = band.bandid LEFT JOIN bandgenre INNER JOIN genre ON bandgenre.genreid = genre.genreid ON band.bandid=bandgenre.bandid LEFT JOIN (SELECT stars, bandid FROM review) reviewaverage ON band.bandid = reviewaverage.bandid WHERE userband.user_accountid = :user_accountid GROUP BY band.bandid ORDER BY stars DESC');
  $query->execute(array('user_accountid' => $user_accountid));

  $rows = $query->fetchAll();
  $bands = array();

    // Käydään kyselyn tuottamat rivit läpi
  if($rows){
    foreach($rows as $row){

      $genre = Band::genres_trim($row['genres']);

      $bands[] = new Band(array(
        'bandid' => $row['bandid'],
        'bandname' => $row['bandname'],
        'description' => $row['description'],
        'established' => $row['established'],
        'homecity' => $row['homecity'],
        'country' => $row['country'],
        'stars' => round($row['stars'], 1),
        'genre' => $genre
      ));
    }
  }
  return $bands;
}

// Poistetaan jäsen bändistä.
public static function destroy($bandid, $user_accountid){

  $query = DB::connection()->prepare('
    DELETE FROM userband WHERE user_accountid = :user_accountid AND bandid = :bandid;
    ');
  $query->execute(array('user_accountid' => $user_accountid, 'bandid' => $bandid));

}

}
?>