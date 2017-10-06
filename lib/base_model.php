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

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      
      //foreach($this->validators as $validator){
        
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
       $validate_bandname = 'validate_bandname';
       $errors = array_merge($errors, $this->{$validate_bandname}());
       $validate_description = 'validate_description';
       $errors = array_merge($errors, $this->{$validate_description}());
       $validate_established = 'validate_established';
       $errors = array_merge($errors, $this->{$validate_established}());
       $validate_country = 'validate_country';
       $errors = array_merge($errors, $this->{$validate_country}());
       $validate_homecity = 'validate_homecity';
       $errors = array_merge($errors, $this->{$validate_homecity}());
      //}
      return $errors;
    }

  }
