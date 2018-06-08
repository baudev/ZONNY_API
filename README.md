![](https://cdn.pbrd.co/images/Hp02HzE.png)

[![pipeline status](https://gitlab.com/baudev/ZONNY_API/badges/v0.2-beta/pipeline.svg)](https://gitlab.com/baudev/ZONNY_API/commits/v0.2-beta)
[![coverage report](https://gitlab.com/baudev/ZONNY_API/badges/v0.2-beta/coverage.svg)](https://gitlab.com/baudev/ZONNY_API/commits/v0.2-beta)

ZONNY is a mobile application allowing the creation of spontaneous and ephemeral events with user's friends. More about on [https://zonny.me](https://zonny.me)

| [Try it out](#try-it-out) | [Features](#features) | [Installation](#installation) | [Contribute](#contribute) | [Remarks](#remarks) | [License](#license) |
| :----------- | :------: | ------------: | :----------- | :------: | ------------: |

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
 2. Install [PostGIS](https://postgis.net/).
 3. ```composer install```


## CONTRIBUTE

You want to contribute? We accept all `Merges Requests` with pleasure!
Send your `Merge Request` on the [dev](https://gitlab.com/baudev/ZONNY_API/tree/master/dev) branch.

## REMARKS

If there is any bug or other problem, please report it by [creating a new GitLab `issue`](https://gitlab.com/baudev/ZONNY_API/issues/new).

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
* Redistribution of this software in source or binary forms shall be approved by the repository's owner.
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer. 
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE ZONNY TEAM BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 