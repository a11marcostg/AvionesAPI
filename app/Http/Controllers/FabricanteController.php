<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Fabricante;
use Response;

// Activamos el uso de las funciones caché
use Illuminate\Support\Facades\Cache;

class FabricanteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// return "En el index del fabricante";
		// Devolvemos un json con todos los frabricantes
		// Caché se actualizará con nuevos datos cada 15 segundos
		// cachefabricantes es la clave con la que se almacenaran los registros obtenidos de Fabricante::all();
		$fabricantes=Cache::remember('cachefabricantes',15/60,function(){

			return Fabricante::all();
		});
		//return Fabricante::all(); ----esto e unha forma

		//Para devolver un json con codigo de respuesta HTTP sin caché
		//return response()->json(['status'=>'ok','data'=>Fabricante::all()],200);

		//Devolvemos json usando caché
		return response()->json(['status'=>'ok','data'=>$fabricantes],200);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	//No se utiliza este metodo porque se usaria para mostrar un formulario
	//de creacion de Fabricantes. Y una API REST no hace eso
	/*public function create()
	{
		//
	}*/

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// Metodo llamado al hacer un POST.
		// Comprobamos que recibimos todos los campos.

		if (!$request->input('nombre')||!$request->input('direccion')||!$request->input('telefono')) {
			// No estamos recibiendo los campos devolvemos error
			return response()->json(['errors'=>Array(['code'=>422,'message'=>'Faltan datos necesarios para procesar el alta'])],422);
		}

		// Insertamos los datos en la tabla. 

		$nuevofabricante=Fabricante::create($request->all());

		//Devolvemos la respuesta 201 de creado mas los datos del nuevo fabricante mas una cabecera de location + cabecera json
		$respuesta= Response::make(json_encode(['data'=>$nuevofabricante]),201)->header('Location','http://www.dominio.local/fabricantes/'.$nuevofabricante->id)->header('Content-Type','application/json');
		return $respuesta;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$fabricante=Fabricante::find($id);

		//Chequeamos si encontro un fabricante
		if (!$fabricante) {

			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$fabricante],200);
		

		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*public function edit($id)
	{
		//
	}*/

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//Vamos a actualizar un fabricante
		// Comprobamos se el fabricante existe. En otro caso devolvemos error.

		$fabricante=Fabricante::find($id);

		if (!$fabricante) {
			return response()->json(['errors'=>['code'=>404,'message'=>'No se encuetra un fabricante con ese código.']],404);
		}

		// Almacenamos en variables para facilitar el uso, los campos recibidos
		$nombre=$request->input('nombre');
		$direccion=$request->input('direccion');
		$telefono=$request->input('telefono');

		//Comprobamos si recibimos peticion patch(parcial) o put(total)

		if ($request->method()=='PATCH') {
			// Actualizacion parcial de datos
			$bandera=false;
			if ($nombre) {
				$fabricante->nombre=$nombre;
				$bandera=true;
			}
			if ($direccion) {
				$fabricante->direccion=$direccion;
				$bandera=true;
			}
			if ($telefono) {
				$fabricante->telefono=$telefono;
				$bandera=true;
			}

			if ($bandera) {
				//actualizamos fabricante
				$fabricante->save();
				//Devolvemos un codigo 200 (ha habido modificaciones)
				return response()->json(['status'=>'ok','data'=>$fabricante],200);
			}else{

				// Devolvemos codigo 304 Not Modified
				return response()->json(['errors'=>['code'=>304,'message'=>'No se ha modificado ningún dato del fabricante']],304);
			}
		
		}/// Acabamos la comprobacion de si era PATCH

		// Metodo PUT actualizamos todos los campos
		if (!$nombre||!$direccion||!$telefono) {
		// Se devuelve error codigo 422 Unprocssable Entity

			return response()->json(['errors'=>['code'=>422,'message'=>'Faltan valores para completar el procesamiento.']],422);

		}
		$fabricante->nombre=$nombre;
		$fabricante->direccion=$direccion;
		$fabricante->telefono=$telefono;

		$fabricante->save();
		return response()->json(['status'=>'ok','data'=>$fabricante],200);


	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$fabricante=Fabricante::find($id);

		//Chequeamos si encontro un fabricante
		if (!$fabricante) {

			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}

		//Antes de borrar el fabricante comprobamos si tiene aviones

		$aviones=$fabricante->aviones;
		//$aviones=$fabricante->aviones()->get();

		if (sizeof($aviones)>0) {
			
			//Si quisieramos borrar todos los aviones del fabricante seria
			//$fabricante->aviones->delete();

			//Devolvemos un codigo 409 Conflict

			return response()->json(['errors'=>Array(['code'=>409,'message'=>'Este fabricante posee aviones y no puede ser eliminado'])],409);
		}


		//Eliminamos el fabricante si no tiene aviones
		//$fabricanteEliminar=Fabricante::destroy($id);

		$fabricante->delete();
		
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el fabricante'],204);
	}

}
