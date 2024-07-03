<?php

namespace App\Http\Controllers\Cms\PBEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\View\VwUserOnboardingHistory;
use App\Models\View\VwUserRoleGroup;
use App\Models\OnboardingHistory;
use App\Models\OnboardingModule;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{

    public function index(){
        $module = OnboardingModule::where('apps_id', 26)->get();
        return view('PBEngine/onboarding/index')->with('data', [
            'onboarding_module' => $module,
        ]);
    }

    public function getHistory(Request $request){
        $history = VwUserOnboardingHistory::where('apps_id', 26)->where('user_id', Auth::user()->id)->where('module_code', $request->module_code)->first();
        // dd($request->module_code, Auth::user()->id, $history);
        if($history){
            return true;
        }
        return false;
    }

    public function addHistory(Request $request){
        $module = OnboardingModule::where('module_code', $request->module_code)->first();
        // dd($request->all(),$module);
        OnboardingHistory::create([
            'module_id' => $module->id,
            'user_id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'created_by' => auth()->user()->name,
            'updated_by' => auth()->user()->name,
        ]);

        return 200;
    }
}
