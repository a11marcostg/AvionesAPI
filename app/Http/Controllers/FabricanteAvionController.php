<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Fabricante;
use App\Avion;

use Response;
class FabricanteAvionController extends Controller {

	
	public function index($idFabricante)
	{
		// Mostramos todos los aviones de un fabricante
		$fabricante=Fabricante::find($idFabricante);
		if (!$fabricante) {
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}
		return response()->json(['status'=>'ok','data'=>$fabricante->aviones()->get()],200);
		// return response()->json(['status'='ok','data'=>$fabricante->aviones->get()],200);


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

		
	public function update($id)
	{
		//
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
