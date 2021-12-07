# README

<img src="/img/logo/music_logotype_horizontal.svg" alt="logotype" width="60%"/>

## Overview

Stripped down version of the official ownCloud music player (v0.9.4) to only include the music player embedded into the files view:
![files view](https://user-images.githubusercontent.com/8565946/43827500-9f45beb6-9b02-11e8-8884-39ed2f0daa54.png)

## Supported formats

* FLAC (`audio/flac`)
* MP3 (`audio/mpeg`)
* Vorbis in OGG container (`audio/ogg`)
* Opus in OGG container (`audio/ogg` or `audio/opus`)
* WAV (`audio/wav`)
* M4A (`audio/mp4`)
* M4B (`audio/m4b`)

_Note: The audio formats supported vary depending on the browser. Chrome and Firefox should be able to play all the formats listed above. All browsers should be able to play at least the MP3 files._

_Note: The app might be unable to play some particular files on some browsers._


### Detail

The Music app utilizes 2 backend players: Aurora.js and SoundManager2.

SoundManager2 utilizes the browser's built-in codecs. Aurora.js, on the other hand, uses Javascript and HTML5 Audio API to decode and play music and doesn't require codecs from browser. The Music app ships with FLAC and MP3 plugins for Aurora.js. Aurora.js does not work on any version of Internet Explorer and fails to play some MP3 files on other browsers, too.

The Music app uses SoundManager2 if the browser has a suitable codec available for the file in question and Aurora.js otherwise. In practice, Firefox and Chrome use SoundManager2 for all supported audio formats. Chromium uses Aurora.js for MP3 and FLAC and doesn't play any other formats. Edge uses Aurora.js for FLAC and SoundManager2 for everything else (ogg and m4b not supported). Internet Explorer plays MP3 with SoundManager2 and doesn's play any other formats.

### Installation

The Music app can be installed using the App Management in ownCloud. Instructions can be found [here](https://doc.owncloud.org/server/8.1/admin_manual/installation/apps_management_installation.html).

After installation, you may want to select a specific sub-folder containing your music files through the settings of the application. This can be useful to prevent unwanted audio files to be included in the music library.

## Development

### L10n hints

Sometimes translatable strings aren't detected. Try to move the `translate` attribute
more to the beginning of the HTML element.

### Build frontend bundle

All the frontend javascript sources of the Music app, excluding the vendor libraries, are bundled into a single file for deployment. This bundle file is js/public/app.js. Similarly, all the style files of the Music app are budnled into css/public/app.css. Generating these bundles requires `make` and `npm` utilities, and happens by running:

	cd build
	make

To automatically regenerate the bundles whenever the source .js/.css files change, use

    make watch

### Build appstore package

	git archive HEAD --format=zip --prefix=music/ > build/music.zip

### Install test dependencies

	composer install

### Run tests

PHP unit tests

	vendor/bin/phpunit --coverage-html coverage-html-unit --configuration tests/php/unit/phpunit.xml tests/php/unit

PHP integration tests

	cd ../..          # owncloud core
	./occ maintenance:install --admin-user admin --admin-pass admin --database sqlite
	./occ app:enable music
	cd apps/music
	vendor/bin/phpunit --coverage-html coverage-html-integration --configuration tests/php/integration/phpunit.xml tests/php/integration

Behat acceptance tests

	cd tests
	cp behat.yml.dist behat.yml
	# add credentials for Ampache API to behat.yml
	../vendor/bin/behat

For the acceptance tests, you need to upload all the tracks from the following zip file: https://github.com/paulijar/music/files/2364060/testcontent.zip

### 3rdparty libs

update JavaScript libraries

	cd js
	bower update
