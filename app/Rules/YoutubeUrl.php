<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class YoutubeUrl implements Rule
{
    public $extractor;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->extractor = resolve('App\Includes\YoutubeIdExtractor');
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
        if ($this->extractor->extractId($value) === null) {
            return false;
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
        return 'The :attribute must be a valid youtube url.';
    }
}
