<?php

namespace Database\Factories;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'amount'      => $this->faker->randomFloat(2, 1, 1000),
            'category'    => $this->faker->word,
            'date'        => $this->faker->date(),
        ];
    }
}
