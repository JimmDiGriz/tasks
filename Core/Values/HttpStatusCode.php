<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Values;

class HttpStatusCode 
{
    const OK = 200;
    const PERMANENT_REDIRECT = 301;
    const TEMPORARY_REDIRECT = 302;
    const BAD_REQUEST = 400;
    const AUTH_FAILED = 401;
    const ACCESS_DENIED = 403;
    const NOT_FOUND = 404;
    const INTERNAL_SERVER_ERROR = 500;
}