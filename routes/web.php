<?php

use App\Http\Controllers\ActivityJobController as ControllersActivityJobController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\PBEngine\APIController;
use App\Http\Controllers\Cms\PBEngine\AssignMachineController;
use App\Http\Controllers\Cms\PBEngine\BOMController;
use App\Http\Controllers\Cms\PBEngine\MemoPBController;
use App\Http\Controllers\Cms\PBEngine\MemoPPCController;
use App\Http\Controllers\Cms\PBEngine\ProductController;
use App\Http\Controllers\Cms\PBEngine\HomepageController;
use App\Http\Controllers\Cms\PBEngine\MaterialController;
use App\Http\Controllers\Cms\PBEngine\ComponentController;
use App\Http\Controllers\Cms\PBEngine\OnboardingController;
use App\Http\Controllers\Cms\PBEngine\SemifinishController;
use App\Http\Controllers\Cms\PBEngine\RawMaterialController;
use App\Http\Controllers\Cms\PBEngine\NotificationController;
use App\Http\Controllers\Cms\PBEngine\MemoWarehouseController;

use App\Http\Controllers\Cms\PBEngine\MappingProCustomerController;
use App\Http\Controllers\Cms\PBEngine\SafetyFactorCapacityController;
use App\Http\Controllers\Cms\PBEngine\MoveQuantityController;
use App\Http\Controllers\Cms\PBEngine\ReasonPauseController;
use App\Http\Controllers\Cms\PBEngine\CustomerController;
use App\Http\Controllers\Cms\PBEngine\MesinController;
use App\Http\Controllers\Cms\PBEngine\UserController;
use App\Http\Controllers\Cms\PBEngine\DashboardController;
use App\Http\Controllers\Cms\PBEngine\DashboardAssignTomorrowController;
use App\Http\Controllers\Cms\PBEngine\MappingImageComponentController;
use App\Http\Controllers\Cms\PBEngine\JobController;
use App\Http\Controllers\Cms\PBEngine\ActivityJobController;
use App\Http\Controllers\Cms\PBEngine\BundlingJobController;
use App\Http\Controllers\Cms\PBEngine\JobFinishedController;
use App\Http\Controllers\Cms\PBEngine\JobGroupLeaderController;
use App\Http\Controllers\Cms\PBEngine\JobIssueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/pb-homepage');
});

Auth::routes();
Route::middleware('auth')->group(function () {
    // Homepage
    Route::get('/pb-homepage', [HomepageController::class, 'index']);
    Route::get('/getMachine', [HomepageController::class, 'getMachine']);

    // API
    Route::group(['prefix' => 'api'], function () {
        Route::get('/sync-master-data', [APIController::class, 'syncronizeMasterData']);
    });

    // Product
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/get', [ProductController::class, 'getProduct']);
        Route::get('/syncronize', [ProductController::class, 'syncProductIMA']);
    });

    // Component
    Route::group(['prefix' => 'component'], function () {
        Route::get('/', [ComponentController::class, 'index']);
        Route::get('/get', [ComponentController::class, 'getComponent']);
        Route::get('/syncronize', [ComponentController::class, 'syncComponentIMA']);
    });

    // Material
    Route::group(['prefix' => 'material'], function () {
        Route::get('/', [MaterialController::class, 'index']);
        Route::get('/get', [MaterialController::class, 'getMaterial']);
        Route::get('/get-material-select', [MaterialController::class, 'getMaterialSelect']);
        Route::get('/syncronize', [MaterialController::class, 'syncMaterialIMA']);
    });

    // Raw Material
    Route::group(['prefix' => 'raw-material'], function () {
        Route::get('/', [RawMaterialController::class, 'index']);
        // Route::get('/{id}', [RawMaterialController::class, 'show']);
        Route::get('/syncronize', [RawMaterialController::class, 'syncRawMaterialCCR']);
        Route::post('/import', [RawMaterialController::class, 'importExcel']);
        Route::get('/get', [RawMaterialController::class, 'getRawMaterial']);
        Route::get('/edit', [RawMaterialController::class, 'editRawMaterial']);
        Route::post('/update', [RawMaterialController::class, 'updateRawMaterial']);
        Route::post('/delete', [RawMaterialController::class, 'deleteRawMaterial']);
    });

    // Bill of Material
    Route::group(['prefix' => 'bom'], function () {
        Route::get('/', [BOMController::class, 'index']);
        Route::get('/detail/{id}', [BOMController::class, 'show']);
        Route::get('/syncronize-prod-comp', [BOMController::class, 'syncProdCompIMA']);
        Route::get('/syncronize-comp-raw', [BOMController::class, 'syncCompRawIMA']);
        Route::post('/import', [BOMController::class, 'importMatrixExcel']);
        Route::get('/get-prod-comp', [BOMController::class, 'getProdComp']);
        Route::get('/get-comp-raw', [BOMController::class, 'getCompRaw']);
    });

    // Memo Component
    Route::group(['prefix' => 'memo-ppc'], function () {
        Route::get('/', [MemoPPCController::class, 'index']);
        Route::get('/detail/{id}', [MemoPPCController::class, 'show']);
        Route::post('/approve/{id}', [MemoPPCController::class, 'approve']);
        Route::post('/reject/{id}', [MemoPPCController::class, 'reject']);
        Route::get('/print/{id}', [MemoPPCController::class, 'print']);
    });

    // Memo Raw Material
    Route::group(['prefix' => 'memo-pb'], function () {
        Route::get('/', [MemoPBController::class, 'index']);
        Route::get('/create/', [MemoPBController::class, 'create']);
        Route::post('/store', [MemoPBController::class, 'store']);
        Route::get('/recive-check/{id}', [MemoPBController::class, 'reciveCheck']);
        Route::post('/recive/{id}', [MemoPBController::class, 'recive']);
        Route::get('/detail/{id}', [MemoPBController::class, 'show']);

        //ajax route
        Route::get('/get-sub-process/{id}', [MemoPBController::class, 'getSubProsesByProduct']);
        Route::get('/get-detail-ticket/{id}', [MemoPBController::class, 'getDetailTicket']);
        Route::get('/get-raw-material-stock', [MemoPBController::class, 'getRawMaterialStock']);
        // Route::get('/get-memo-ppc/{product_id}', [MemoPBController::class, 'getMemoPPC']);
        Route::get('/get-memo-ppc', [MemoPBController::class, 'getMemoPPC']);
        Route::get('/get-pro/{memo_id}', [MemoPBController::class, 'getPRO']);
        Route::get('/get-memo-component-ppc', [MemoPBController::class, 'getMemoComponentPPC']);


        // Memo Material Cart
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/get', [MemoPBController::class, 'getMaterialCart']);
            Route::post('/add', [MemoPBController::class, 'addMaterialCart']);
            Route::post('/update/{id}', [MemoPBController::class, 'updateMaterialCart']);
            Route::post('/delete', [MemoPBController::class, 'deleteMaterialCart']);
        });
    });

    // Memo Semifinish
    Route::group(['prefix' => 'memo-warehouse'], function () {
        Route::get('/', [MemoWarehouseController::class, 'index']);
        Route::get('/detail/{id}', [MemoWarehouseController::class, 'show']);

        //ajax route
        Route::get('/get-detail-ticket/{id}', [MemoWarehouseController::class, 'getDetailTicket']);
    });

    // Semi Finish
    Route::group(['prefix' => 'semifinish'], function () {
        Route::get('/', [SemifinishController::class, 'index']);
        Route::get('/detail/{product_id}/{memo_id?}/{sub_proccess_id?}/{search_pn?}', [SemifinishController::class, 'show'])->name('show-progress-product');
        Route::put('/update-progress', [SemifinishController::class, 'update_progress']);
        Route::get('/inventory', [SemifinishController::class, 'indexInventory']);
        Route::get('/inventory-detail/{id}', [SemifinishController::class, 'inventoryDetail']);
        Route::get('/inventory-detail-export', [SemifinishController::class, 'inventoryDetailExport']);
        Route::get('/inventory-get', [SemifinishController::class, 'getSemifinishInventory']);
        Route::get('/inventory-get/{type}/{product}', [SemifinishController::class, 'getSemifinishInventoryProduct']);
        Route::get('/monitoring', [SemifinishController::class, 'indexMonitoring']);
        Route::get('/monitoring/{memo_id}', [SemifinishController::class, 'detailMonitoring']);
        Route::get('/monitoring/get-list-memo/{product?}', [SemifinishController::class, 'getListMemo']);
        Route::get('/monitoring/get-list/memo/all', [SemifinishController::class, 'getListMemoAll']);
        Route::get('/monitoring/get-list/chart/data', [SemifinishController::class, 'getChartDataPRO']);
    });

    // Notification
    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/detail/{id}', [NotificationController::class, 'show']);
    });

    // Onboarding
    Route::group(['prefix' => 'onboarding'], function () {
        Route::get('/', [OnboardingController::class, 'index']);
        Route::get('/get-history/', [OnboardingController::class, 'getHistory']);
        Route::post('/add-history', [OnboardingController::class, 'addHistory']);
    });

    Route::get('/cekAPI', [MemoPPCController::class, 'cekAPI']);

    // SafetyFactorCapacity
    Route::group(['prefix' => 'safety-factor-capacity'], function(){
        Route::resource('safety-factor-capacity', SafetyFactorCapacityController::class);
        Route::get('/', [SafetyFactorCapacityController::class, 'index']);
        Route::get('/search', [SafetyFactorCapacityController::class, 'search']);
        Route::post('/store', [SafetyFactorCapacityController::class, 'store']);           
    }); 

    // Reason Pause
    Route::group(['prefix' => 'reason-pause'], function(){
        Route::resource('reason-pause', ReasonPauseController::class);
        Route::get('/', [ReasonPauseController::class, 'index']);
        Route::get('/search', [ReasonPauseController::class, 'search']);
        Route::post('/store', [ReasonPauseController::class, 'store']);           
    }); 


    // Customer
    Route::group(['prefix' => 'customer'], function(){
        Route::resource('customer', CustomerController::class);
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/search', [CustomerController::class, 'search']);
        Route::post('/store', [CustomerController::class, 'store']);
    });

     // Mesin
     Route::group(['prefix' => 'machine'], function(){
        Route::resource('machine', MesinController::class);
        Route::get('/', [MesinController::class, 'index']);
        Route::get('/getProcess', [MesinController::class, 'getProcess']);
        Route::get('/search', [MesinController::class, 'search']);
        Route::get('/getSafety', [MesinController::class, 'getSafety']);
        Route::get('/getSafetyFactors', [MesinController::class, 'getSafetyFactors']);
        Route::get('/getSafety', [MesinController::class, 'getSafety']);
        Route::get('/m_list_machine_breakdown', [MesinController::class, 'm_list_machine_breakdown']);
      //  Route::post('/store', [MesinController::class, 'store']);
    });


    // User
    Route::group(['prefix' => 'user'], function(){
        Route::resource('user', UserController::class);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/search', [UserController::class, 'search']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('autocomplete', [UserController::class, 'autocomplete']);
        Route::get('getUserData', [UserController::class, 'getUserData']);
        Route::get('/getProcess', [UserController::class, 'getProcess']);
        Route::get('/getProcessEdit', [UserController::class, 'getProcessEdit']);
    });


    //Assign
    Route::group(['prefix' => 'assign-machine'], function(){
        Route::resource('assign-machine', AssignMachineController::class);
        Route::get('/', [AssignMachineController::class, 'index']);
    });

    //Move-Quantity
    Route::group(['prefix' => 'move-quantity'], function(){
        Route::get('/', [MoveQuantityController::class, 'allAvailableMove']);
        Route::get('/get-list-component-by-partnumbercomponent/{anp_id}/{target_id}', [MoveQuantityController::class, 'getListComponentByPartnumbercomponent']);
        Route::get('/show-modal-move-qty/{target_id}/{anp_id}', [MoveQuantityController::class, 'showModalMoveQty']);
        Route::post('/move-qty/{target_id}/{anp_id}', [MoveQuantityController::class,'moveQty']);

    });


    //Dashboard
    Route::group(['prefix' => 'dashboard-pb'], function(){
        Route::resource('dashboard-pb', DashboardController::class);
        Route::get('/', [DashboardController::class, 'index']);
        // Route::get('/test', [DashboardController::class, 'test']);
        Route::get('/test2', [DashboardController::class, 'test2']);
        Route::get('/test3', [DashboardController::class, 'test3']);
        Route::get('/getData', [DashboardController::class, 'getDataAssign']);
        Route::get('/m_list_data_view99', [DashboardAssignTomorrowController::class, 'm_list_data_view99']);
        Route::get('/m_list_data_view1', [DashboardAssignTomorrowController::class, 'm_list_data_view1']);
        Route::get('/m_list_data_view2', [DashboardAssignTomorrowController::class, 'm_list_data_view2']);
        Route::get('/getDataForTomorrow', [DashboardAssignTomorrowController::class, 'getDataForTomorrow']);
        Route::get('/index_For_Tomorrow', [DashboardAssignTomorrowController::class, 'index']);
        // Route::get('/assign', [AssignMachineController::class, 'test1']);
        Route::get('/assign/{mppid}', [AssignMachineController::class, 'test1']);
        Route::get('/test/{mppid}', [AssignMachineController::class, 'test']);
        Route::get('/moving/{mppid}', [AssignMachineController::class, 'moving']);
        // Route::post('/save_moving', [AssignMachineController::class, 'save_moving'])->name('save_moving.save_moving');
        Route::post('/save_moving', [AssignMachineController::class, 'save_moving'])->name('move-quantity.save_moving');
        
        Route::get('/m_history_movein/{mppid}', [AssignMachineController::class, 'm_history_movein']);
        Route::get('/m_history_moveout/{mppid}', [AssignMachineController::class, 'm_history_moveout']);

        Route::get('/all-available-move', [MoveQuantityController::class, 'allAvailableMove']);
        Route::get('/get-list-component-by-partnumbercomponent/{anp_id}/{target_id}', [MoveQuantityController::class, 'getListComponentByPartnumbercomponent']);
        Route::get('/show-modal-move-qty/{target_id}/{anp_id}', [MoveQuantityController::class, 'showModalMoveQty']);
        Route::post('/move-qty/{target_id}/{anp_id}', [MoveQuantityController::class,'moveQty']);
        // Route::post('/save}', [AssignMachineController::class, 'save']);

    });



    //Mapping Image Componet
    Route::group(['prefix' => 'mapping-image'], function(){
        Route::resource('mapping-image', MappingImageComponentController::class);
        Route::get('/', [MappingImageComponentController::class, 'index']);
        Route::get('/search', [MappingImageComponentController::class, 'search']);
        Route::get('/getComponent', [MappingImageComponentController::class, 'getComponent']);
        Route::get('/getProduct', [MappingImageComponentController::class, 'getProduct']);    
        Route::get('/checkComponentExists', [MappingImageComponentController::class, 'checkComponentExists']);
        Route::get('/getImage', [MappingImageComponentController::class, 'getImage']);
        Route::get('/getImageHistory', [MappingImageComponentController::class, 'getImageHistory']);
        Route::get('/getImageHistoryAct', [MappingImageComponentController::class, 'getImageHistoryAct']);
        Route::get('/getMappingByComponent/{component_id}', [MappingImageComponentController::class, 'getMappingByComponent']);
        Route::get('/getAllProduct', [MappingImageComponentController::class, 'getAllProduct']); 
        Route::get('/getProductEdit', [MappingImageComponentController::class, 'getProductEdit']); 
        Route::get('/getAllProductEdit', [MappingImageComponentController::class, 'getAllProductEdit']);
        Route::get('/showHistoryPage', [MappingImageComponentController::class, 'showHistoryPage']);
        Route::get('/cardHistoryPage', [MappingImageComponentController::class, 'cardHistoryPage']);
        Route::put('/activate/{MIC_id}', [MappingImageComponentController::class, 'activate']);
        Route::post('/update', [MappingImageComponentController::class, 'update']);

    });


    Route::group(['prefix' => 'start-stop-job'], function(){
        Route::resource('start-stop-job', JobController::class);
        Route::get('/', [JobController::class, 'index']);
        Route::get('/search/{mesin_nama_mesin}', [JobController::class, 'search']);
        Route::get('/allData/{mesin_nama_mesin}', [JobController::class, 'allData']);
        Route::get('/actual-progress/{anp_id}', [ActivityJobController::class, 'actualProgress']);
        //Start Job diluar
        Route::post('/startjob/{anp_id}', [JobController::class, 'startJob']);
        //Start Job di activity progress
        Route::post('/start-job/{anp_id}', [ActivityJobController::class, 'startJob']);

        Route::post('/start-job/{anp_id}', [ActivityJobController::class, 'startJob']);
        //Pause Job diluar
        Route::post('/pausejob/{anp_id}', [JobController::class, 'pauseJob']);
        //Pause Job di activity progress  
        Route::post('/pause-job/{anp_id}', [ActivityJobController::class, 'pauseJob']); 
        //Start After Pause diluar
        Route::post('/startpause-job/{anp_id}', [JobController::class, 'startafterpauseJob']); 
        //Start After di activity progress
        Route::post('/start-pause-job/{anp_id}', [ActivityJobController::class, 'startafterpauseJob']);
        //Stop Job diluar
        Route::post('/stopjob/{anp_id}', [JobController::class, 'stopJob']);
        //Stop Job di activity progress
        Route::post('/stop-job/{anp_id}', [ActivityJobController::class, 'stopJob']);
        Route::get('/m-cd/{anp_id}', [JobController::class, 'mcd']);
        Route::get('/mcsi/{anp_id}', [JobController::class, 'mcsi']);
        Route::get('/schedule_progress_history/{anp_id}', [JobController::class, 'schedule_progress_history']);

        Route::get('/test', [JobController::class, 'test']);
        Route::get('/getDataJob', [JobController::class, 'getDataJob']);
        Route::get('/choose-machine', [JobController::class, 'choooseMachine']);
    });


    Route::group(['prefix' => 'issue-during-production'], function(){
        Route::get('/{anp_id}', [JobIssueController::class, 'issue_during_production']);
        Route::post('/add-issue/{anp_id}', [JobIssueController::class, 'addIssue']);
        Route::post('finish-issue/{issue_id}/{anp_id}', [JobIssueController::class, 'finishIssue']);
        Route::post('delete-issue/{issue_id}/{anp_id}', [JobIssueController::class, 'deleteIssue']);
    });

    Route::group(['prefix' => 'start-stop-job-bundling'], function(){
        // Route::resource('start-stop-job-bundling', BundlingJobController::class);
        Route::get('/', [BundlingJobController::class, 'index']);
        Route::get('/page-bundling-start/{keybundling?}', [BundlingJobController::class, 'pageBundlingStart']);
        Route::post('/bundling-start/{keybundling?}', [BundlingJobController::class, 'bundlingStart']);
        Route::get('/page-bundling-stop/{bundling_key}', [BundlingJobController::class, 'pageBundlingStop']);
        Route::post('/bundling-stop/{bundling_key}', [BundlingJobController::class, 'bundlingStop']);
        //Pause diluar
        Route::post('/bundling-pause/{bundling_key}', [BundlingJobController::class, 'bundlingPause']);
        //Pause di actual
        Route::post('/bundling-pause-actual/{bundling_key}', [BundlingJobController::class, 'bundling_Pause']);
        //After Pause diluar
        Route::post('/bundling-start-pause/{bundling_key}', [BundlingJobController::class, 'bundlingStartAfterPause']);
        //After Pause di actual
        Route::post('/bundling-start-pause-actual/{bundling_key}', [BundlingJobController::class, 'bundling_StartAfterPause']);

        Route::get('/bundling-actual-progress/{bundling_key}', [BundlingJobController::class, 'bundlingActualProgress']);

        Route::get('/check-kode-bundling/{bundling_key}', [BundlingJobController::class, 'checkKodeBundling']);

    });

    Route::group(['prefix' => 'outstanding-job'], function(){
        Route::resource('outstanding-job', JobGroupLeaderController::class);
        // Route::get('/{mesin_kode_mesin}', [JobGroupLeaderController::class, 'index']);
        Route::get('/', [JobGroupLeaderController::class, 'index']);
        Route::get('/search/{mesin_nama_mesin}', [JobGroupLeaderController::class, 'search']);
        Route::get('/allData/{mesin_nama_mesin}', [JobGroupLeaderController::class, 'allData']);
        Route::get('/actual-progress/{anp_id}', [JobGroupLeaderController::class, 'actualProgress']);
        Route::post('/stop-job/{anp_id}', [JobGroupLeaderController::class, 'stopJob']);
        Route::get('/test', [JobGroupLeaderController::class, 'test']);
        Route::get('/m_moving_machine_one_assign/{anp_id}', [JobGroupLeaderController::class, 'm_moving_machine_one_assign']);
        Route::get('/mesin_breakdown/{mesin_kode_mesin}', [JobGroupLeaderController::class, 'mesin_breakdown']);
        Route::post('/save_mesin_breakdown', [JobGroupLeaderController::class, 'save_mesin_breakdown'])->name('outstanding-job.save_mesin_breakdown');
        Route::post('/save_moving/{anp_id}', [JobGroupLeaderController::class, 'save_moving']);

        Route::get('/getDataJob', [JobGroupLeaderController::class, 'getDataJob']);
        Route::get('/choose-machine', [JobGroupLeaderController::class, 'choooseMachine']);
    });

    Route::group(['prefix' => 'finished-job'], function(){
        Route::resource('finished-job', JobFinishedController::class);
        // Route::get('/{mesin_kode_mesin}', [JobGroupLeaderController::class, 'index']);
        Route::get('/', [JobFinishedController::class, 'index']);
        Route::get('/search/{mesin_nama_mesin}', [JobFinishedController::class, 'search']);
        Route::get('/allData/{mesin_nama_mesin}', [JobFinishedController::class, 'allData']);
        Route::get('/actual-progress/{anp_id}', [JobFinishedController::class, 'actualProgress']);
        Route::get('/mcsi/{anp_id}', [JobFinishedController::class, 'mcsi']);
        Route::get('/schedule_progress_history/{anp_id}', [JobFinishedController::class, 'schedule_progress_history']);
        Route::get('/test', [JobFinishedController::class, 'test']);
        Route::get('/getDataJob', [JobFinishedController::class, 'getDataJob']);
        Route::get('/choose-machine', [JobFinishedController::class, 'choooseMachine']);
        Route::get('/mcd/{anp_id}', [JobFinishedController::class, 'mcd']);
    });

    //Assign
    Route::group(['prefix' => 'assign-machine'], function(){
        Route::resource('assign-machine', AssignMachineController::class);
        Route::get('/', [AssignMachineController::class, 'index']);
        Route::get('m_assign', [AssignMachineController::class, 'index']);
        // Route::post('/save}', [AssignMachineController::class, 'save']);
        Route::post('/save', [AssignMachineController::class, 'save']); // Perbaikan di sini
    });

    Route::group(['prefix' => 'issue-during-production'], function(){
        Route::get('/{anp_id}', [JobIssueController::class, 'issue_during_production']);
        Route::get('show-add-issue/{anp_id}', [JobIssueController::class, 'showAddIssue']);
        Route::post('/add-issue/{anp_id}', [JobIssueController::class, 'addIssue']);
        Route::get('get-finish-issue/{issue_id}/{anp_id}', [JobIssueController::class, 'getFinishIssue']);
        Route::post('finish-issue/{issue_id}/{anp_id}', [JobIssueController::class, 'finishIssue']);
        Route::get('get-delete-issue/{issue_id}/{anp_id}', [JobIssueController::class, 'getDeleteIssue']);
        Route::post('delete-issue/{issue_id}/{anp_id}', [JobIssueController::class, 'deleteIssue']);
    });

    Route::group(['prefix' => 'mapping-pro-customer'], function(){
        Route::get('/', [MappingProCustomerController::class, 'index']);
        Route::get('/get-pro', [MappingProCustomerController::class, 'getPRO']);
        Route::post('/add-mapping-pro', [MappingProCustomerController::class, 'addMappingPRO']);
        Route::get('/get-mapping-id/{mapping_id}', [MappingProCustomerController::class, 'getMappingId']);
        Route::post('/edit-mapping-pro/{mapping_id}', [MappingProCustomerController::class, 'editMappingPRO']);
        Route::post('/delete-mapping-pro/{mapping_id}', [MappingProCustomerController::class, 'deleteMappingPRO']);
    });

});
            

            
