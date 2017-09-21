<?php

  $routes->get('/', function() {
    BandController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

// Bändien listaussivu
$routes->get('/band', function(){
  BandController::index();
});

// Bändien listaussivu
$routes->get('/band/new', function(){
  BandController::new();
});

$routes->post('/band', function(){
  BandController::store();
});

// Bändin esittelysivu
$routes->get('/band/:id', function($id){
  BandController::bandinfo($id);
});