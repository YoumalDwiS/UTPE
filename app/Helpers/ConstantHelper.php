<?php

namespace App\Helpers;

use App\Enum\ApprovalStatus;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ConstantHelper
{
    public static function memoStatus($type)
    {

        switch ($type) {
            case "WAITING APPROVAL":
                $state = "WAITING APPROVAL";
                break;
            case "WAITING REVISION":
                $state = "WAITING REVISION";
                break;
            case "APPROVED":
                $state = "APPROVED";
                break;
            default:
                $state = "NO STATUS YET";
                break;
        }
        return $state;
    }


}
