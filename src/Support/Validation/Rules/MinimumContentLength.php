<?php

namespace TeamTeaTime\Forum\Support\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use TeamTeaTime\Forum\Support\Content\ContentManager;

class MinimumContentLength implements ValidationRule
{
    private int $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        $text = app(ContentManager::class)->toText($value);

        if (mb_strlen($text) < $this->min) {
            $fail('validation.min.string')->translate([
                'attribute' => $attribute,
                'min' => $this->min,
            ]);
        }
    }
}
