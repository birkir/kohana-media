<?php defined('SYSPATH') or die('No direct script access.');

// Set the media route
Route::set('media', 'media(/<filename>)', array('filename' => '.+'))
	->defaults(array(
		'controller' => 'media',
		'action' => 'process'
	));