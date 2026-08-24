<?php

namespace App\Enums;

enum MilestoneStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Blocked = 'blocked';
    case Completed = 'completed';
}
