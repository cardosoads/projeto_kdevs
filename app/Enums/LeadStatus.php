<?php

namespace App\Enums;

enum LeadStatus: string
{
    case Discovered = 'discovered';
    case Qualified = 'qualified';
    case Disqualified = 'disqualified';
    case Contacted = 'contacted';
    case Converted = 'converted';
}
