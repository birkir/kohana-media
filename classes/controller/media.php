<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Media extends Controller {

	private $gzip = TRUE;

	public function action_process($filename = NULL)
	{
		$media = Media::factory()
		->load($filename);

		if ($media !== FALSE)
		{
			$media = $media->smushit();

			// Check for gzip flag
			if ($this->gzip === TRUE)
			{
				$media = $media->gzip();
				$this->response->headers('content-encoding', 'gzip');
			}

			// Set response body and headers
			$this->response->check_cache(sha1($this->request->uri()).filemtime($media->file), $this->request);
			$this->response->body($media->render());
			$this->response->headers('content-type',  File::mime_by_ext($media->ext));
			$this->response->headers('last-modified', date('r', filemtime($media->file)));
		}
		else
		{
			$this->response->status(404);
		}
	}
}
