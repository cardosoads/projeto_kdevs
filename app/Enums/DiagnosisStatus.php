<?php

namespace App\Enums;

enum DiagnosisStatus: string
{
    case Draft = 'draft';
    case Completed = 'completed';
}
