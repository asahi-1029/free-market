<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => User::first()->id,
            'address' => 'さいたま市桜区',
            'postal_code' => '338-0824',
        ];
        DB::table('addresses')->insert($param);
    }
}
