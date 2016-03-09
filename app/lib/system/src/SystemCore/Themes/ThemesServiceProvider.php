<?php namespace SystemCore\Themes;

use SystemCore\Themes\Themes;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ThemesServiceProvider extends ServiceProvider {

	/**
	* Shortcut for adding an Alias in app/config/app.php
	*
	* @return void
	*/
	public function boot()
	{
        $loader = AliasLoader::getInstance();
        $loader->alias('Themes', 'SystemCore\Themes\ThemesFacade');
	}

	/**
	* Register the view finder implementation.
	*
	* @return void
	*/
	public function register()
	{

		$this->app['themes'] = $this->app->share(function($app)
		{
			$config = \Config::get('themes');
			return new Themes($config);
		});

	}

}
