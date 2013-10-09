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

	protected static $_combine_buffer = array();

	/**
	 * Add script file to combine buffer on production.
	 *
	 * @param  string
	 * @param  array
	 * @return mixed
	 */
	public static function script($file, $combine = TRUE)
	{
		// add to combine buffer
		if ($combine === TRUE)
		{
			Media::$_combine_buffer[] = array(
				'file' => $file,
				'type' => 'script',
				'rel'  => 'script'
			);
		}

		// render if environment is greater than staging
		if (Kohana::$environment > Kohana::STAGING OR $combine === FALSE)
		{
			return HTML::script((strpos($file, '://') === FALSE ? 'media/' : NULL).$file);
		}

		return NULL;
	}

	public static function style($file, $combine = TRUE, $rel = 'stylesheet')
	{
		// add to combine buffer
		if ($combine === TRUE)
		{
			Media::$_combine_buffer[] = array(
				'file' => $file,
				'type' => 'style',
				'rel'  => $rel
			);
		}

		// render if environment is greater than staging
		if (Kohana::$environment > Kohana::STAGING OR $combine === FALSE)
		{
			return HTML::style((strpos($file, '://') === FALSE ? 'media/' : NULL).$file, ['rel' => $rel]);
		}

		return NULL;
	}

	public static function compile($file = NULL)
	{
		$filename = APPPATH.'media/'.$file;

		$fh = fopen($filename, 'r');
		$contents = fread($fh, filesize($filename));
		fclose($fh);

		return $contents;
	}

	public static function combine(array $files, $output)
	{
		// generate cache key
		$cache = hash_hmac('sha1', 'cache-'.$output.'-'.implode('-', $files), 'media-key').'.km';

		// get output type
		$type = ($output === 'script' ? 'js' : 'css');

		if ( ! file_exists(APPPATH.'cache/media/'.$cache) OR Request::current()->query('media') === 'nocache')
		{
			$raw = '';

			foreach ($files as $file)
			{
				$raw .= Media::compile($file).PHP_EOL;
			}

			file_put_contents(APPPATH.'cache/media/'.$cache, $raw);
		}

		return 'media/'.$cache.'.'.$type;
	}

	/**
	 * Render media files combined or not
	 *
	 * @return mixed
	 */
	public static function render()
	{
		$types = array('style' => array(), 'script' => array());
		$render = array();

		// loop through combine buffer
		foreach (Media::$_combine_buffer as $file)
		{
			// get type and rel
			$type = Arr::get($file, 'type', NULL);
			$rel  = Arr::get($file, 'rel',  NULL);

			// create sub-rel array
			if ( ! isset($types[$type][$rel]))
			{
				$types[$type][$rel] = array();
			}

			// add file to array
			$types[$type][$rel][] = Arr::get($file, 'file');
		}

		// loop through type of files
		foreach ($types as $type => $items)
		{
			// loop through rel types
			foreach ($items as $rel => $group)
			{
				// generate combined file
				$filename = Media::combine($group, $type);

				// add to render array
				$render[] = ($type === 'script' ? HTML::script($filename) : HTML::style($filename, array('rel' => $rel)));
			}
		}

		// reset combine buffer
		Media::$_combine_buffer = array();

		// check for environment and output render
		if (Kohana::$environment <= Kohana::STAGING)
		{
			return implode("\n", $render);
		}
	}

}  // End Kohana Media