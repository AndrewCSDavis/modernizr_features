# Modernizr Features

- Version: 1.2
- Author: Andrew Davis
- Build Date: 2014-08-18
- Requirements: Symphony 2.2, 2.3, 2.4

## Installation

- Upload the 'modernizr_features' folder to your Symphony 'extensions' folder.
- Enable it by selecting "Modernizr Features", choose Enable from the with-selected menu, then click Apply.

## Usage

Bundles a datasource that outputs the users browser, version, platform, platform version, if it's a mobile device, a robot or using chromeframe, detects orientation of device as well as screen size also gives a range of device capabilties provided by Modernizr. Easy!

There is a setting on the preferences page to include geolocationing based on the IP address using Geoplugin. This is disabled by default as some people don't want the page load delay associated with geolocationing. Enabling this adds a location node to the bundled datasource, as well as lat/long and country values into the param pool.

## Uses

In no way is this extension a replacement for ie conditionals.

**Do not use this extension to solely provide content/styles etc to a couple of browsers only. There are dozens of browsers out there in use, so you will only harm your website by isolating it. You should create your site as normal and use this extension to add content specific to certain technologies, like tablets, mobiles or html5 compatible/non-compatible browsers.**

**Browsers are always changing. Some browsers allow the user to alter the way the browser identifies itself, or to not identify itself at all. Some firewalls block the sending of the browser identification, so no browser detection scheme is entirely successful.**