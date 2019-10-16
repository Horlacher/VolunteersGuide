# The Volunteer's Guide

We use Github to maintain our Code. If you want to use this WordPress Plugin, install it from the plugin repository in the WordPress backend of your website.

## Description

Integrates features of PLATO for [The Alliance](http://www.alliance-network.eu/) into a Wordpress page. This plugin is only an integration for PLATO, but not related  

## Requirements

* PHP >= 7.0
* MySQL >= 5.6.5
* WordPress >= 4.9

## Installation

1. Put `VolunteersGuide` to the Wordpress Plugin directory (/wp-content/plugins/)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin in WordPress admin
4. Place shortcodes on pages

## Dev

### Required packages

Please install the following build dependencies:
* [Python](https://www.python.org/)
* [Ruby](https://www.ruby-lang.org/)
* [node.js](https://nodejs.org/) (tested with v8.10.0)
* [gettext](https://packages.ubuntu.com/bionic/gettext)

Download required packages:

```
npm install
npm install grunt-cli sass -g
```

### Generate assets

Generate minified JS and CSS files and compile .mo translation files:

```
grunt buildAssets
```

### Build zip

Create a ZIP which can be uploaded to a remote WordPress installation:

```
grunt buildZip
```

### Running the tests

There are no automated tests available yet, feel free to implement them and send a pull-request.

## Changelog

See README.txt

## Authors

See also the list of [contributors](https://github.com/horlacher/plato-integration/contributors) who participated in this project.

## License

This project is licensed under the GPLv3 License - see the [LICENSE.txt](LICENSE.txt) file for details.