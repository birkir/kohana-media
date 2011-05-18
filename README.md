Media module for Kohana 3.1+
=============

Installation
-------------

1.  Add the module to kohana modules directory

    a)  If you have a git repo, you can add it as an submodule

    `git submodule add git://github.com/birkir/media.git modules/media`

    `git submodule init`

    `git submodule update`

    b)  You can also just add it to your project

    `git clone git://github.com/birkir/media.git`

2.  Go to APPPATH/bootstrap.php and add this to your Kohana::modules array

    `'media'      => MODPATH.'media',      // Media module`

3.  The module is ready to rock.


Usage
-------------

Store your media files in APPPATH/media folder. So for example if you create a file called APPPATH/media/css/screen.css then you can access the file through http://DOCROOT/media/css/screen.css but the file will be minified and compressed before output to the browser.

Same story for image files, but they will be compressed by smush.it service (if your server is accessible from the internet) and then gzipped.


Techincal features
-------------
<table>
<thead><tr><th>Ext</th><th>Minify</th><th>Smush.it</th><th>Gzip*</th><th>Sass</th><th>Less</th><th>Haml</th><th>Combining</th></tr></thead>
<tbody>
<tr><td align=right>css</td><td align=center>X</td><td></td><td align=center>X</td></tr>
<tr><td align=right>js</td><td align=center>X</td><td></td><td align=center>X</td></tr>
<tr><td align=right>png</td><td></td><td align=center>X</td><td align=center>X</td></tr>
<tr><td align=right>jpg</td><td></td><td align=center>X</td><td align=center>X</td></tr>
<tr><td align=right>gif</td><td></td><td align=center>X</td><td align=center>X</td></tr>
</table>
* Only for browser accepting gzip. (IE4+, Netscape6+, Opera5+, Lynx, Firefox, Safari, Opera, etc.)