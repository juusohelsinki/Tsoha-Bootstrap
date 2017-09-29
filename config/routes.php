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

$routes->post('/band/update', function(){
  BandController::update();
});

// Bändin esittelysivu
$routes->get('/band/:id', function($id){
  BandController::bandinfo($id);
});

$routes->get('/band/:id/edit', function($id){
  // Bändin muokkauslomakkeen esittäminen
  BandController::edit($id);
});
$routes->post('/band/:id/edit', function($id){
  // Bändin muokkaaminen
  BandController::update($id);
});

$routes->post('/band/:id/destroy', function($id){
  // Bändin poisto
  BandController::destroy($id);
});

$routes->get('/login', function(){
  // Kirjautumislomakkeen esittäminen
  UserController::login();
});
$routes->post('/login', function(){
  // Kirjautumisen käsittely
  UserController::handle_login();
});