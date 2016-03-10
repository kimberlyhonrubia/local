<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::group( array('prefix' => '/','namespace' => 'Frontend' ), function() {

	Route::group( array('prefix' => 'map', 'namespace' => 'Map' ), function() {

        Route::controller( '/', 'MapController',
            [
                'getIndex' => FRONTEND_MAP_INDEX
            ]
        );

    });

    Route::controller( '/', 'FrontendController',
        [
            'getIndex' => FRONTEND_INDEX
        ]
    );
});