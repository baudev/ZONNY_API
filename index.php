<?php

require_once 'config.php';
require_once 'vendor/autoload.php';

use Slim\Slim;
use ZONNY\Controllers\Accounts\Authentificate;
use ZONNY\Controllers\Accounts\DeleteAccount;
use ZONNY\Controllers\Accounts\GetAccount;
use ZONNY\Controllers\Accounts\CreateEditAccount;
use ZONNY\Controllers\Accounts\UpdateLocation;
use ZONNY\Controllers\Accounts\Unavailable;
use ZONNY\Controllers\Events\CreateDeleteEventRequest;
use ZONNY\Controllers\Events\CreateEvent;
use ZONNY\Controllers\Events\DeleteEvent;
use ZONNY\Controllers\Events\EditEvent;
use ZONNY\Controllers\Events\EditEventRequest;
use ZONNY\Controllers\Events\EditResponse;
use ZONNY\Controllers\Events\GetAllEventRequests;
use ZONNY\Controllers\Events\GetEvent;
use ZONNY\Controllers\Events\GetHistoric;
use ZONNY\Controllers\Friends\GetAllFriends;
use ZONNY\Controllers\Friends\GetFriend;
use ZONNY\Controllers\Friends\GetInvitableFriends;
use ZONNY\Controllers\Friends\PutFriendLink;
use ZONNY\Controllers\GooglePlaces\GetGooglePlace;
use ZONNY\Controllers\Map\ShowMap;
use ZONNY\Utils\Application;

$app = new Slim();

/**
 * Démarre l'application
 */
Application::init($app);

/**
 * Routes concernant la carte
 */

$app->get('/', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    Application::response(200, new ShowMap());
});

/**
 * Routes concernant le compte de  l'utilisateur
 */
// récupérer les informations concernant l'utilisateur
$app->get('/account', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    Application::response(200, new GetAccount());
});

// ajouter ou modifier l'utilisateur au lancement de l'application
$app->map('/account', function () use ($app) {
    $fb_access_token = $app->request->post('fb_access_token');
    $expire = $app->request->post('expiration_datetime_token');
    Application::response(200, new CreateEditAccount($fb_access_token, $expire));
})->via('PUT', 'POST');

// mettre à jour la localisation de l'utilisateur
$app->put('/account/location', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $latitude = $app->request->post('latitude');
    $longitude = $app->request->post('longitude');
    Application::response(200, new UpdateLocation($latitude, $longitude));
});

// supprime le compte
$app->delete('/account', array(new Authentificate(), 'AuthUser'), function () {
    Application::response(200, new DeleteAccount());
});

// Récupère la liste de tous les amis de l'utilisateur sur l'application
$app->get('/account/friends', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    Application::response(200, new GetAllFriends());
});

// Récupère la liste de tous les amis invitables à un évènement
$app->get('/account/invitable_friends/(:event_id)', array(new Authentificate(), 'AuthUser'), function ($event_id = 0) use ($app) {
    Application::response(200, new GetInvitableFriends($event_id));
});

// Modifie une relation d'amitié
$app->put('/account/friends', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $friend_id = $app->request->put('friend_id');
    $authorization      = $app->request->put('authorization');
    Application::response(200, new PutFriendLink($friend_id, $authorization));
});

// Récupère les informations concernant un ami
$app->get('/account/friends/:friend_id', array(new Authentificate(), 'AuthUser'), function ($friend_id) use ($app) {
    Application::response(200, new GetFriend($friend_id));
});

// Met l'utilisateur comme indispobible jusqu'à la date demandée
$app->put('/account/unavailable/start', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $datetime      = $app->request->put('datetime');
    $unavailable = new Unavailable();
    Application::response(200, $unavailable->setUnavailable($datetime));
});

// Arrête le mode indisponible de l'utilisateur
$app->put('/account/unavailable/end', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $unavailable = new Unavailable();
    Application::response(200, $unavailable->setFree());
});
/**
 * Routes concernant les évènements
 */
// Permet de créer un évènement
$app->post('/event', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $name               = $app->request->post('name');
    $public             = $app->request->post('public');
    $latitude           = $app->request->post('latitude');
    $longitude          = $app->request->post('longitude');
    $start_time         = $app->request->post('start_time');
    $end_time           = $app->request->post('end_time');
    $information       = $app->request->post('information');
    $picture_url        = $app->request->post('picture_url');
    $invited_friends_id = $app->request->post('invited_friends_id');
    Application::response(200, new CreateEvent($name, $public, $latitude, $longitude, $start_time, $end_time, $information, $picture_url, $invited_friends_id));
});

// Permet d'afficher l'historique des évènements par page
$app->get('/event/historic/:page', array(new Authentificate(), 'AuthUser'), function ($page) use ($app) {
    Application::response(200, new GetHistoric(Application::getUser(), $page));
});

// Permet de récupérer les informations concernant un évènement
$app->get('/event/:event_id', array(new Authentificate(), 'AuthUser'), function ($event_id) use ($app) {
    Application::response(200, new GetEvent($event_id));
});

// Permet de modifier un évènement
$app->put('/event', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $event_id = $app->request->post('event_id');
    $name               = $app->request->post('name');
    $public             = $app->request->post('public');
    $latitude           = $app->request->post('latitude');
    $longitude          = $app->request->post('longitude');
    $start_time         = $app->request->post('start_time');
    $end_time           = $app->request->post('end_time');
    $information       = $app->request->post('information');
    $picture_url        = $app->request->post('picture_url');
    $invited_friends_id = $app->request->post('invited_friends_id');
    Application::response(200, new EditEvent($event_id, $name, $public, $latitude, $longitude, $start_time, $end_time, $information, $picture_url, $invited_friends_id));
});

// Permet de répondre à un évènement
$app->put('/event/response', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $event_id = $app->request->post('event_id');
    $response = $app->request->post('response');
    Application::response(200, new EditResponse($event_id, $response));
});

// Route permettant de supprimer un évènement
$app->delete('/event/:event_id', array(new Authentificate(), 'AuthUser'), function ($event_id) use ($app) {
    Application::response(200, new DeleteEvent($event_id));
});

// Permet de notifier au créateur d'un évènement public qu'un de ses amis est intéressé
$app->post('/event/request', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $event_id = $app->request->post('event_id');
    $create_request = new CreateDeleteEventRequest();
    Application::response(200, $create_request->createRequest($event_id));
});

// Permet de supprimer une requête
$app->delete('/event/request/:event_id', array(new Authentificate(), 'AuthUser'), function ($event_id) use ($app) {
    $event_id = $app->request->post('event_id');
    $create_request = new CreateDeleteEventRequest();
    Application::response(200, $create_request->deleteRequest($event_id));
});

// Permet de répondre à une requête
$app->put('/event/request', array(new Authentificate(), 'AuthUser'), function () use ($app) {
    $request_id = $app->request->post('request_id');
    $response   = $app->request->post('response');
    Application::response(200, new EditEventRequest($request_id, $response));
});

// Permet de récupérer toutes les requêtes concernant un évènements
$app->get('/event/request/:event_id/:page', array(new Authentificate(), 'AuthUser'), function ($event_id, $page) use ($app) {
    Application::response(200, new GetAllEventRequests($event_id, $page));
});
/**
 * Routes concernant les suggestions d'évènements
 */
// Permet de récupérer les informations concernant un évènement Facebook sugéré
$app->get('/events_public/:id',  array(new Authentificate(), 'AuthUser'), function ($event_id) use ($app) {
    // TODO CONTROLLER
});

// Permet de récupérer une liste d'évènements Facebook suggérés de la catégorie demandée
$app->get('/events_public/search/:category/:page',  array(new Authentificate(), 'AuthUser'), function ($category, $page) use ($app) {
    // TODO CONTROLLER
});

// Permet une suggestion d'évènement Facebook aléatoirement
$app->get('/events_public/random/',  array(new Authentificate(), 'AuthUser'), function () use ($app) {
    // TODO CONTROLLER
});

/**
 * Routes concernant les lieux suggérés
 */
// Permet de récupérer les informations concernant un lieu Google
$app->get('/place_public/:id',  array(new Authentificate(), 'AuthUser'), function ($place_id) use ($app) {
    Application::response(200, new GetGooglePlace($place_id));
});

/**
 * On lance Slim
 */
$app->run();



