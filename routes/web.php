<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    
    // Route to create a subject (GET api/)
    $router->get('/', ['uses' => 'ApiController@index']);
    
    $router->group(['prefix' => 'subject'], function () use ($router) {

        // Route to create a subject (POST api/subject)
        $router->post('/', ['uses' => 'ApiController@storeSubject']);

        // Route to assign a project to a subject (POST api/subject/{subjectID}/project)
        $router->post('/{subjectID}/project', ['uses' => 'ApiController@assignProjectToSubject']);
        
    });
});
