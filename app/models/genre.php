<?php
class Genre extends BaseModel{

  public $genreid, $name;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

// Haetaan kaikki genret genre-taulukosta
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

      $genres[] = new Genre(array(
        'genreid' => $row['genreid'],
        'name' => $row['name']
      ));
    }

// Palautetaan lista genreistä.
    return $genres;
  }

// Haetaan genren id:tä vastaava genren nimi.
  public static function get_genre_name($id){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM genre WHERE genreid = :genreid LIMIT 1');
    $query->execute(array('genreid' => $id));

    $row = $query->fetch();

    $genrename = "";

    if($row){
      $genrename = $row['name'];
    }

// Palautetaan genren nimi.
    return $genrename;
  }
}
?>