<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Avion;

class AvionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return response()->json(['status'=>'ok','data'=>Avion::all()],200);
	}

	public function show($serie)
	{
		$avion=Avion::find($serie);

		//Chequeamos si encontró un avion
		if (!$avion) {

			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese codigo'])],404);
		}

		return response()->json(['status'=>'ok','data'=>$avion],200);
	}

}
