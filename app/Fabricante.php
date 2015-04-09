<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model {

	// Definimos la tabla MySQL que usará este modelo.

	protected $table="fabricantes";

	// Atributos de la table que se pueden rellenar de forma masiva

	protected $fillable=['nombre','direccion','telefono'];

	// Ocultamos los campos de timestamps en las consultas
	protected $hidden=['created_at','updated_at'];

	// Relacion de fabricante con aviones

	public function aviones(){

		// Un fabricante tiene varios aviones
		return $this->hasMany('App\Avion');

	}

}
