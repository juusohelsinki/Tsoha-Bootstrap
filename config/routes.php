<?php

// Etusivu / Bändien listaussivu
  $routes->get('/', function() {
    BandController::index();
  });

// Etusivu / Bändien listaussivu
  $routes->post('/', function() {
    BandController::index();
  });

// Sandbox
  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

// Bändien listaussivu
$routes->get('/band', function(){
  BandController::index();
});

// Bändien listaussivu
$routes->get('/band/', function(){
  BandController::index();
});

// Uuden bändin lisäyssivu
$routes->get('/band/new', function(){
  BandController::new();
});

// Bändin tietojen päivittäminen
$routes->post('/band/update', function(){
  BandController::update();
});

// Bändin esittelysivu
$routes->get('/band/:id', function($id){
  BandController::bandinfo($id);
});

// Bändin muokkauslomakkeen esittäminen
$routes->get('/band/:id/edit', function($id){
  BandController::edit($id);
});

// Bändin jäsenten listaus / muokkaussivu
$routes->get('/band/:id/editmembers', function($id){
  BandMemberController::edit($id);
});

  // Bändin arvostelulomakkeen esittäminen
$routes->get('/review/:id/review', function($id){
  ReviewController::review($id);
});

// Kaikki arvostelut listana
$routes->get('/reviews', function(){
  ReviewController::index();
});

  // Kaikki arvostelut listana
$routes->get('/reviews/', function(){
  ReviewController::index();
});

  // Lista käyttäjän omista bändeistä
$routes->get('/ownbands', function(){
  BandMemberController::memberbands();
});

// Bändin tietojen tallentaminen
$routes->post('/band', function(){
   BandController::store();
});

  // Bändin muokkaussivu
$routes->post('/band/:id/edit', function($id){
  BandController::update($id);
});

  // Bändin arvostelun tallentaminen
$routes->post('/review/:id/:useraccountid/review', function($id,$user_accountid){
  ReviewController::storereview($id,$user_accountid);
});

  // Arvostelun poisto
$routes->post('/review/:id/destroy', function($id){
  ReviewController::destroy($id);
});

// Arvostelun muokkaaminen
$routes->post('/review/:id/:bandid/edit', function($id,$bandid){
  ReviewController::edit($id,$bandid);
});

  // Arvostelun päivittäminen
$routes->post('/review/:id/:bandid/update', function($id, $bandid){
  ReviewController::update($id,$bandid);
});

  // Bändin poisto
$routes->post('/band/:id/destroy', function($id){
  BandController::destroy($id);
});

  // Jäsenen poistaminen bändistä
$routes->post('/member/:id/:useraccountid/destroy', function($id,$user_accountid){
  BandMemberController::destroy($id,$user_accountid);
});

  // Jäsenen lisääminen bändiin
$routes->post('/member/addmember', function(){
  BandMemberController::addmember();
});

  // Kirjautumislomakkeen esittäminen
$routes->get('/login', function(){
  UserController::login();
});

  // Kirjautumisen käsittely
$routes->post('/login', function(){
  UserController::handle_login();
});

// Uloskirjautuminen
$routes->post('/logout', function(){
  UserController::logout();
});