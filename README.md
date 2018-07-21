# [S'mores](https://github.com/findsomewinmore/smores)

S'mores is a WordPress starter theme based on [HTML5 Boilerplate](http://html5boilerplate.com/) & [Foundation](http://foundation.zurb.com) that will help the FiWi Creative Team (and others) create better Wordpress themes.

S'mores is an on-going project and an ever evolving repository. As our systems and processees change, so will S'mores.

## Features

* [Gulp](http://gruntjs.com) for compiling Sass to CSS, checking for JS errors, live reloading, concatenating and minifying files, optimizing PNGs and JPEGs, versioning assets, and generating lean Modernizr builds
* [NPM](http://npmjs.com/) for front-end package management
* [HTML5 Boilerplate](http://html5boilerplate.com/)
  * We use a little older version of HTML5 Boilerplate. We still like Paul Irish's IE version detection. Hey, in IE's case, browser detection _is_ feature detection. 
  * The latest [jQuery](http://jquery.com/) via Google CDN, with a local fallback (Bower)
  * The latest [Modernizr](http://modernizr.com/) build for feature detection, with lean builds with Grunt
  * An optimized Google Analytics snippet
* [Foundation 6](http://foundation.zurb.com)
* [Babel](http://babeljs.io)
* [Autoprefixer](https://github.com/postcss/autoprefixer)
* Organized file and asset structure

## Installation

1. Clone the git repo - `git clone https://github.com/findsomewinmore/smores.git`
2. Rename the directory to the name of your theme or website.
3. Remove the .git directory (This will preven you from commiting your personal project to the S'mores repositiory)
4. Initialize a new Git repo with `git init`

## Development

S'mores uses [Grunt](http://gulpjs.com/) for compiling Sass to CSS, checking for JS errors, concatenating and minifying files, optimizing PNGs and JPEGs, versioning assets, and generating lean Modernizr builds.

### Branches

There are currently two branches. Master and Wordpress. Both contain the same dependency files and assets folder. 
1. `master` contains a single starter HTML files, for wireframes/templates
2. `wordpress` contains the required Wordpress theme files

To switch branches type: `git checkout <branch name>` from the command line.

# Credits

##Findsome & Winmore

S'mores is maintained and funded by [Findsome & Winmore](http://findsomewinmore.com). This open source project is brought to you by dozens of other open source projects. We like to give credit to those that we have borrowed from. If you find a code snippet we forgot to credit, please submit a pull request for a README.md update.

# License

S'mores is Copyright © 2014 Findsome & Winmore. It is free software, and may be redistributed under the terms specified in the [LICENSE](LICENSE.md) file.
