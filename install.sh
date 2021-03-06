#!/bin/sh

# Author : Randall Box

echo "Installing..."
mkdir web/images
mkdir web/css
mkdir web/libraries
mkdir web/libraries/bootstrap
mkdir web/libraries/jquery
cp config.example.php config.inc.php
cp vendor/components/jquery/jquery.min.js web/libraries/jquery/
cp vendor/twbs/bootstrap-sass/assets/javascripts/bootstrap.min.js web/libraries/bootstrap/
sass scss/styles.scss > web/css/styles.css
echo "Done. Configure database in config.inc.php\n"
