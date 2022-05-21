<?php

namespace App\Enums;

enum OperatorSymbol
{
    case EQUAL;

    case AND;

    case OR;

    case NOT;

    case GREATER_THAN;

    case LESS_THAN;
}
