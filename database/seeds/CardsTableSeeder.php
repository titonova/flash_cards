<?php

use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cards')->delete();

        \DB::table('cards')->insert(
            [
                'category' => 1,
                'difficulty' => 1

            ]
        );


    }
}
