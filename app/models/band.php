<?php
class Band extends BaseModel{

  public $bandid, $bandname, $description, $genre, $homecity, $established, $country, $bandimagepath, $stars, $errors;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array(
      'validate_bandname', 
      'validate_description',
      'validate_established',
      'validate_homecity',
      'validate_country'
    );
  }

  public static function genres_trim($rowgenres){
    $genre = "";
    if($rowgenres == "{NULL}"){
        $genre = "Ei määritetty.";
      } else {
      $genre = "";
      $genres = str_replace("}", "", $rowgenres);
      $genres = str_replace("{", "", $genres);
      $genres = explode(',', $genres);
      foreach($genres as $index){
          $genre .= $index;
          $genre .= ", ";
      }
      $genre=rtrim($genre,", ");
      }
      return $genre;
  }

  public static function all(){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT band.*, array_agg(genre.name) AS genres FROM band LEFT JOIN bandgenre INNER JOIN genre ON bandgenre.genreid = genre.genreid ON band.bandid=bandgenre.bandid GROUP BY band.bandid');
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
        'genre' => $genre
      ));
    }
  }
    return $bands;

  }

  public static function find($id){
    $query = DB::connection()->prepare('SELECT band.*, array_agg(genre.name) AS genres 
FROM band LEFT JOIN bandgenre INNER JOIN genre ON bandgenre.genreid = genre.genreid ON band.bandid=bandgenre.bandid WHERE band.bandid = :bandid GROUP BY band.bandid LIMIT 1;
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
        'genre' => $genre
      ));

      return $band;
    }

    return null;
  }

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

  public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Band (bandname, description, established, homecity, country) VALUES (:bandname, :description, :established, :homecity, :country) RETURNING bandid');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi

    $query->execute(array(
      'bandname' => $this->bandname, 
      'description' => $this->description,
      'established' => $this->established,
      'homecity' => $this->homecity,
      'country' => $this->country
    ));

    $row = $query->fetch();

    $this->id = $row['bandid'];

    foreach($this->genre as $genre){
      $query = DB::connection()->prepare('INSERT INTO bandgenre (genreid, bandid) VALUES (:genreid, :bandid)');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi

    $query->execute(array(
      'genreid' => $genre, 
      'bandid' => $this->id
    ));

    }

  }

  public function validate_bandname(){
    $errors = array();
    if($this->bandname == '' || $this->bandname == null){
      $errors[] = 'Nimi ei saa olla tyhjä!';
    }

    return $errors;
  }

  public function validate_description(){
    $errors = array();
    if($this->description == '' || $this->description == null){
      $errors[] = 'Kuvaus ei saa olla tyhjä';
    }

    return $errors;
  }

  public function validate_established(){
    $errors = array();
    if($this->established == '' || $this->established == null){
      $errors[] = 'Perustamisvuosi ei saa olla tyhjä';
    }
    if(!is_numeric($this->established)){
      $errors[] = 'Perustamisvuosi pitää olla numeroja.';
    }

    return $errors;
  }

  public function validate_homecity(){
    $errors = array();
    if($this->homecity == '' || $this->homecity == null){
      $errors[] = 'Kotikaupunki ei saa olla tyhjä';
    }

    return $errors;
  }

  public function validate_country(){
    $errors = array();
    if($this->country == '' || $this->country == null){
      $errors[] = 'Maa ei saa olla tyhjä';
    }

    return $errors;
  }

}
?>