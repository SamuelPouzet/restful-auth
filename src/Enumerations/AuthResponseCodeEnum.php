<?php

namespace Samuelpouzet\RestfulAuth\Enumerations;

enum AuthResponseCodeEnum: int
{
    case OK = 200;
    case DENIED = 403;
    case NEEDS_AUTH = 401;
}
