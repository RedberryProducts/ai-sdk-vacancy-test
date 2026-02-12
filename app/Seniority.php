<?php

namespace App;

enum Seniority: string
{
    case Junior = 'junior';
    case Middle = 'middle';
    case Senior = 'senior';
    case Lead = 'lead';
    case Principal = 'principal';

    public function label(): string
    {
        return match ($this) {
            self::Junior => 'Junior',
            self::Middle => 'Middle',
            self::Senior => 'Senior',
            self::Lead => 'Lead',
            self::Principal => 'Principal',
        };
    }
}
