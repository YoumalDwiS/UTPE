<?php

namespace App\Helpers;

use App\Enum\ApprovalStatus;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AlertHelper
{
    public static function getMessage($table, $type)
    {

        switch ($type) {
            case "Memo":
                switch($type){
                    case "Approved" : $message = "Managed to approve the memo"; break;
                    case "Rej" : $message = "Managed to approve the memo"; break;
                }
                break;
        }
        return $message;
    }
}
