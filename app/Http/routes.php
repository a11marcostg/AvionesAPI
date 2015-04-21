<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Versionado de la API 
// Las rutas quedarán alfo como /api/v1.0/rutas existentes....
Route::group(array('prefix'=>'api/v1.0'),function(){




// Creamos la rutas nuevas que tendrán en cuenta los controllers programados en Controllers
//Ruta /fabricantes/....
	Route::resource('fabricantes','FabricanteController',['except'=>['create','edit']]);


// Recurso anidado /fabricantes/xx/aviones
	Route::resource('fabricantes.aviones','FabricanteAvionController',['except'=>['create','edit','show']]);


//Ruta /aviones/.... El resto de metodos los gestiona FabricanteAvionController
	Route::resource('aviones','AvionController',['only'=>['index','show']]);



	Route::get('/', function(){

		return "Bienvenido a API RESTful de Aviones.";

	});

});

Route::get('/', function(){

		return "<a href='http://www.dominio.local/api/v1.0'>Acceda a la version 1.0 de la API RESTful de Aviones</a>";

});

/*
Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/