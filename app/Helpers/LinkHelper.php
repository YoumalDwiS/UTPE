<?php

namespace App\Helpers;

use App\Enum\ApprovalStatus;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LinkHelper
{
    public static function API_IMA()
    {
        return "http://10.48.10.43/imaapi/";
    }

    public static function SATRIA_URL()
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        return $actual_link . env('ENV_SATRIA_URL');
    }
}
