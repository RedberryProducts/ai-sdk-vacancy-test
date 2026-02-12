<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Role;
use App\Seniority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidate>
 */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    private const SKILLS_BY_ROLE = [
        'backend_developer' => ['PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'REST API', 'GraphQL', 'Node.js', 'Python'],
        'frontend_developer' => ['JavaScript', 'TypeScript', 'React', 'Vue.js', 'Angular', 'HTML', 'CSS', 'Tailwind CSS', 'Next.js', 'Webpack'],
        'fullstack_developer' => ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'MySQL', 'Docker', 'REST API', 'Tailwind CSS', 'Node.js'],
        'devops_engineer' => ['Docker', 'Kubernetes', 'AWS', 'GCP', 'CI/CD', 'Terraform', 'Ansible', 'Linux', 'Nginx', 'Monitoring'],
        'qa_engineer' => ['Selenium', 'Cypress', 'PHPUnit', 'Jest', 'API Testing', 'Load Testing', 'Test Planning', 'Bug Tracking', 'Automation'],
        'ui_ux_designer' => ['Figma', 'Sketch', 'Adobe XD', 'Prototyping', 'User Research', 'Wireframing', 'Design Systems', 'Accessibility', 'UI Design'],
        'project_manager' => ['Agile', 'Scrum', 'Jira', 'Confluence', 'Risk Management', 'Stakeholder Management', 'Budgeting', 'Team Leadership'],
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->randomElement(Role::cases());
        $availableSkills = self::SKILLS_BY_ROLE[$role->value] ?? [];

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
            'skills' => fake()->randomElements(self::SKILLS_BY_ROLE[$role->value] ?? [], fake()->numberBetween(3, 6)),
        ]);
    }

    public function seniority(Seniority $seniority): static
    {
        return $this->state(fn (array $attributes) => [
            'seniority' => $seniority,
        ]);
    }
}
