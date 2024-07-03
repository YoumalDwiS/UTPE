<?php

namespace App\Providers;

use App\Helpers\FunctionHelper;
use App\Models\Table\PBEngine\TbNotification;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\View\VwUserRoleGroup;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Blade::withoutDoubleEncoding();
        view()->composer(['PBEngine.template.menu.sidebar-menu', 'PBEngine.template.menu.topbar-menu', 'PBEngine.template.component.profile-navbar'], function ($view) {

            $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('app', Auth::user()->accessed_app)->orderBy('menu', 'asc')->get();
            $haveapp =  VwPermissionAppsMenu::select('app', 'app_name', 'logo')->where('user', Auth::user()->id)->groupBy('app_name')->get();
            $mainmenu = VwPermissionAppsMenu::select('main', 'icon')->where('user', Auth::user()->id)->where('app', Auth::user()->accessed_app)->orderBy('main', 'asc')->groupBy('main')->get();

            $user = VwUserRoleGroup::where('user', Auth::user()->id)->where('apps', Auth::user()->accessed_app)->first();
            $notification = TbNotification::where(function ($query) use ($user) {
                $query->where('role_name', 'ALL')->orWhere('role_name', $user->role_name);
            })->whereRaw('(SELECT COUNT(tb_notification_reader.id) FROM tb_notification_reader WHERE tb_notification_reader.notification_id = tb_notification.id AND tb_notification_reader.read_by = ' . Auth::user()->id . ' GROUP BY tb_notification_reader.notification_id) is null')->orderBy('tb_notification.created_at', 'DESC')->take(5)->get();
            //->whereRaw('(SELECT COUNT(tb_notification_reader.id) FROM tb_notification_reader WHERE tb_notification_reader.notification_id = tb_notification.id AND tb_notification_reader.read_by = ' . Auth::user()->id . ' GROUP BY tb_notification_reader.notification_id) is null')

            $count_notification = TbNotification::where(function ($query) use ($user) {
                $query->where('role_name', 'ALL')->orWhere('role_name', $user->role_name);
            })->whereRaw('(SELECT COUNT(tb_notification_reader.id) FROM tb_notification_reader WHERE tb_notification_reader.notification_id = tb_notification.id AND tb_notification_reader.read_by = ' . Auth::user()->id . ' GROUP BY tb_notification_reader.notification_id) is null')->get()->count('id');

            // dd($notification->toArray());
            $datamenu = [];
            foreach ($mainmenu as $main) {
                $menu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('main', $main->main)->where('app', Auth::user()->accessed_app)->groupBy('menu')->orderBy('menu', 'asc')->get();
                $datamenu[] = [
                    'main' => $main->main,
                    'menu' => $menu,
                    'icon' => $main->icon,
                ];
            }
            $data = array(
                'haveapp' => $haveapp,
                'appsmenu' => $appsmenu,
                'datamenu' => $datamenu,
                'notification' => $notification,
                'count_notification' => $count_notification,
            );

            $view->with('data', $data);
        });
    }
}
