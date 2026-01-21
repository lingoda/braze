<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

enum Gender: string
{
    case Male = 'M';
    case Female = 'F';
    case Other = 'O';
    case NotApplicable = 'N';
    case PreferNotToSay = 'P';
    case Unknown = 'nil';
}
