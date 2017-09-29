<?php
class Band extends BaseModel{

  public $bandid, $bandname, $description, $genre, $homecity, $established, $country, $bandimagepath, $errors;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array(
      'validate_bandname', 
      'validate_description',
      'validate_genre',
      'validate_established',
      'validate_homecity',
      'validate_country',
    );
  }

  public static function all(){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM band');
    // Suoritetaan kysely
    $query->execute();
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $bands = array();

    // Käydään kyselyn tuottamat rivit läpi
    foreach($rows as $row){

      // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
      $bands[] = new Band(array(
        'bandid' => $row['bandid'],
        'bandname' => $row['bandname'],
        'description' => $row['description'],
        'genre' => $row['genre'],
        'established' => $row['established'],
        'homecity' => $row['homecity'],
        'country' => $row['country']
      ));
    }

    return $bands;
  }

  public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM band WHERE bandid = :bandid LIMIT 1');
    $query->execute(array('bandid' => $id));

    $row = $query->fetch();

    if($row){
      $band = new Band(array(
        'bandid' => $row['bandid'],
        'bandname' => $row['bandname'],
        'description' => $row['description'],
        'genre' => $row['genre'],
        'established' => $row['established'],
        'homecity' => $row['homecity'],
        'country' => $row['country'] 
      ));

      return $band;
    }

    return null;
  }

  public static function destroy($id){
    $query = DB::connection()->prepare('DELETE FROM band WHERE bandid = :bandid');
    $query->execute(array('bandid' => $id));

  }

  public function update($id){

    $query = DB::connection()->prepare('UPDATE Band SET (bandname, description, genre, established, homecity, country) = (:bandname, :description, :genre, :established, :homecity, :country) WHERE bandid=:bandid');

    $query->execute(array(
      'bandid' => $id,
      'bandname' => $this->bandname, 
      'description' => $this->description,
      'genre' => $this->genre,
      'established' => $this->established,
      'homecity' => $this->homecity,
      'country' => $this->country
    ));
  }

  public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Band (bandname, description, genre, established, homecity, country) VALUES (:bandname, :description, :genre, :established, :homecity, :country) RETURNING bandid');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array(
      'bandname' => $this->bandname, 
      'description' => $this->description,
      'genre' => $this->genre,
      'established' => $this->established,
      'homecity' => $this->homecity,
      'country' => $this->country
    ));

    $row = $query->fetch();

    $this->id = $row['bandid'];
  }

  public function validate_bandname(){
    echo $this->bandname;
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

  public function validate_genre(){
    $errors = array();
    if($this->genre == '' || $this->genre == null){
      $errors[] = 'Genre ei saa olla tyhjä';
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