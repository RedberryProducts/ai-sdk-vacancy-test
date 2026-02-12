<?php

namespace App;

enum Role: string
{
    case BackendDeveloper = 'backend_developer';
    case FrontendDeveloper = 'frontend_developer';
    case FullStackDeveloper = 'fullstack_developer';
    case DevOpsEngineer = 'devops_engineer';
    case QaEngineer = 'qa_engineer';
    case UiUxDesigner = 'ui_ux_designer';
    case ProjectManager = 'project_manager';

    public function label(): string
    {
        return match ($this) {
            self::BackendDeveloper => 'Backend Developer',
            self::FrontendDeveloper => 'Frontend Developer',
            self::FullStackDeveloper => 'Full Stack Developer',
            self::DevOpsEngineer => 'DevOps Engineer',
            self::QaEngineer => 'QA Engineer',
            self::UiUxDesigner => 'UI/UX Designer',
            self::ProjectManager => 'Project Manager',
        };
    }
}
