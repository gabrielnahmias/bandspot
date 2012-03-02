BandSpot
=============

BandSpot is a website engine that started out as a project for a local band, Elemovements.  It integrates heavily with Facebook and ReverbNation.  It also includes a mobile version of the site for iPhone users.  It has not yet been normalized (removed of all project-specific images and configuration variables, etc.) however it would not take much to customize the site accordingly.

[Check out the current incarnation live!](http://www.elemovements.com)

What does it do?
-----------

* Provides several widgets to keep your users up-to-date about you.
* Allows iPhone users to have a similar experience to some of the Flash-based widgets through RSS parsing and mobile downloads (currently configured to read through a folder formatted in a specific way).
* Minifies all scripts and stylesheets and appropriately sorts out the ones necessary for the certain platform (mobile or not).
* Is very concurrent and includes a Facebook login feature that displays their avatar and name.
* Has default ability to be very extensible and fun to enhance.

How to use it
-----------

In order to use BandSpot and tailor it to your own project, it is suggested to adhere to the dimensions and other boundaries of the engine.  Replace all images and make sure to heavily modify "config.php" in the inc directory.  You will need to provide IDs that can be obtained by going to the widgets page of the proper ReverbNation page and extracting certain variables from the generated code.  You will also need your Facebook application ID, secret, etc.

Browser support
-----------

* Google Chrome
* Mozilla Firefox 3+
* Apple Safari 4+
* Opera 10+
* Internet Explorer 6+

License
-----------

Public domain

Acknowledgements
------------

BandSpot is a project by [Gabriel Nahmias](http://github.com/terrasoftlabs), co-founder of Terrasoft.