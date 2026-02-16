<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::inRandomOrder()->first()?->id ?? User::factory(),
            'transaction_id' => strtoupper($this->faker->unique()->bothify('PAY-##??########')),
            'payer_email'    => $this->faker->safeEmail(),
            'amount'         => $this->faker->randomFloat(2, 29.99, 2499.99),
            'currency'       => 'EUR',
            'status'         => $this->faker->randomElement(['COMPLETED', 'COMPLETED', 'COMPLETED', 'PENDING', 'FAILED']),
            'payment_method' => 'paypal',
            'paypal_response'=> null,
            'paid_at'        => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
