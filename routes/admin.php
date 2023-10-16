<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ThematicSectionController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\DepartmentTypeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeePositionController;
use App\Http\Controllers\Admin\EmployeeAttributeController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RightsController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\LogController;

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

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth', 'index')->middleware('guest')->name('AuthIndex');
    Route::get('/auth/redirect', 'redirect')->name('AuthRedirect');
    Route::get('/auth/callback', 'callback')->name('AuthCallback');
    Route::get('/auth/silent-callback', 'silentCallback')->name('AuthSilentCallback');
    Route::get('/auth/logout', 'logout')->middleware('auth')->name('AuthLogout');
});

//Route::get('/newsupload', [HomeController::class, 'newsupload'])
//    ->name('newsupload');
//
//Route::post('/newsstore', [HomeController::class, 'newsstore'])
//    ->name('newsstore');

Route::middleware('auth')->group(function() {
    Route::get('/', [HomeController::class, 'index'])
        ->name('HomeIndex');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('ProfileIndex');
        Route::post('/profile/sessions', 'closeSessions')->name('ProfileCloseSessions');
    });

    Route::middleware('role:user')->group(function() {

        Route::controller(MediaController::class)->group(function () {
            Route::get('/media', 'index')->middleware('role_or_permission:admin|media_view')->name('MediaIndex');
            Route::get('/media/manager', 'manager')->middleware('role_or_permission:admin|media_view')->name('MediaManager');
            Route::post('/media', 'store')->middleware('role_or_permission:admin|media_create')->name('MediaStore');
            Route::post('/media/upload', 'upload')->middleware('role_or_permission:admin|media_create')->name('MediaUpload');
            Route::put('/media/{id}', 'update')->middleware('role_or_permission:admin|media_edit')->name('MediaUpdate');
            Route::post('/media/{id}/restore', 'restore')->middleware('role_or_permission:admin|media_restore')->name('MediaRestore');
            Route::post('/media/restore', 'restoreMultiple')->middleware('role_or_permission:admin|media_restore')->name('MediaRestoreMultiple');
            Route::delete('/media/{id}', 'destroy')->middleware('role_or_permission:admin|media_delete')->name('MediaDestroy');
            Route::post('/media/destroy', 'destroyMultiple')->middleware('role_or_permission:admin|media_delete')->name('MediaDestroyMultiple');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->middleware('role_or_permission:admin|category_view')->name('CategoryIndex');
            Route::post('/categories', 'store')->middleware('role_or_permission:admin|category_create')->name('CategoryStore');
            Route::get('/categories/{id}/edit', 'edit')->middleware('role_or_permission:admin|category_edit')->name('CategoryEdit');
            Route::put('/categories/{id}', 'update')->middleware('role_or_permission:admin|category_edit')->name('CategoryUpdate');
            Route::post('/categories/{id}/restore', 'restore')->middleware('role_or_permission:admin|category_restore')->name('CategoryRestore');
            Route::post('/categories/{id}/visible', 'visible')->middleware('role_or_permission:admin|category_hiding')->name('CategoryVisible');
            Route::delete('/categories/{id}', 'destroy')->middleware('role_or_permission:admin|category_delete')->name('CategoryDestroy');
        });

        Route::controller(ArticleController::class)->group(function () {
            Route::get('/articles', 'index')->middleware('role_or_permission:admin|article_view')->name('ArticleIndex');
            Route::get('/articles/create', 'create')->middleware('role_or_permission:admin|article_create')->name('ArticleCreate');
            Route::post('/articles', 'store')->middleware('role_or_permission:admin|article_create')->name('ArticleStore');
            Route::get('/articles/{id}/edit', 'edit')->middleware('model.permission:article')->name('ArticleEdit');
            Route::put('/articles/{id}', 'update')->middleware('model.permission:article')->name('ArticleUpdate');
            Route::post('/articles/{id}/restore', 'restore')->middleware('role_or_permission:admin|article_restore')->name('ArticleRestore');
            Route::post('/articles/{id}/visible', 'visible')->middleware('role_or_permission:admin|article_hiding')->name('ArticleVisible');
            Route::delete('/articles/{id}', 'destroy')->middleware('role_or_permission:admin|article_delete')->name('ArticleDestroy');
        });

        Route::controller(TagController::class)->group(function () {
            Route::get('/tags', 'index')->middleware('role_or_permission:admin|tag_view')->name('TagIndex');
            Route::post('/tags', 'store')->middleware('role_or_permission:admin|tag_create')->name('TagStore');
            Route::get('/tags/{id}/edit', 'edit')->middleware('role_or_permission:admin|tag_edit')->name('TagEdit');
            Route::put('/tags/{id}', 'update')->middleware('role_or_permission:admin|tag_edit')->name('TagUpdate');
            Route::post('/tags/{id}/restore', 'restore')->middleware('role_or_permission:admin|tag_restore')->name('TagRestore');
            Route::delete('/tags/{id}', 'destroy')->middleware('role_or_permission:admin|tag_delete')->name('TagDestroy');
        });

        Route::controller(ThematicSectionController::class)->group(function () {
            Route::get('/thematic-sections', 'index')->middleware('role_or_permission:admin|thematic-section_view')->name('ThematicSectionIndex');
            Route::post('/thematic-sections', 'store')->middleware('role_or_permission:admin|thematic-section_create')->name('ThematicSectionStore');
            Route::get('/thematic-sections/{id}/edit', 'edit')->middleware('role_or_permission:admin|thematic-section_edit')->name('ThematicSectionEdit');
            Route::put('/thematic-sections/{id}', 'update')->middleware('role_or_permission:admin|thematic-section_edit')->name('ThematicSectionUpdate');
            Route::post('/thematic-sections/{id}/restore', 'restore')->middleware('role_or_permission:admin|thematic-section_restore')->name('ThematicSectionRestore');
            Route::delete('/thematic-sections/{id}', 'destroy')->middleware('role_or_permission:admin|thematic-section_delete')->name('ThematicSectionDestroy');
            Route::get('/thematic-sections/all', 'all')->name('ThematicSectionAllIndex');
            Route::get('/thematic-sections/get', 'get')->name('ThematicSectionGetIndex');
        });

        Route::controller(NewsController::class)->group(function () {
            Route::get('/news', 'index')->middleware('role_or_permission:admin|news_view')->name('NewsIndex');
            Route::get('/news/create', 'create')->middleware('role_or_permission:admin|news_create')->name('NewsCreate');
            Route::post('/news', 'store')->middleware('role_or_permission:admin|news_create')->name('NewsStore');
            Route::get('/news/{id}/edit', 'edit')->middleware('model.permission:news')->name('NewsEdit');
            Route::put('/news/{id}', 'update')->middleware('model.permission:news')->name('NewsUpdate');
            Route::post('/news/{id}/restore', 'restore')->middleware('role_or_permission:admin|news_restore')->name('NewsRestore');
            Route::post('/news/{id}/visible', 'visible')->middleware('role_or_permission:admin|news_hiding')->name('NewsVisible');
            Route::delete('/news/{id}', 'destroy')->middleware('role_or_permission:admin|news_delete')->name('NewsDestroy');
            Route::get('/news/thematic', 'thematic')->name('NewsTakeThematic');
        });

        Route::controller(EventController::class)->group(function () {
            Route::get('/events', 'index')->middleware('role_or_permission:admin|event_view')->name('EventIndex');
            Route::get('/events/create', 'create')->middleware('role_or_permission:admin|event_create')->name('EventCreate');
            Route::post('/events', 'store')->middleware('role_or_permission:admin|event_create')->name('EventStore');
            Route::get('/events/{id}/edit', 'edit')->middleware('model.permission:event')->name('EventEdit');
            Route::put('/events/{id}', 'update')->middleware('model.permission:event')->name('EventUpdate');
            Route::post('/events/{id}/restore', 'restore')->middleware('role_or_permission:admin|event_restore')->name('EventRestore');
            Route::post('/events/{id}/visible', 'visible')->middleware('role_or_permission:admin|event_hiding')->name('EventVisible');
            Route::delete('/events/{id}', 'destroy')->middleware('role_or_permission:admin|event_delete')->name('EventDestroy');
        });

        Route::controller(DepartmentTypeController::class)->group(function () {
            Route::get('/department-types', 'index')->middleware('role_or_permission:admin|personal_view')->name('DepartmentTypeIndex');
            Route::post('/department-types', 'store')->middleware('role_or_permission:admin|personal_create')->name('DepartmentTypeStore');
            Route::get('/department-types/{id}/edit', 'edit')->middleware('role_or_permission:admin|personal_edit')->name('DepartmentTypeEdit');
            Route::put('/department-types/{id}', 'update')->middleware('role_or_permission:admin|personal_edit')->name('DepartmentTypeUpdate');
            Route::delete('/department-types/{id}', 'destroy')->middleware('role_or_permission:admin|personal_delete')->name('DepartmentTypeDestroy');
        });

        Route::controller(DepartmentController::class)->group(function () {
            Route::get('/departments', 'index')->middleware('role_or_permission:admin|personal_view')->name('DepartmentIndex');
            Route::get('/departments/create', 'create')->middleware('role_or_permission:admin|personal_create')->name('DepartmentCreate');
            Route::post('/departments', 'store')->middleware('role_or_permission:admin|personal_create')->name('DepartmentStore');
            Route::get('/departments/{id}/edit', 'edit')->middleware('role_or_permission:admin|personal_edit')->name('DepartmentEdit');
            Route::put('/departments/{id}', 'update')->middleware('role_or_permission:admin|personal_edit')->name('DepartmentUpdate');
            Route::delete('/departments/{id}', 'destroy')->middleware('role_or_permission:admin|personal_delete')->name('DepartmentDestroy');
        });

        Route::controller(EmployeeController::class)->group(function () {
            Route::get('/employees', 'index')->middleware('role_or_permission:admin|personal_view')->name('EmployeeIndex');
            Route::get('/employees/create', 'create')->middleware('role_or_permission:admin|personal_create')->name('EmployeeCreate');
            Route::post('/employees', 'store')->middleware('role_or_permission:admin|personal_create')->name('EmployeeStore');
            Route::get('/employees/{id}/edit', 'edit')->middleware('role_or_permission:admin|personal_edit')->name('EmployeeEdit');
            Route::put('/employees/{id}', 'update')->middleware('role_or_permission:admin|personal_edit')->name('EmployeeUpdate');
            Route::delete('/employees/{id}', 'destroy')->middleware('role_or_permission:admin|personal_delete')->name('EmployeeDestroy');
        });

        Route::controller(EmployeePositionController::class)->group(function () {
            Route::get('/employees/positions', 'index')->name('EmployeePositionIndex');
            Route::get('/employees/card', 'show')->name('EmployeeShow');
            Route::post('/employees/position', 'store')->middleware('role_or_permission:admin|personal_edit')->name('EmployeePositionStore');
            Route::put('/employees/position/{id}', 'update')->middleware('role_or_permission:admin|personal_edit')->name('EmployeePositionUpdate');
            Route::delete('/employees/{id}/position', 'destroy')->middleware('role_or_permission:admin|personal_edit')->name('EmployeePositionDestroy');
        });

        Route::controller(EmployeeAttributeController::class)->group(function () {
            Route::post('/employees/attribute', 'store')->middleware('role_or_permission:admin|personal_edit')->name('EmployeeAttributeStore');
            Route::put('/employees/attribute/{id}', 'update')->middleware('role_or_permission:admin|personal_edit')->name('EmployeeAttributeUpdate');
            Route::delete('/employees/{id}/attribute', 'destroy')->middleware('role_or_permission:admin|personal_edit')->name('EmployeeAttributeDestroy');
        });

        Route::controller(AddressController::class)->group(function () {
            Route::get('/addresses', 'index')->middleware('role_or_permission:admin|personal_view')->name('AddressIndex');
            Route::post('/addresses', 'store')->middleware('role_or_permission:admin|personal_create')->name('AddressStore');
            Route::get('/addresses/{id}/edit', 'edit')->middleware('role_or_permission:admin|personal_edit')->name('AddressEdit');
            Route::put('/addresses/{id}', 'update')->middleware('role_or_permission:admin|personal_edit')->name('AddressUpdate');
            Route::delete('/addresses/{id}', 'destroy')->middleware('role_or_permission:admin|personal_delete')->name('AddressDestroy');
        });

        Route::controller(CommentController::class)->group(function () {
            Route::get('/comments', 'index')->middleware('role_or_permission:admin|comment_view')->name('CommentIndex');
            Route::get('/comments/{id}/edit', 'edit')->middleware('role_or_permission:admin|comment_edit')->name('CommentEdit');
            Route::put('/comments/{id}', 'update')->middleware('role_or_permission:admin|comment_edit')->name('CommentUpdate');
            Route::post('/comments/{id}/restore', 'restore')->middleware('role_or_permission:admin|comment_restore')->name('CommentRestore');
            Route::delete('/comments/{id}', 'destroy')->middleware('role_or_permission:admin|comment_delete')->name('CommentDestroy');
        });

        Route::controller(SliderController::class)->group(function () {
            Route::get('/sliders', 'index')->middleware('role_or_permission:admin|slider_view')->name('SliderIndex');
            Route::post('/sliders', 'store')->middleware('role_or_permission:admin|slider_create')->name('SliderStore');
            Route::get('/sliders/{id}/edit', 'edit')->middleware('role_or_permission:admin|slider_edit')->name('SliderEdit');
            Route::put('/sliders/{id}', 'update')->middleware('role_or_permission:admin|slider_edit')->name('SliderUpdate');
            Route::delete('/sliders/{id}', 'destroy')->middleware('role_or_permission:admin|slider_delete')->name('SliderDestroy');

            Route::post('/sliders/{id}/slide', 'storeSlide')->middleware('role_or_permission:admin|slider_create')->name('SlideStore');
            Route::put('/sliders/slide/{id}', 'updateSlide')->middleware('role_or_permission:admin|slider_edit')->name('SlideUpdate');
            Route::delete('/sliders/slide/{id}', 'destroySlide')->middleware('role_or_permission:admin|slider_delete')->name('SlideDestroy');
        });

        Route::controller(UserController::class)->middleware('role:admin')->group(function () {
            Route::get('/users', 'index')->name('UserIndex');
            Route::get('/users/create', 'create')->name('UserCreate');
            Route::post('/users', 'store')->name('UserStore');
            Route::get('/users/{id}/edit', 'edit')->name('UserEdit');
            Route::put('/users/{id}', 'update')->name('UserUpdate');
            Route::post('/users/{id}/restore', 'restore')->name('UserRestore');
            Route::delete('/users/{id}', 'destroy')->name('UserDestroy');
        });

        Route::controller(RightsController::class)->middleware('role:admin')->group(function () {
            Route::get('/settings/rights', 'index')->name('RightsIndex');

            Route::post('/settings/rights/role', 'storeRole')->name('RightsRoleStore');
            Route::post('/settings/rights/role/attach', 'attachRole')->name('RightsRoleAttach');
            Route::get('/settings/rights/role/{id}/edit', 'editRole')->name('RightsRoleEdit');
            Route::put('/settings/rights/role/{id}', 'updateRole')->name('RightsRoleUpdate');
            Route::delete('/settings/rights/role/{id}', 'destroyRole')->name('RightsRoleDestroy');

            Route::post('/settings/rights/permission', 'storePermission')->name('RightsPermissionStore');
            Route::post('/settings/rights/permission/attach', 'attachPermission')->name('RightsPermissionAttach');
            Route::get('/settings/rights/permission/{id}/edit', 'editPermission')->name('RightsPermissionEdit');
            Route::put('/settings/rights/permission/{id}', 'updatePermission')->name('RightsPermissionUpdate');
            Route::delete('/settings/rights/permission/{id}', 'destroyPermission')->name('RightsPermissionDestroy');

            Route::post('/settings/rights/individual/attach', 'individualAttach')->name('RightsIndividualAttach');
            Route::post('/settings/rights/individual/detach', 'individualDetach')->name('RightsIndividualDetach');
            Route::post('/settings/rights/individual/models', 'individualModels')->name('RightsIndividualModels');
            Route::get('/settings/rights/individual/users', 'individualUsers')->name('RightsIndividualUsers');
        });

        Route::controller(SettingController::class)->middleware('role:admin')->group(function () {
            Route::get('/settings', 'index')->name('SettingIndex');
            Route::get('/settings/php', 'php')->name('SettingPHP');
            Route::get('/settings/{path}', 'show')->name('SettingShow');
            Route::post('/settings', 'store')->name('SettingStore');
            Route::put('/settings/update', 'update')->name('SettingUpdate');
            Route::delete('/settings/{id}', 'destroy')->name('SettingDestroy');
        });

        Route::get('/logs', [LogController::class, 'index'])
            ->middleware('role:admin')
            ->name('LogIndex');
    });
});
