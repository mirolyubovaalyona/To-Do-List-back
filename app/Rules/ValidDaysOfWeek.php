<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDaysOfWeek implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $validDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $days = json_decode($value, true);
        
        if (!is_array($days)) {
            $fail('Dayes of week not array');
        }

        foreach ($days as $day) {
            if (!in_array($day, $this->validDays)) {
                $fail('The array contains not only days of the week');
            }
        }

        $lastIndex = -1;
        foreach ($days as $day) {
            $currentIndex = array_search($day, $this->validDays);
            if ($currentIndex === false || $currentIndex <= $lastIndex) {
                $fail('The days of the week are not consecutive');
            }
            $lastIndex = $currentIndex;
        }
    }
}
