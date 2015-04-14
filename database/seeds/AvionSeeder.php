<?php

use Illuminate\Database\Seeder;


// Hace uso del modelo del avion
use App\Avion;
use App\Fabricante;

// Usamos el faker

use Faker\Factory as Faker;

class AvionSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker=Faker::create();


		$cuantos=Fabricante::all()->count();

		for ($i=0; $i < 20; $i++) { 
			
			// Cuando llamamos al metodo create del Model Avion
			// Se esteá creando una nueva fila en la tabla de Fabricantes (Active Record Eloquent ORM)

			Avion::create([
				'modelo'=>$faker->word(),
				'longitud'=>$faker->randomFloat(),
				'capacidad'=>$faker->randomNumber(),
				'velocidad'=>$faker->randomNumber(),
				'alcance'=>$faker->randomNumber(),
				'fabricante_id'=>$faker->numberBetween(1,$cuantos),
				

			]);

		}
	}

}