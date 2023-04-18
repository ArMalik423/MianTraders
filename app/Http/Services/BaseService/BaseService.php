<?php

namespace App\Http\Services\BaseService;

use App\Http\Traits\ApiResponse;
use App\Http\Traits\AuthUser;

class BaseService
{
    /*## Custom Traits  ##*/
    use ApiResponse;
    use AuthUser;
}
