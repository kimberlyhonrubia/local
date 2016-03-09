<?php namespace Frontend\Map;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

use Controller;

class MapController extends Controller {


	public function __construct() {

	}

	public function getIndex()
    {
        return View::make('frontend.map.index');
    }

	public function index() {

	}

}