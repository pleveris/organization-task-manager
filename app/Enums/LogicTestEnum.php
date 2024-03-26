<?php

namespace App\Enums;

enum LogicTestEnum: string
{
    case AllSubtasksMustBeCompleted = 'All subtasks must be completed';
    case Expiration = 'Expiration';
    case Complete = 'Complete';
}
