<?php

namespace NotificationChannels\Bandwidth\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumberRule implements Rule
{
    /**
     * @var int
     */
    protected $minimum;

    /**
     * Create a new rule instance.
     *
     * @param int $minimum
     */
    public function __construct($minimum = 1)
    {
        $this->minimum = (int) $minimum;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @source https://stackoverflow.com/questions/6478875/regular-expression-matching-e-164-formatted-phone-numbers
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (bool)preg_match("/^\+?[1-9]\d{{$this->minimum},14}$/", (string) $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must follow the E.164 number format.';
    }
}
