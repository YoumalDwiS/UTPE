<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MstApps;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
     
        try{
            $user = User::where('id', Auth::user()->id)->where('accessed_app', NULL)->first();
            
            $apps = MstApps::where('id', Auth::user()->accessed_app)->first();
            if(!empty($user)){
                //return redirect('welcome');
                return view("PBEngine/homepage/operator");
            }else{
                 if(empty($apps)){
                    return $this->home();
                }else{         
                       
                    if ($this->PermissionMenu($apps->link) == 0){
                        User::where('id', Auth::user()->id)
                          ->update([
                              'accessed_app' => NULL,
                              ]
                            );
                           
                        return redirect('welcome')->with('err_message', 'Akses Ditolak!');
                    }
                    return redirect($apps->link);
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }


}
