<?php
class Band extends BaseModel{

  public $bandid, $bandname, $description, $genre, $homecity, $established, $country, $bandimagepath, $stars, $errors;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array(
      'validate_bandname', 
      'validate_established',
      'validate_homecity',
      'validate_country',
      'validate_description'
    );
  }

// Apufunktio, jolla poistetaan genrelistasta {,} ja "-merkit
  public static function genres_trim($rowgenres){
    $genre = "";
    if($rowgenres == "{NULL}"){
        $genre = "Ei määritetty.";
      } else {
      $genre = "";
      $genres = str_replace("}", "", $rowgenres);
      $genres = str_replace("{", "", $genres);
      $genres = str_replace("\"", "", $genres);
      $genres = explode(',', $genres);
      foreach($genres as $index){
          $genre .= $index;
          $genre .= ", ";
      }
      $genre=rtrim($genre,", ");
      }
      return $genre;
  }

// Haetaan lista kaikista bändeistä ja järjestetään bändit arvostelujen keskiarvon mukaan suurimmasta pienimpään.
  public static function all(){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT band.*, array_agg(DISTINCT genre.name) AS genres, AVG(reviewaverage.stars) AS stars
FROM band LEFT JOIN bandgenre INNER JOIN genre ON bandgenre.genreid = genre.genreid ON band.bandid=bandgenre.bandid LEFT JOIN (SELECT stars, bandid FROM review) reviewaverage ON band.bandid = reviewaverage.bandid GROUP BY band.bandid ORDER BY stars DESC NULLS LAST');
    // Suoritetaan kysely
    $query->execute();
    // Haetaan kyselyn tuottamat rivit
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

// Haetaan yhden bändin tiedot. (id = bandid)
  public static function find($id){
    $query = DB::connection()->prepare('SELECT band.*, array_agg(DISTINCT genre.name) AS genres, AVG(reviewaverage.stars) AS stars
FROM band LEFT JOIN bandgenre INNER JOIN genre ON bandgenre.genreid = genre.genreid ON band.bandid=bandgenre.bandid LEFT JOIN (SELECT stars, bandid FROM review) reviewaverage ON band.bandid = reviewaverage.bandid
WHERE band.bandid = :bandid GROUP BY band.bandid LIMIT 1;
');
    $query->execute(array('bandid' => $id));

    $row = $query->fetch();

    if($row){

      $genre = Band::genres_trim($row['genres']);

      $band = new Band(array(
        'bandid' => $row['bandid'],
        'bandname' => $row['bandname'],
        'description' => $row['description'],
        'established' => $row['established'],
        'homecity' => $row['homecity'],
        'country' => $row['country'],
        'stars' => round($row['stars'], 1),
        'genre' => $genre
      ));

      return $band;
    }

    return null;
  }


// Poistetaan bändi tietokannasta.
  public static function destroy($id){

        $query = DB::connection()->prepare('
      DELETE FROM bandgenre WHERE bandid = :bandid;
      ');
    $query->execute(array('bandid' => $id));


    $query2 = DB::connection()->prepare('
      DELETE FROM band WHERE bandid = :bandid;
      ');

    $query2->execute(array('bandid' => $id));

  }

// Päivitetään bändin tiedot tietokannassa ja lisätään bändin genret genre-tauluun.
  public function update($id){

    $query = DB::connection()->prepare('UPDATE Band SET (bandname, description, established, homecity, country) = (:bandname, :description, :established, :homecity, :country) WHERE bandid=:bandid');

    $query->execute(array(
      'bandid' => $id,
      'bandname' => $this->bandname, 
      'description' => $this->description,
      'established' => $this->established,
      'homecity' => $this->homecity,
      'country' => $this->country
    ));

foreach($this->genre as $genre){
      $query = DB::connection()->prepare('INSERT INTO bandgenre (genreid, bandid) VALUES (:genreid, :bandid)');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi

    $query->execute(array(
      'genreid' => $genre, 
      'bandid' => $id
    ));
  }

  }

  // Lisätään bändi tietokantaan ja listään userband-kantaan kirjautuneen käyttäjän id, sekä bändin id.
  public function save($user_accountid){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Band (bandname, description, established, homecity, country) VALUES (:bandname, :description, :established, :homecity, :country) RETURNING bandid');

    $query->execute(array(
      'bandname' => $this->bandname, 
      'description' => $this->description,
      'established' => $this->established,
      'homecity' => $this->homecity,
      'country' => $this->country
    ));

    $row = $query->fetch();

// Jos bändin lisääminen onnistui, lisätään myös userband-tauluun tiedot
if($row){
    $query = DB::connection()->prepare('INSERT INTO userband (user_accountid, bandid) VALUES (:user_accountid, :bandid)');

    $query->execute(array(
      'user_accountid' => $user_accountid, 
      'bandid' => $row['bandid'],
    ));

    $this->id = $row['bandid'];

// Lisätään genret, jos niitä on valittuna.
    if(isset($this->genre)){
    foreach($this->genre as $genre){
      $query = DB::connection()->prepare('INSERT INTO bandgenre (genreid, bandid) VALUES (:genreid, :bandid)');

    $query->execute(array(
      'genreid' => $genre, 
      'bandid' => $this->id
    ));
  }

    }
  }

  }

// Validaattorit (validate_not_too_short_or_null löytyy base_model.php -tiedostosta)//

  // Bändin nimen validoiminen
  public function validate_bandname(){

    $errors = $this->validate_not_too_short_or_null('Bändin nimi', $this->bandname, 1);

    return $errors;
  }

// Bändin kuvauksen validoiminen
  public function validate_description(){

    $errors = $this->validate_not_too_short_or_null('Kuvaus', $this->description, 2);

    return $errors;
  }

// Bändin perustamisvuoden validoiminen (kokonaisluku, numeroita, ei tyhjä)
  public function validate_established(){
    $errors = array();
    $established = $this->established;
    if($established == '' || $established == null){
      $errors[] = 'Perustamisvuosi ei saa olla tyhjä.';
    } else if(!ctype_digit(strval($established))){
      $errors[] = 'Perustamisvuosi pitää olla kokonaisluku.';
    }

    return $errors;
  }

// Bändin kotikaupungin validoiminen
  public function validate_homecity(){

    $errors = $this->validate_not_too_short_or_null('Kotikaupunki', $this->homecity, 2);

    return $errors;
  }

// Bändin kotimaan validoiminen
  public function validate_country(){

    $errors = $this->validate_not_too_short_or_null('Maa', $this->country, 4);

    return $errors;
  }

}
?>