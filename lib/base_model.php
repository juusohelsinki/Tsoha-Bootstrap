<?php

class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
  protected $validators;

  public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
    foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
      if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
        $this->{$attribute} = $value;
      }
    }
  }

// Apufunktio lomakkeiden kenttien validoimiseen
  public static function validate_not_too_short_or_null($name, $value, $length){
    $errors = array();
    if($value == '' || $value == null){
      $errors[] = $name. ' ei saa olla tyhjä!';
    } else if(strlen($value) < $length){
      $errors[] = 'Kentän '.$name.' pituuden tulee olla vähintään '.$length.' merkkiä!';
    }

    return $errors;
  }

// Funktio virheiden listaamiseen
  public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
    $errors = array();

      foreach($this->validators as $validator){

        $errors = array_merge($errors, $this->{$validator}());

      }

    return $errors;
  }

}
