<?php namespace SystemCore\Themes;

/**
 * Themes
 *
 * Handle Theming for Website Design
 *
 * @package Library Themes
 * @author  Rynelle Coronacion <rynellecoronacion@gmail.com>
 * @version v1
 */

use Illuminate\Support\Facades\View;

class Themes {

	/**
	 * Location of themes directory
	 * @var string
	 */
	protected $themes_location;

	/**
	 * Theme Namespace
	 * @var string
	 */
	protected $namespace;

	/**
	 * Name of default theme
	 * @var string
	 */
	protected $default_theme;

	/**
	 * Name of current theme
	 * @var string
	 */
	protected $current_theme;

	/**
	 * Theme Location
	 * @var string
	 */
	protected $theme_path;

	/**
	 * Theme View Directory
	 * @var string
	 */
	protected $views;

	/**
	 * Directory Seperator
	 * Linux/Mac "/"
	 * Windows   "\"
	 *
	 * @var string
	 */
	protected $seperator;

	/**
     * Repository config.
     *
     * @var object
     */
    protected $config;

	/**
	 * Create a new view instance.
	 *
	 * @param  object  $config
	 * @return void
	 */
	public function __construct(array $config)
	{
		$this->config 	  = $config;
		$this->seperator  = DIRECTORY_SEPARATOR;

		$this->initializr();
	}

	/**
	 * Inializer of all variables
	 *
	 * @param string $theme
	 * @return void
	 */
	public function initializr()
	{
		$this->setThemesNamespace(@$this->config['namespace']);
		$this->setThemesLocation(@$this->config['path']);
		$this->setDefaultTheme(@$this->config['default']);
		$this->setCurrentTheme(@$this->config['theme']);
		$this->setViewLocation(@$this->config['views']);

		$this->getThemePath();

	}

	/**
	 * Set the theme location
	 * /app/themes
	 *
	 * @param string $theme
	 * @return void
	 */
	public function setThemesNamespace($namespace)
	{
		$this->namespace = $namespace;
	}

	/**
	 * Set the theme location
	 * /app/themes
	 *
	 * @param string $theme
	 * @return void
	 */
	public function setThemesLocation($path)
	{
		$this->themes_location = app_path();
		if ( $path )
			$this->themes_location = rtrim($path, $this->seperator);
	}

	/**
	 * Set the default path
	 *
	 * @param string $theme
	 * @return void
	 */
	public function setDefaultTheme($theme)
	{
		$this->default_theme = null;
		if ( $theme )
			$this->default_theme = trim($theme, $this->seperator);
	}

	/**
	 * Set the theme path
	 *
	 * @param string $theme
	 * @return void
	 */
	public function setCurrentTheme($theme)
	{
		$this->current_theme = null;
		if ( $theme )
			$this->current_theme = trim($theme, $this->seperator);
	}

	/**
	 * Set the theme path
	 *
	 * @param string $theme
	 * @return void
	 */
	public function setViewLocation($folder)
	{
		$this->views = '';
		if ( $folder )
			$this->views = $this->seperator . trim($folder, $this->seperator);
	}

	/**
	 * Get the path to the theme in use.
	 *
	 * If theme is not found
	 * It will automatically go to views
	 *
	 * @return string Path to the default theme
	 */
	public function getThemePath()
	{
		$default = $this->themes_location . $this->seperator . $this->default_theme . $this->views;
		$theme   = $this->themes_location . $this->seperator . $this->current_theme . $this->views;
		if ( file_exists($theme) && $this->current_theme )
		{
		    $this->theme_path = $theme;
		}
		elseif ( file_exists($default) && $this->default_theme )
		{
			$this->theme_path = $default;
		}
		else {
			$this->theme_path = app_path('views');
		}
	}

	/**
	 * addLocation
	 * set the location
	 * View::addLocation(app_path().'/themes');
	 *
	 * @return string Path to the current theme
	 */
	protected function addLocation()
	{
		View::addLocation($this->theme_path);
	}

	/**
	 * addNamespace
	 * set the view namespace
	 * View::addNamespace('theme', app_path().'/themes');
	 *
	 * @return string
	 */
	protected function addNamespace($namespace)
	{
		View::addNamespace($namespace, $this->theme_path);
	}

	/**
	* Register the view finder implementation.
	*
	* @return void
	*/
	public function make($view, $data = array(), $mergeData = array())
	{

		$namespace = $this->namespace;

		$this->addLocation();
		$this->addNamespace($namespace);

		return View::make($namespace . '::' . $view, $data, $mergeData);
	}

}
