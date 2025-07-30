    <?php

    use App\Http\Controllers\Admin\ {
        AuthController, AwardRedeemRequestsController, HomeController, ChatController,
        ServiceController, BookingController, VehicleController, providerPricesController,
        AreaController, workTypeController, transactionController, paymentController,
        MakeController, PermissionController, AdminController, LanguageController,
        RoleController, CityController, SettingController, CenterController,
        PlayerController, CountryController, TalantController, VarController,
        VarChooseController, UserController, CategoryProductsController,
        ProductsController, WomenFootballCategoryController, WomenFootballController,
        UserCoachController, AcademyController, AwardsController, PredictsController,
        TeamController, TeamPlayerController, ClubController, VoteController,
        VoteChoiceController, TranslationController, TeamGroupController
    };
    use Illuminate\Support\Facades\Route;

    Route::group(
        [
            'prefix' => LaravelLocalization::setLocale().'/admin',
            'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
        ], function() {

            Route::get( 'login', [ AuthController::class, 'loginView' ] )->name( 'admin.login' );
            Route::post( 'login', [ AuthController::class, 'postLogin' ] )->name( 'admin.postLogin' );

        }
    );

    Route::group(
        [
            'prefix' => LaravelLocalization::setLocale().'/Dashboard',
            'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'admin' ]
        ], function() {

            Route::group( [ 'middleware' => 'admin', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ], function () {
                Route::get( '/', [ HomeController::class, 'index' ] )->name( 'admin.index' );
                Route::get( 'requests_calenders', [ HomeController::class, 'requests_calenders' ] )->name( 'admin.requests_calenders' );

                Route::get( 'calender', [ HomeController::class, 'calender' ] )->name( 'admin.calender' );

                Route::get( 'logout', [ AuthController::class, 'logout' ] )->name( 'admin.logout' );

                ### admins

                Route::resource( 'admins', AdminController::class );
                Route::get('profile', [AdminController::class,'profile'] )->name('admin.profile');
                Route::post('updateprofile', [AdminController::class,'updateProfile'] )->name('admin.updateprofile');
                Route::get( 'activateAdmin', [ AdminController::class, 'activate' ] )->name( 'admin.active.admin' );

                ### Languages
                Route::resource( 'languages', LanguageController::class);
                Route::get( 'activateLanguage', [ LanguageController::class, 'activate' ] )->name( 'admin.active.language' );

                Route::resource( 'users', UserController::class );
                Route::get( 'activateUser', [ UserController::class, 'activate' ] )->name( 'admin.active.user' );

                ### Permissions and Roles
                Route::resource( 'permissions', PermissionController::class )->names([
                    'index' => 'admin.permissions.index',
                    'create' => 'admin.permissions.create',
                    'store' => 'admin.permissions.store',
                    'edit' => 'admin.permissions.edit',
                    'update' => 'admin.permissions.update',
                    'destroy' => 'admin.permissions.destroy',
                ]);
                Route::resource( 'roles', RoleController::class );
                Route::resource( 'centers', CenterController::class );
                Route::resource( 'countries', CountryController::class );
                Route::resource( 'cities', CityController::class );
                Route::get('getCities/{areaId}', [CityController::class, 'getCities'])->name('admin.getCities');
                Route::resource( 'area', AreaController::class );
                Route::resource( 'settings', SettingController::class );
                Route::resource( 'talants', TalantController::class );
                Route::post('update-talant-status', [TalantController::class, 'updateStatus'])->name('admin.updateTalantStatus');
                Route::post('toggle-featured', [TalantController::class, 'toggleFeatured'])->name('admin.talants.toggle-featured');
                Route::resource( 'vars', VarController::class );
                Route::resource( 'varChooses', VarChooseController::class );
                Route::resource( 'categoryProducts', CategoryProductsController::class );
                Route::resource( 'products', ProductsController::class );
                Route::post('update-product-status', [ProductsController::class, 'updateStatus'])->name('admin.updateProductStatus');

                Route::resource( 'women-football-categories', WomenFootballCategoryController::class );
                Route::resource( 'women-football', WomenFootballController::class );
                Route::resource( 'user-coach', UserCoachController::class );
                Route::resource( 'academy', AcademyController::class );
                Route::resource( 'awards', AwardsController::class );
                Route::resource( 'award-redeem-requests', AwardRedeemRequestsController::class );
                Route::post('award-redeem-requests/update-status', [AwardRedeemRequestsController::class, 'updateStatus'])->name('admin.award-redeem-requests.update-status');

                Route::resource( 'predicts', PredictsController::class );
                Route::resource( 'clubs', ClubController::class );
                Route::resource( 'players', PlayerController::class );

                Route::resource( 'teams', TeamController::class );
                Route::resource( 'team-players', TeamPlayerController::class );

                Route::resource( 'votes', VoteController::class );
                Route::resource( 'vote-choice', VoteChoiceController::class);
                Route::resource('team_groups', TeamGroupController::class);

                Route::post('update-Team-group-status', [TeamGroupController::class, 'updateStatus'])->name('admin.updateTeamGroup');

                // Chat Routes
                Route::prefix('chat')->group(function () {
                    Route::get('/', [ChatController::class, 'index'])->name('admin.chat.index');
                    Route::get('/create', [ChatController::class, 'create'])->name('admin.chat.create');
                    Route::get('/{user}', [ChatController::class, 'show'])->name('admin.chat.show');
                    Route::get('/{user}/listen', [ChatController::class, 'listen'])->name('admin.chat.listen');
                    Route::post('/send', [ChatController::class, 'sendMessage'])->name('admin.chat.send');
                });

                Route::group(['prefix'=>'translation'],function(){
                    Route::match(['get','post'],'/', [TranslationController::class,'index'])->name('translation.index');
                    Route::post('update', [TranslationController::class,'update'])->name('translation.update');
                });
                // Services

                // payment

            }
        );

    }
    );
