# API ZONNY

ZONNY est une application mobile permettant la création d'évènements spontanés et éphémères avec ses amis. Découvrez en plus sur [https://zonny.me](https://zonny.me)
## ESSAYER

 1. Accédez à la documentation [https://zonny.me/docs](https://zonny.me/docs)
 2. Obtenez votre `key_app` en s'enregistrant avec la requête POST `/account`
 3. Vous pouvez maintenant utiliser l'API !


## FONCTIONNALITÉS

 - Comptes utilisateurs. Connexion avec Facebook, SMS, Email et anonymement !
 - Évènements
 - Requêtes pour s'inviter aux évènements publics de ses amis
 - Suggestions d'évènements
 - Chat (via XMPP et Firebase Cloud Messaging)

## INSTALLATION

 1. Configurez le fichier `config.php` 
 2. Installez [PostGIS](https://postgis.net/).
 3. ```composer install```


## CONTRIBUER

Vous voulez contribuer ? Nous acceptons les `Merges Requests` avec plaisir !
Envoyez votre `Merge Request` sur la branche [dev](https://gitlab.com/baudev/ZONNY_API/tree/master/dev).

## COMMENTAIRES

S'il vous plaît reportez les bugs et autres problèmes en [créant une nouvelle `issue`Gitlab](https://gitlab.com/baudev/ZONNY_API/issues/new).

Essayez de respecter la structure suivante le plus possible :

```
## Comportement attendu ##

## Comportement obtenu ##

Configuration (optionnel)
- PHP version:
- Composer version: 
- PostGreSQL version:
```

## LICENCE

Copyright (c) 2018, ZONNY
All rights reserved. 

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met: 
* Redistribution of this software in source or binary forms shall be approved by the repository's owner.
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer. 
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE ZONNY TEAM BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 