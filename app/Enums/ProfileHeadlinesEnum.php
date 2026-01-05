<?php

namespace App\Enums;

enum ProfileHeadlinesEnum: string
{
    case ARCHITECT = 'architect';
    case ARCHITECT_URBANIST = 'architect_urbanist';
    case INTERIOR_DESIGNER = 'interior_designer';
    case LANDSCAPE_ARCHITECT = 'landscape_architect';
    case URBAN_PLANNER = 'urban_planner';
    case ARCHITECT_AND_INTERIOR_DESIGNER = 'architect_and_interior_designer';

    public static function label(): array
    {
        return [
            self::ARCHITECT->value => 'Arquiteto',
            self::ARCHITECT_URBANIST->value => 'Arquiteto & Urbanista',
            self::INTERIOR_DESIGNER->value => 'Designer de Interiores',
            self::LANDSCAPE_ARCHITECT->value => 'Arquiteto de Paisagismo',
            self::URBAN_PLANNER->value => 'Planejador Urbano',
            self::ARCHITECT_AND_INTERIOR_DESIGNER->value => 'Arquiteto & Designer de Interiores',
        ];
    }
}
