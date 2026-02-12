<?php

namespace Database\Factories;

use App\Concerns\HasRoleSkills;
use App\Models\Candidate;
use App\Role;
use App\Seniority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidate>
 */
class CandidateFactory extends Factory
{
    use HasRoleSkills;

    protected $model = Candidate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->randomElement(Role::cases());
        $availableSkills = static::getSkillsForRole($role->value);

        return [
            'name' => fake()->name(),
            'role' => $role,
            'seniority' => fake()->randomElement(Seniority::cases()),
            'skills' => fake()->randomElements($availableSkills, fake()->numberBetween(3, 6)),
        ];
    }

    public function role(Role $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
            'skills' => fake()->randomElements(static::getSkillsForRole($role->value), fake()->numberBetween(3, 6)),
        ]);
    }

    public function seniority(Seniority $seniority): static
    {
        return $this->state(fn (array $attributes) => [
            'seniority' => $seniority,
        ]);
    }
}
