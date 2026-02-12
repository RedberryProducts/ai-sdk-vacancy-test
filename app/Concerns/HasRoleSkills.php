<?php

namespace App\Concerns;

trait HasRoleSkills
{
    /**
     * Skills available for each role.
     *
     * @var array<string, list<string>>
     */
    protected static array $skillsByRole = [
        'backend_developer' => ['PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'REST API', 'GraphQL', 'Node.js', 'Python'],
        'frontend_developer' => ['JavaScript', 'TypeScript', 'React', 'Vue.js', 'Angular', 'HTML', 'CSS', 'Tailwind CSS', 'Next.js', 'Webpack'],
        'fullstack_developer' => ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'MySQL', 'Docker', 'REST API', 'Tailwind CSS', 'Node.js'],
        'devops_engineer' => ['Docker', 'Kubernetes', 'AWS', 'GCP', 'CI/CD', 'Terraform', 'Ansible', 'Linux', 'Nginx', 'Monitoring'],
        'qa_engineer' => ['Selenium', 'Cypress', 'PHPUnit', 'Jest', 'API Testing', 'Load Testing', 'Test Planning', 'Bug Tracking', 'Automation'],
        'ui_ux_designer' => ['Figma', 'Sketch', 'Adobe XD', 'Prototyping', 'User Research', 'Wireframing', 'Design Systems', 'Accessibility', 'UI Design'],
        'project_manager' => ['Agile', 'Scrum', 'Jira', 'Confluence', 'Risk Management', 'Stakeholder Management', 'Budgeting', 'Team Leadership'],
    ];

    /**
     * Get skills for a specific role.
     *
     * @return list<string>
     */
    protected static function getSkillsForRole(string $role): array
    {
        return static::$skillsByRole[$role] ?? [];
    }

    /**
     * Get all skills by role as formatted string.
     */
    protected function getSkillsByRoleAsString(): string
    {
        $lines = [];
        foreach (static::$skillsByRole as $role => $skills) {
            $roleLabel = str_replace('_', ' ', ucwords($role, '_'));
            $lines[] = "- {$roleLabel}: ".implode(', ', $skills);
        }

        return implode("\n", $lines);
    }
}
