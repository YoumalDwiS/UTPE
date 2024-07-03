<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\ErrorLogs;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\View\VwUserRoleGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

use Exception;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{

    public static function PermissionMenu($menu)
    {
        $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link', $menu)->count();

        return $appsmenu;
    }

    public static function PermissionActionMenu($menu)
    {
        $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link', $menu)->first();
        // if (!Auth::check()) {
        //     return response()->json(['error' => 'User not authenticated'], 401);
        // }
    
        // $user = Auth::user();
    
        // // Ensure the user object is not null
        // if (!$user) {
        //     return response()->json(['error' => 'User not found'], 404);
        // }
    
        // // Ensure the access token is not null
        // if (is_null($user->access_token)) {
        //     return response()->json(['error' => 'Access token not found'], 400);
        // }
    
        // $access_token = $user->access_token;
        // $postdata = array(
        //     'menu' => $menu,
        // );
    
        // $data = Http::withHeaders([
        //     'x-api-key' =>  '26|rCG1TgCKPV1YMUv5AKP8zi8ukemsvL3L4QMJNDN2',
        //     'Authorization' => 'Bearer ' . $access_token
        // ])
        // ->post('https://satria-apps.patria.co.id/satria-api-man/public/api/satria-permission-menu', $postdata);
    
        // $result = json_decode($data);
    
        // if (empty($result->data)) {
        //     return false;
        // } else {
        //     return $result->data[0];
        // }

        return $appsmenu;
    }

    public static function GetUserRoleGroup()
    {

        $role = VwUserRoleGroup::select('role_name')->where('user', Auth::user()->id)->where('apps', Auth::user()->accessed_app)->first();
        return $role->role_name;
    }

    public static function ErrorLog($e)
    {
        try {
            $message = $e->getMessage();
            $code = $e->getCode();
            $string = $e->__toString();
            $create = ErrorLogs::create([
                'remote_addr' => $_SERVER['REMOTE_ADDR'],
                'action' => url()->current(),
                'code' => $code,
                'message' => $message,
                'ex_string' => $string,
                'apps' => Auth::user()->accessed_app,
                'created_by' => Auth::user()->email,
            ]);
        } catch (Exception $e) {
        }
    }




    
}
