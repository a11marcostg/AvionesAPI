<?php

use Illuminate\Database\Seeder;


// Hace uso del modelo del fabricante
use App\Fabricante;

// Usamos el faker

use Faker\Factory as Faker;


class FabricanteSeeder extends Seeder{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker=Faker::create();

		for ($i=0; $i < 5; $i++) { 
			
			// Cuando llamamos al metodo create del Model Fabricante
			// Se esteá creando una nueva fila en la tabla de Fabricantes (Active Record Eloquent ORM)

			Fabricante::create([
				'nombre'=>$faker->word(),
				'direccion'=>$faker->word(),
				'telefono'=>$faker->randomNumber(),

			]);

		}
	}

}