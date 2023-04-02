<?php

namespace Roxayl\MondeGC\Models\Enums;

enum Resource: string
{
    case BUDGET = 'budget';
    case COMMERCE = 'commerce';
    case INDUSTRY = 'industrie';
    case AGRICULTURE = 'agriculture';
    case TOURISM = 'tourisme';
    case RESEARCH = 'recherche';
    case ENVIRONMENT = 'environnement';
    case EDUCATION = 'education';
}
