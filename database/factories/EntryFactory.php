<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::where('is_active', true)->inRandomOrder()->first();
        $service = Service::where('type', 'S')->where('is_active', true)->inRandomOrder()->first();
        $approved = fake()->boolean();
        return [
            'dc_no' => fake()->numberBetween(100, 1000),
            'client_id' => $client->id ?? null,
            'service_id' => $service->id ?? null,
            'size' => fake()->randomElement([58, 60, 62, 64, 66, 68, 70, 72, 74, 76]),
            'no_of_pcs' => fake()->numberBetween(1, 10),
            'metres' => fake()->randomFloat(2, 1, 10),
            'is_paid' => fake()->boolean(),
            'entry_date' => fake()->date(),
            'is_approved' => $approved,
            'ref_no' => fake()->numberBetween(100, 1000),
            'created_by' => 1,
            'updated_by' => 1,
            'approved_by' => $approved ? 1 : null,
            'approved_at' => $approved ? fake()->dateTimeBetween('-1 year', 'now') : null,
            'reference_no_updated_by' => 1,
            'reference_no_updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
