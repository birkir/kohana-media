Media module for Kohana 3.1+
=============

Installation
-------------

1. Add the module to kohana modules directory

1a. If you have a git repo, you can add it as an submodule

`git submodule add git://github.com/birkir/media.git modules/media`

`git submodule init`

`git submodule update`

1b. You can also just add it to your project

`
git clone git://github.com/birkir/media.git
`

2. Go to APPPATH/bootstrap.php and add this to your Kohana::modules array

`
'media'      => MODPATH.'media',      // Media module
`