Media module for Kohana 3.1+
=============

Installation
-------------

*  Add the module to kohana modules directory

*  *  If you have a git repo, you can add it as an submodule

`git submodule add git://github.com/birkir/media.git modules/media`

`git submodule init`

`git submodule update`

*  You can also just add it to your project

`
git clone git://github.com/birkir/media.git
`

*  Go to APPPATH/bootstrap.php and add this to your Kohana::modules array

`
'media'      => MODPATH.'media',      // Media module
`

*  The module is ready to rock.


Usage
-------------

Store your media files in APPPATH/media folder. So for example if you create a file called APPPATH/media/css/screen.css then you can access the file through http://DOCROOT/media/css/screen.css but the file will be minified and compressed before output to the browser.