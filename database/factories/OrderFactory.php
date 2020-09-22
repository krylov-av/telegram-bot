<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);
        return [
            'user_id' => \App\Models\User::all()->random(1)->first(),
            //'user_id'=>User::factory()->make(),
            'title' => $faker->productName,
            'clientName' => $this->faker->firstNameFemale." ".$this->faker->lastName,
            'status'=>rand(0,1),
        ];
    }
}
