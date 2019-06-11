<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // initialize faker
        $faker = Faker::create();

        // Use of Faker library to create dummy data for clients and transactions

        // create 20 clients
        foreach (range(1,20) as $index) {
            DB::table('clients')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'avatar' => $faker->image('storage/app/public',400,400,null,false),
                'email' => $faker->email,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

        // create 200 transactions
        foreach (range(1,200) as $index) {
            // Create test transactions
            DB::table('transactions')->insert([
                'client_id' => random_int(1,20),
                'transaction_date' => $faker->dateTime,
                'amount' => $faker->randomFloat(2,1,100000),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
