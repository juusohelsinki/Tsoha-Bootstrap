<?php
class Genre extends BaseModel{

  public $genreid, $name;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function genre(){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM genre');
    // Suoritetaan kysely
    $query->execute();
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $genres = array();

    // Käydään kyselyn tuottamat rivit läpi
    foreach($rows as $row){

      // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
      $genres[] = new Genre(array(
        'genreid' => $row['genreid'],
        'name' => $row['name']
      ));
    }

    return $genres;
  }

    public static function get_genre_name($id){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM genre WHERE genreid = :genreid LIMIT 1');
    $query->execute(array('genreid' => $id));

    $row = $query->fetch();

    $genrename = "";

    if($row){
      $genrename = $row['name'];
      }

      return $genrename;
}
}
?>