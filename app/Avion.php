<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Avion extends Model {

	// Definimos la tabla MySQL que usará este modelo.

	protected $table="aviones";

	// Clave primaria de la tabla aviones.
	// En este caso es el campo serie, por lo tanto hay que indicarlo.
	// Si no se indica, por defecto seria un campo llamado "id"
	protected $primaryKey='serie';

	// Atributos de la table que se pueden rellenar de forma masiva

	protected $fillable=['modelo','longitud','capacidad','velocidad','alcance'];

	// Ocultamos los campos de timestamps en las consultas
	protected $hidden=['created_at','updated_at'];

	// Definimos relacion con fabricante

	public function fabricantes(){

		// Un avion tiene solo un fabricante

		return $this->belongsTo('App\Fabricante');

	}

}
