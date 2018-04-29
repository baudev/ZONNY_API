<?php
/**
 *  configuration de la base des donnée PostgreSQL
 *  configuration pour le CI de GitLab
 */
define('DB_USERNAME_POSTGRE', 'postgres');
define('DB_PASSWORD_POSTGRE', '');
define('DB_HOST_POSTGRE', 'mdillon__postgis');
define('DB_NAME_POSTGRE', 'zonny');

/**
 * configuration de l API facebook
 **/

define('FB_APP_ID', 'xxxx');
define('FB_APP_SECRET', 'xxxx');

/**
 * configuration de firebase
 */

define('FIREBASE_KEY', 'xxxx');

/**
 * configuration compte Google
 */

define('GOOGLE_KEY', 'xxxx');

/**
 * Paramètres
 */
// active le mode debug pour l'affichage des erreurs
define('DEBUG', true);
// au bout de combien de temps l'utilisateur doit renvoyer sa localisation
define('NUMBER_SECONDS_MUST_RESEND_LOCATION', 3600);
// au bout de combien de temps on considère que la position de l'ami n'est plus valide (on considère alors que la position de l'ami est nulle)
define('NUMBER_SECONDS_LOCATION_IS_NO_MORE_VALID', 7200);
// durée maximale d'indisponibilité (attention au picker sur Android)
define('MAX_DURATION_UNAVAILABLE', 86400);
// durée maximale entre le datetime actuel et la datetime du début d'un évènement
define('MAX_START_EVENT_INTERVAL', 86400);
// durée maximale d'un evenement
define('MAX_EVENT_DURATION', 86400);
// durée minimale entre le datetime de fin et du début de l'évèneent
define('MIN_EVENT_DURATION', 60);
// nombre d'élements retournés par page pour les requêtes de l'historique des évènemets
define('NUMBER_EVENTS_BY_PAGE_HISTORIC', 20);
// nombre d'élements retournés par page pour la liste des demandes d'ajouts à un évènement
define('NUMBER_REQUEST_BY_PAGE_EVENT', 20);
// catégories des Lieux de GooglePlaces cherchéées
define('GOOGLE_PLACES_CATEGORIES_RESEARCH', array("amusement_park","aquarium","art_gallery","bar","bowling_alley","cafe","campground","casino","gym","library","liquor_store","meal_takeaway","movie_theater","museum","night_club","park","restaurant","shopping_mall","stadium","zoo"));
// nombre de secondes minimum entre deux recherches de lieux Google à proximité
define('MIN_INTERVAL_GOOGLE_PLACES_RESEARCH', 7200);
// le nombre de lieux Google à proximité de l'utilisateur qu'on analyse pour savoir s'il faut en rechercher d'autres
define('NUMBER_GOOGLE_PLACES_PROXIMITY', 15);
// le nombre à partir on considère qu'il y a assez de lieux Google à proximité de l'utilisateur pour ne pas lancer la recherche
define('NUMBER_UNTIL_GOOGLE_PLACES_RESEARCH_IS_NEEDED', 10);
// la distance maximale entre l'utilisateur et les évènements considérés pour savoir si la recherche des lieux Google doit être lancé
define('MAX_DISTANCE_GOOGLE_PLACES_RESEARCH', 5);
// durée avant laquelle on considère qu'un lieu de Google devrait être actualité
define('MAX_DURATION_BEFORE_REFRESH_GOOGLE_PLACE', 604800); // 1 semaine
// nombre minimum d'amis en commun Facebook pour notifier l'utilisateur de l'arrivée d'un ami
define('MIN_FACEBOOK_COMMUN_FRIENDS', 10);
// nombre minimum de likes en commun Facebook pour notifier l'utilisateur de l'arrivée d'un ami
define('MIN_FACEBOOK_COMMUN_LIKES', 10);