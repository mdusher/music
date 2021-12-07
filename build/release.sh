#!/bin/bash
#
# ownCloud Music
#
# @author Pauli Järvinen
# @copyright 2021 Pauli Järvinen <pauli.jarvinen@gmail.com>
#

# Create the base package from the files stored in git
cd ..
git archive HEAD --format=zip --prefix=music/ > music.zip

# Add the generated webpack files to the previously created package
cd ..
zip -g music/music.zip music/dist/*.js
zip -g music/music.zip music/dist/*.css
zip -g music/music.zip music/dist/*.json
zip -g music/music.zip music/dist/img/**

# Remove the front-end source files from the package as those are not needed to run the app
zip -d music/music.zip "music/build/*"
zip -d music/music.zip "music/css/*.css"
zip -d music/music.zip "music/css/*/"
zip -d music/music.zip "music/img/*.svg"
zip -d music/music.zip "music/img/*/*"
zip -d music/music.zip "music/js/*.js*"
zip -d music/music.zip "music/js/*/*"

# Remove also files related to testing and code analysis
zip -d music/music.zip "music/composer.*"
zip -d music/music.zip "music/phpstan.neon"
