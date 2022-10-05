<?php

use Illuminate\Database\Seeder;

class CardCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('card_categories')->delete();

        \DB::table('card_categories')->insert(array(
            0 =>
            array(
                'created_at' => '2022-10-03 11:10:08',
                'id' => 1,
                'name' => 'Laravel - Routing',
                'updated_at' =>'2022-10-03 11:10:08',
            ),
        ));
    }
}
