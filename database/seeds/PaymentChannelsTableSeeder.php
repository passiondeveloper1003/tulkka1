<?php

use Illuminate\Database\Seeder;

class PaymentChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Models\PaymentChannel::$classes as $index => $class) {

            \App\Models\PaymentChannel::updateOrCreate(
                ['id' => $index + 1],
                [
                    'title' => $class,
                    'class_name' => $class,
                    'status' => 'active',
                    'image' => null,
                    'settings' => '',
                    'created_at' => time()
                ]
            );
        }
    }
}
