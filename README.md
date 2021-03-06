<p align="center">
	<img src="https://cdn.pbrd.co/images/Hp02HzE.png" />
</p>

ZONNY is a mobile application allowing the creation of spontaneous and ephemeral events with user's friends. More about on [https://zonny.me](https://zonny.me)

[![ZONNY VERSION](https://img.shields.io/badge/dynamic/json.svg?url=https://raw.githubusercontent.com/baudev/ZONNY_API/master/composer.json&label=stable&query=$.version&colorB=1976d2)]()
[![ZONNY VERSION](https://img.shields.io/badge/dynamic/json.svg?url=https://raw.githubusercontent.com/baudev/ZONNY_API/dev/composer.json&label=unstable&query=$.version&colorB=dc8623)]()
[![Build Status](https://travis-ci.org/baudev/ZONNY_API.svg?branch=dev)](https://travis-ci.org/baudev/ZONNY_API)
[![Coverage Status](https://coveralls.io/repos/github/baudev/ZONNY_API/badge.svg?branch=travis_ci)](https://coveralls.io/github/baudev/ZONNY_API?branch=travis_ci)
[![swagger validator](https://img.shields.io/swagger/valid/2.0/https/raw.githubusercontent.com/baudev/ZONNY_API/dev/doc/swagger.json.svg)](https://zonny.me/docs)
![Discord](https://img.shields.io/discord/440222477562413056.svg) 


| [Try it out](#try-it-out) | [Features](#features) | [Installation](#installation) | [Contribute](#contribute) | [Remarks](#remarks) | [License](#license) |
| :----------- | :------: | ------------: | :----------- | :------: | ------------: |

**[ :speech_balloon: Questions / Comments? Join us on Discord!](https://discord.gg/P3szxKG)**

## TRY IT OUT
 1. Access to the documentation at [https://zonny.me/docs](https://zonny.me/docs)
 2. Get your `key_app` while registering with the POST request: `/account`
 3. You can start trying it!


## FEATURES

- User accounts. Connection with Facebook, Messages, Emails and anonymously!
- Events
- Requests to become a guest at public friends' events.
- Events' suggestions
- Chat (with XMPP and Firebase Cloud Messaging)

## INSTALLATION

 1. Configure the `config.php` file. 
 2. Install [PostGIS](https://postgis.net/)
 3. Install database `/vendor/bin/doctrine orm:schema-tool:update --force`
 4. Install dependencies ```composer install```


## CONTRIBUTE

You want to contribute? We accept all `Pull Requests` with pleasure!
Send your `Pull Request` on the [dev](https://github.com/baudev/ZONNY_API/tree/dev) branch.

## REMARKS

If there is any bug or other problem, please report it by [creating a new Github `issue`](https://github.com/baudev/ZONNY_API/issues/new).

Try respecting the following structure as possible:

```
## Expected behavior ##
...

## Obtained behavior ##
...

Configuration (optional)
- PHP version:
- Composer version: 
- PostGreSQL version:
```

## LICENSE

Copyright (c) 2018, ZONNY
All rights reserved. 

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met: 
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer. 
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE ZONNY TEAM BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 
