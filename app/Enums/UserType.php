<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static employee()
 * @method static static lecturer()
 * @method static static both()
 */
final class UserType extends Enum
{
    const Employee =   'employee';
    const Lecturer =   'lecturer';
    const Both =       'lecturer_and_employee';
}
