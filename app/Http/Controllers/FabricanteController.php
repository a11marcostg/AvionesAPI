<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Fabricante;
use Response;

class FabricanteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return "En el index del fabricante";
		//Devolvemos un json con todos los frabricantes

		//return Fabricante::all(); ----esto e unha forma

		//Para devolver un json con codigo de respuesta HTTP

		return response()->json(['status'=>'ok','data'=>Fabricante::all()],200);


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
	public function update($id)
	{
		
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

		//$fabricanteEliminar=Fabricante::destroy($id);

		$fabricante->delete();
		
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el fabricante'],204);
	}

}
