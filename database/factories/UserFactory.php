<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;

class UserFactory extends CoreFactory
{
    const DEFAULT_USER_PASSWORD = 'qwerty';

    protected $model = User::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'fio' => $this->faker->regexify('/^\w{10} \w{10} \w{10}$/'),
            'phone' => $this->faker->regexify('/^[7]\d{10}$/'),
            'note' => $this->faker->text('100'),
            'email' => $this->faker->regexify('/^\w{15}@gmail\.com'),
            'email_verified_at' => $this->generateRandomDate(),
            'password' => '$2a$12$fEj0.qUMQ3lp600rYbAwNefGxSETJSbWhXXPM4VTIcdZo3TnP5MbO', // qwerty
        ]);
    }
}
