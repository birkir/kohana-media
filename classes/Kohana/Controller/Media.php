<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Media class
 *
 * @package    Kohana/Media
 * @category   Controllers
 * @author     Birkir Gudjonsson
 * @copyright  (c) 2013
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Controller_Media extends Controller {

	/**
	 * Process the file
	 *
	 * @param   string  filename
	 * @return  void
	 */
	public function action_media()
	{
		$filename = $this->request->param('file');
		$info = pathinfo($filename);
		$root = APPPATH.'media/';

		// we have combined file here
		if (strlen($info['filename']) === 43 AND substr($info['filename'], -3) === '.km')
		{
			$root = APPPATH.'cache/media/';
		}

		$file = pathinfo($filename, PATHINFO_FILENAME);
		$filename = $root.$file;

		if ( ! file_exists($filename))
			throw HTTP_Exception::factory(404, 'Not found');

		// gzip contents
		if ($this->request->accept_encoding('gzip'))
		{
			$gzip = APPPATH.'cache/media/'.$file.'.gz';

			if ( ! file_exists($gzip))
			{
				$gf = gzopen($gzip, 'w9');
				gzwrite($gf, file_get_contents($filename));
				gzclose($gf);
			}

			$this->response->headers('content-encoding', 'gzip');
			$filename = $gzip;
		}

		// Set response body and headers
		$this->check_cache(sha1($this->request->uri()).filemtime($filename));
		$this->response->body(file_get_contents($filename));
		$this->response->headers('content-type', File::mime_by_ext($info['extension']));
		$this->response->headers('last-modified', date('r', filemtime($filename)));
	}

} // End Controller Media
