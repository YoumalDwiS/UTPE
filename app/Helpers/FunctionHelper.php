<?php

namespace App\Helpers;

use App\Enum\ApprovalStatus;
use App\Models\View\VwUserRoleGroup;
use DateTime;
use Illuminate\Support\Facades\Auth;

class FunctionHelper
{
    const example = 'Example!';

    public static function isEmpty($value)
    {
        return empty($value) ? '-' : $value;
    }

    public static function transactionMessage($model, $type)
    {
        switch ($type) {
            case 'error':
                $result = 'Data ' . $model . ' tidak berhasil ditambahkan!';
                break;
            default:
                $result = 'Data ' . $model . ' berhasil ditambahkan!';
                break;
        }

        return $result;
    }

    public static function isFileExists($url)
    {
        return file_exists(public_path() . '/storage/' . $url);
    }

    public static function getFile($file, $defaultFile): string
    {
        $defaultFileUrl = asset('assets/images/placeholder') . '/' . $defaultFile;
        $fileUrl = asset('storage') . '/' . $file;

        return self::isFileExists($file) ? $fileUrl : $defaultFileUrl;
    }

    public static function extractNumber($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public static function isValidDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    public static function getMemoStatusColor($type)
    {
        switch ($type) {
            case "WAITING APPROVAL":
                $state = "primary";
                break;
            case "WAITING REVISION":
                $state = "warning";
                break;
            case "APPROVED":
                $state = "success";
                break;
            default:
                $state = "secondary";
                break;
        }

        return $state;
    }

    public static function getMemoKanbanStatus($id_proses)
    {
        switch ($id_proses) {
            case 50:
                $badge ="<div class='badge badge-primary'>NEW</div>";
                break;
            case 51:
                $badge ="<div class='badge badge-warning'>PROSES</div>";
                break;
            case 52:
                $badge ="<div class='badge badge-success'>FINISH</div>";
                break;
            case 53:
                $badge ="<div class='badge badge-danger'>REJECT</div>";
                break;
            case 500:
                $badge ="<div class='badge badge-primary'>NEW</div>";
                break;
            case 501:
                $badge ="<div class='badge badge-warning'>PROSES</div>";
                break;
            case 502:
                $badge ="<div class='badge badge-warning'>PROSES</div>";
                break;
            case 503:
                $badge = "<div class='badge badge-success'>FINISH</div>";
                break;
            default:
                $badge = "<div class='badge badge-secondary'>-</div>";
                break;
        }

        // dd($badge);
        return $badge;
    }

    public static function getUserInitial()
    {
        $name = explode(' ', Auth::user()->name);
        if (count($name) == 1) {
            $initial = substr($name[0], 0, 1);
        } else {
            $initial = substr($name[0], 0, 1) . substr($name[count($name) - 1], 0, 1);
        }

        return $initial;
    }

    public static function getUserColor()
    {
        // dd(Auth::user());
        $user = VwUserRoleGroup::where('user', Auth::user()->id)->where('apps', Auth::user()->accessed_app)->first();
        $role = $user->role_name;
        // $role = "Nesting Planner PBEngine";

        switch ($role) {
            case "Operator PBEngine":
                $color = "success";
                break;
            case "Nesting Planner PBEngine":
                $color = "danger";
                break;
            case "Group Leader PBEngine":
                $color = "warning";
                break;
            case "Supervisor PBEngine":
                $color = "twilight";
                break;
            case "Material Kontrol PBEngine":
                $color = "primary";
                break;
            case "Admin PBEngine":
                $color = "dark";
                break;
            default:
                $color = "secondary";
                break;
        }

        return $color;
    }

    public static function getUserRoleGroup()
    {
        $role = VwUserRoleGroup::select('role_name')->where('user', Auth::user()->id)->where('apps', Auth::user()->accessed_app)->first();
        return $role->role_name;
    }

}
