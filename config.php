<?php
/**
 *  configuration de la base des donnée PostgreSQL
 *  configuration pour le CI de GitLab
 */
define('DB_USERNAME_POSTGRE', 'postgres');
define('DB_PASSWORD_POSTGRE', '');
define('DB_HOST_POSTGRE', '127.0.0.1');
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
// nombre de caractères de la clé de l'API
define('API_KEY_CHARAC_NUMBER', 100);
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
// nombre minimum d'amis en commun Facebook pour notifier l'utilisateur de l'arrivée d'un ami
define('MIN_FACEBOOK_COMMUN_FRIENDS', 10);
// nombre minimum de likes en commun Facebook pour notifier l'utilisateur de l'arrivée d'un ami
define('MIN_FACEBOOK_COMMUN_LIKES', 10);