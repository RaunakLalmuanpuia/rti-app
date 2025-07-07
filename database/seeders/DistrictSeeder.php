<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $districts = [
            ['name' => 'Aizawl'],
            ['name' => 'Lunglei'],
//            ['name' => 'District 3'],
            // Add more districts as needed
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}
