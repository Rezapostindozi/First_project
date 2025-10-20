<?php


namespace App\Enums;



enum HttpStatus: int
{
    case OK = 200;
    case CREATED = 201;
    case NO_CONTENT  = 204;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED  = 401;
    case FORBIDDEN  = 403;
    case NOT_FOUND  = 404;
    case Method_Not_Allowed = 405;
    case Unprocessable_Entity = 422;
    case Too_many_Requests = 429;
    case MOVED_PERMANENTLY = 500;
    case Bad_GateWay = 502;
    case  Geteway_Timeout = 504;
}
