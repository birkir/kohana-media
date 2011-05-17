<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Media file processor
 *
 * @package    Kohana/Media
 * @category   Base
 * @author     Birkir Rafn Gudjonsson
 * @copyright  (c) 2010 BRG
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Media {

	// Loaded file
	public $file = NULL;

	// Currently cached file
	public $cache = NULL;

	// File extension
	public $ext = NULL;

	/**
	 * Creates a new Media object.
	 *
	 * @param   array  configuration
	 * @return  Media
	 */
	public static function factory()
	{
		return new Media();
	}

	/**
	 * Creates a new Media object.
	 *
	 * @param   array  configuration
	 * @return  void
	 */
	public function __construct()
	{
		$this->_cache_dir = APPPATH.'cache'.DIRECTORY_SEPARATOR.'media';
	}

	/**
	 * Load a media file
	 *
	 * @param   string  filename
	 * @return  void
	 */
	public function load($filename = NULL)
	{
		// Find the file extension
		$this->ext = pathinfo($filename, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($filename, 0, -(strlen($this->ext) + 1));

		// Check if the file exists
		if ($file = Kohana::find_file('media', $file, $this->ext))
		{
			// Set file and cache variable to source file
			$this->file = $this->cache = $file;

			return $this;
		}

		return FALSE;
	}

	/**
	 * Gzip loaded file
	 *
	 * @return   void
	 */
	public function gzip()
	{
		// Check if file was loaded
		if ($this->file === NULL)
		{
			throw new Kohana_Exception('No file was loaded by the media module.');
		}

		// Find accepted encodings
		$encodings = Request::current()->accept_encoding();

		// Check if browser supports gzip encoding
		if (in_array('gzip', array_keys($encodings)))
		{
			// Set cache filename
			$cache = $this->_cache_dir.DIRECTORY_SEPARATOR.sha1(Request::current()->uri().filemtime($this->cache).'gzip');

			if ( ! $this->_changed($cache) OR isset($_GET['nocache']))
			{
				// Write gzipped contents
				$gf = gzopen($cache, 'w9');
				gzwrite($gf, $this->_read_cache());
				gzclose($gf);

				// Set the new cache path
				$this->cache = $cache;
			}
		}

		return $this;
	}

	/**
	 * Minify wrapper for js and css files
	 *
	 * @return  void
	 */
	public function minify()
	{
		if ($this->ext == 'css')
		{
			
		}
		else if($this->ext == 'js')
		{
			
		}
		
		return $this;
	}

	/**
	 * Render the file contents
	 *
	 * @return   string
	 */
	public function render()
	{
		if ($this->file === NULL)
		{
			throw new Kohana_Exception('No file was loaded by the media module.');
		}

		return self::_read_cache($this->file);
	}

	/**
	 * Check if source file has changed since last generation of cache
	 *
	 * @return void
	 */
	private function _changed($cache = NULL)
	{
		// Check if file does exist and is newer than the cache
		if (file_exists($cache) AND strtotime(date('r', filemtime($this->file))) >= strtotime(date('r', filemtime($cache))))
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Read current cache contents
	 *
	 * @return  string
	 */
	private function _read_cache()
	{
		// Read current file contents
		$fh = fopen($this->cache, 'r');
		$contents = fread($fh, filesize($this->cache));
		fclose($fh);

		return $contents;
	}

	/**
	 * Write contents to cache file
	 *
	 * @param   string  contents
	 * @return  void
	 */
	private function _write_cache($contents = NULL, $filename = NULL)
	{
		// Check if cache directory exists
		if ( ! is_dir($this->_cache_dir))
		{
			// Create the cache directory
			mkdir($this->_cache_dir, 02777);

			// Set permissions (must be manually set to fix umask issues)
			chmod($this->_cache_dir, 02777);
		}

		// Create the cache filename
		$this->cache = $this->_cache_dir.DIRECTORY_SEPARATOR.sha1($filename);

		// Write new file contents
		$fh = fopen($this->cache, 'w');
		fwrite($fh, $contents);
		fclose($fh);
	}
}
