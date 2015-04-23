<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Fabricante;
use App\Avion;

use Response;

// Activamos el uso de las funciones caché
use Illuminate\Support\Facades\Cache;

class FabricanteAvionController extends Controller {

	public function __construct(){

		$this->middleware('auth.basic',['only'=>['store','update','destroy']]);

	}
	public function index($idFabricante)
	{
		// Mostramos todos los aviones de un fabricante

		$fabricante=Fabricante::find($idFabricante);

		if (!$fabricante) {
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}

		$avionesFabricante=Cache::remember('cacheavionesFabricante',15/60,function()use($fabricante){

			return $fabricante->aviones()->get();
		});

		return response()->json(['status'=>'ok','data'=>$avionesFabricante],200);
		//return response()->json(['status'=>'ok','data'=>$fabricante->aviones()->get()],200);


	}

	
	public function store($idFabricante,Request $request)
	{
		// Comprobamos si existe este fabricante
		$fabricante=Fabricante::find($idFabricante);

		if (!$fabricante) {
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}

		//Comprobamos si recibimos todos los campos para un nuevo avion
		if (!$request->input('modelo')||!$request->input('longitud')||!$request->input('capacidad')||!$request->input('velocidad')||!$request->input('alcance')) {
			// No estamos recibiendo los campos devolvemos error
			return response()->json(['errors'=>Array(['code'=>422,'message'=>'Faltan datos necesarios para procesar el alta de avion'])],422);
		}


		$nuevoAvion=$fabricante->aviones()->create($request->all());

		$respuesta= Response::make(json_encode(['data'=>$nuevoAvion]),201)->header('Location','http://www.dominio.local/aviones/'.$nuevoAvion->serie)->header('Content-Type','application/json');
		return $respuesta;

	}

		
	public function update($idFabricante,$idAvion, Request $request)
	{
		// Comprobamos si el fabricante existe
		$fabricante=Fabricante::find($idFabricante);

		if (!$fabricante) {
			return response()->json(['errors'=>['code'=>404,'message'=>'No se encuetra un fabricante con ese código.']],404);
		}

		//Comprobamos si el avion que buscamos es el de ese fabricante

		$avion = $fabricante->aviones()->find($idAvion);

		if (!$avion) {
			return response()->json(['errors'=>['code'=>404,'message'=>'No se encuetra un avion con ese código asociado al fabricante.']],404);
		}

		//Listado de campos recibidos del formulario de actualizacion
		$modelo=$request->input('modelo');
		$longitud=$request->input('longitud');
		$capacidad=$request->input('capacidad');
		$velocidad=$request->input('velocidad');
		$alcance=$request->input('alcance');

		// Comprobamos el metodo si es PATCH O PUT

		if ($request->method()=='PATCH') {
			$bandera=false;
			//Comprobamos campo a campo si hemos recibido datos
			if ($modelo) {
				$avion->modelo=$modelo;
				$bandera=true;
			}
			if ($longitud) {
				$avion->longitud=$longitud;
				$bandera=true;
			}
			if ($capacidad) {
				$avion->capacidad=$capacidad;
				$bandera=true;
			}
			if ($velocidad) {
				$avion->velocidad=$velocidad;
				$bandera=true;
			}
			if ($alcance) {
				$avion->alcance=$alcance;
				$bandera=true;
			}
			if ($bandera) {
				//actualizamos fabricante
				$avion->save();
				//Devolvemos un codigo 200 (ha habido modificaciones)
				return response()->json(['status'=>'ok','data'=>$avion],200);
			}else{

				// Devolvemos codigo 304 Not Modified
				return response()->json(['errors'=>['code'=>304,'message'=>'No se ha modificado ningún dato del avion']],304);
			}


		}/// Acabamos la comprobacion de si era PATCH

		// Metodo PUT actualizamos todos los campos
		if (!$modelo||!$longitud||!$capacidad||!$velocidad||!$alcance) {
		// Se devuelve error codigo 422 Unprocssable Entity

			return response()->json(['errors'=>['code'=>422,'message'=>'Faltan valores para completar el procesamiento.']],422);

		}

		// Actualizamos el modelo avion
		$avion->modelo=$modelo;		
		$avion->longitud=$longitud;
		$avion->capacidad=$capacidad;
		$avion->velocidad=$velocidad;
		$avion->alcance=$alcance;
	

		$avion->save();
		return response()->json(['status'=>'ok','data'=>$avion],200);



	}

	
	public function destroy($idFabricante,$idAvion)
	{
		$fabricante=Fabricante::find($idFabricante);

		//Chequeamos si encontro un fabricante
		if (!$fabricante) {

			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese codigo'])],404);
		}


		$avion=$fabricante->aviones()->find($idAvion);

		//Chequeamos si encontro un fabricante
		if (!$avion) {

			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion asociado a ese fabricante'])],404);
		}

		

		$avion->delete();
		
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el avion'],204);
	}

}
