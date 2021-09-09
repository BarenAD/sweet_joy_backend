<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumericKeysArray implements Rule
{
    private $nameObject;

    /**
     * Create a new rule instance.
     *
     * @param string $nameObject
     */
    public function __construct(string $nameObject = 'object')
    {
        $this->nameObject = $nameObject;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $key => $value) {
            if (!is_int($key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ключи "'.$this->nameObject.'.*" должны быть целочисленными значениеми.';
    }
}
