<?php defined('SYSPATH') or die('No direct script access.');

class Request extends Kohana_Request {

	/**
	 * @var  string  the language of the main request
	 */
	public static $lang;

	/**
	 * Extension of the main request singleton instance. If none given, the URI will
	 * be automatically detected. If the URI contains no language segment, the user
	 * will be redirected to the same URI with the default language prepended.
	 * If the URI does contain a language segment, I18n and locale will be set.
	 * Also, a cookie with the current language will be set. Finally, the language
	 * segment is chopped off the URI and normal request processing continues.
	 *
	 * @param   string   URI of the request
	 * @return  Request
	 * @uses    Request::detect_uri
	 */
	public static function factory($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array())
	{
		// All supported languages
		$langs = (array) Kohana::$config->load('lang');

		if ($uri === TRUE)
		{
			// We need the current URI
			$uri = Request::detect_uri();
		}
		
		Request::$lang = Lang::find_default();
		
		// Store target language in I18n
		I18n::$lang = $langs[Request::$lang]['i18n_code'];

		// Set locale
		setlocale(LC_ALL, $langs[Request::$lang]['locale']);

		// Update language cookie if needed
		if (Cookie::get(Lang::$cookie) !== Request::$lang)
		{
			Cookie::set(Lang::$cookie, Request::$lang);
		}

		// Continue normal request processing
		return parent::factory($uri, $cache, $injected_routes);
	}

}