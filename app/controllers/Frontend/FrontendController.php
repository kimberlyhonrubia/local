<?php namespace Frontend;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

use Controller;

class FrontendController extends Controller {


	public function __construct() {

	}

	public function getIndex()
    {
        return View::make('frontend.index');
    }


}