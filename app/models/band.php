<?php
class Band extends BaseModel{

public $bandid, $bandname, $description, $bandimagepath;

public function __construct($attributes){
    parent::__construct($attributes);
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
        'bandimagepath' => $row['bandimagepath']
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
        'bandimagepath' => $row['bandimagepath']
      ));

      var_dump($band);
      return $band;
    }

    return null;
  }

  public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Band (bandname,  description) VALUES (:bandname, :description) RETURNING bandid');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array('bandname' => $this->bandname, 'description' => $this->description));
    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    $row = $query->fetch();
    // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
    $this->id = $row['bandid'];
  }
}
?>
