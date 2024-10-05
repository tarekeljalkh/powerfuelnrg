<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ThirdId' => $this->faker->unique()->numberBetween(1000, 9999),
            'Name' => $this->faker->company,
            'ShortName' => $this->faker->companySuffix,
            'Title' => $this->faker->title,
            'AltName' => $this->faker->company,
            'AltShortName' => $this->faker->companySuffix,
            'ManualRef' => $this->faker->word,
            'CountryId' => $this->faker->numberBetween(1, 200),
            'SisterCompany' => $this->faker->boolean,
            'Address' => $this->faker->address,
            'Phone1' => $this->faker->regexify('\d{3}-\d{3}-\d{4}'), // Generates a shorter phone number like '123-456-7890'
            'Phone2' => $this->faker->regexify('\d{3}-\d{3}-\d{4}'),
            'Phone3' => $this->faker->regexify('\d{3}-\d{3}-\d{4}'),
            'Fax' => $this->faker->regexify('\d{3}-\d{3}-\d{4}'),
            'POBOX' => $this->faker->postcode,
            'Email' => $this->faker->safeEmail,
            'Site' => $this->faker->domainName,
            'ShowInPayable' => $this->faker->boolean,
            'ShowInReceivable' => $this->faker->boolean,
            'ShowInEmployee' => $this->faker->boolean,
            'Blocked' => $this->faker->boolean,
            'ContactName' => $this->faker->name,
            'ContactMail' => $this->faker->email,
            'ContactPhone' => $this->faker->regexify('\d{3}-\d{3}-\d{4}'),
            'Notes' => $this->faker->sentence,
            'VatREG' => $this->faker->regexify('\d{8,15}'), // Adjusted VAT format
            'iUser' => $this->faker->userName,
            'idate' => $this->faker->dateTime,
            'Uuser' => $this->faker->userName,
            'Udate' => $this->faker->dateTime,
            'AreaId' => $this->faker->numberBetween(1, 100),
        ];
    }
}
