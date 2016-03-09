<?php namespace SystemCore\Themes;

use Illuminate\Support\Facades\Facade;

class ThemesFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	 protected static function getFacadeAccessor() { return 'themes'; }

}
