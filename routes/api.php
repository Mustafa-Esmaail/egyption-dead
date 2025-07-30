<?php

use App\Http\Controllers\Api\User\AcademyController;
use App\Http\Controllers\Api\User\Auth\AuthController;
use App\Http\Controllers\Api\User\AwardsController;
use App\Http\Controllers\Api\User\DynamicSettingsController;
use App\Http\Controllers\Api\User\LocationController;
use App\Http\Controllers\Api\User\MarketPlaceController;
use App\Http\Controllers\Api\User\NotificationController;
use App\Http\Controllers\Api\User\PlanController;
use App\Http\Controllers\Api\User\SettingController;
// use App\Http\Controllers\Api\User\Auth\SocialLoginController;
use App\Http\Controllers\Api\User\TeamPlayerController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserTalantController;
use App\Http\Controllers\Api\User\VarController;
use App\Http\Controllers\Api\User\VoteController;
use App\Http\Controllers\Api\User\WomentFootballController;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return 'sssss';
});
// Route::group(['prefix' => 'auth'], function () {

// });

Route::group(['prefix' => 'user/auth'], function () {

    Route::post('login', [\App\Http\Controllers\Api\User\Auth\AuthController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\Api\User\Auth\AuthController::class, 'register']);
    Route::post('forget_password', [\App\Http\Controllers\Api\User\Auth\AuthController::class, 'forgetPassword']);
    Route::post('validate_otp', [\App\Http\Controllers\Api\User\Auth\AuthController::class, 'validateOtp']);
    Route::post('update_password', [\App\Http\Controllers\Api\User\Auth\AuthController::class, 'updatePassword']);
    Route::post('social-login', [\App\Http\Controllers\Api\User\Auth\SocialLoginController::class, 'socialLogin']);
    Route::post('social-login-socialte', [\App\Http\Controllers\Api\User\Auth\SocialLoginController::class, 'socialLogin']);

    // Social Authentication Routes
    // Route::get('{provider}/redirect', [SocialAuthController::class, 'redirectToProvider']);
    // Route::get('{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

    Route::middleware('ApiLogin')->group(function () {

        Route::get('logout', [AuthController::class, 'logout']);

        Route::get('delete_acount', [\App\Http\Controllers\Api\User\Auth\AuthController::class, 'deleteAcount']);

        //*Academies
        Route::get('get_academies', [AcademyController::class, 'get_academies']);
        Route::get('toggle_subscrib_in_academy/{academyId}', [AcademyController::class, 'toggle_subscrib_in_academy']);
        Route::get('toggle_liked_academy/{academyId}', [AcademyController::class, 'toggle_liked_academy']);
        Route::get('academy_details/{academyId}', [AcademyController::class, 'academy_details']);

        //*Talants
        Route::match(['get', 'post'], 'get_talants', [UserTalantController::class, 'get_talants']);
        Route::get('toggle_liked_talant/{talantId}', [UserTalantController::class, 'toggle_liked_talant']);
        Route::get('increment_talant_share/{talantId}', [UserTalantController::class, 'increment_talant_share']);
        Route::post('add_comment_on_talant', [UserTalantController::class, 'add_comment_on_talant']);
        Route::post('edit_talant_comment', [UserTalantController::class, 'edit_talant_comment']);
        Route::get('delete_talant_comment/{commentid}', [UserTalantController::class, 'delete_talant_comment']);
        Route::post('upload_talant_video', [UserTalantController::class, 'store_talant']);
        Route::post('update_talant', [UserTalantController::class, 'update_talant']);
        Route::post('add_rate', [UserTalantController::class, 'add_rate']);
        Route::post('delete_talant_video', [UserTalantController::class, 'delete_talant_video']);
        Route::get('get_user_talant', [UserTalantController::class, 'get_user_talant']);
        Route::get('user_liked_talant', [UserTalantController::class, 'user_liked_talant']);
        Route::get('get_featured_talants', [UserTalantController::class, 'get_featured_talants']);
        Route::get('user_favourite_talant', [UserTalantController::class, 'user_favourite_talant']);
        Route::get('toggle_fav_talant/{talantId}', [UserTalantController::class, 'toggle_fav_talant']);

        //*teams

        Route::get('get_teams', [TeamPlayerController::class, 'get_teams']);
        Route::get('get_players_of_team/{teamId}', [TeamPlayerController::class, 'get_players_of_team']);
        Route::post('store_player_postion', [TeamPlayerController::class, 'store_player_postion']);
        // Route::post('add_team_group', [TeamPlayerController::class,'add_team_group']);
        Route::post('add_user_team', [TeamPlayerController::class, 'add_user_team']);
        // Route::post('update_team_group', [TeamPlayerController::class,'update_team_group']);
        Route::get('get_team_groups/{teamId}', [TeamPlayerController::class, 'get_team_groups']);
        Route::post('delete_user_team', [TeamPlayerController::class, 'delete_user_team']);
        Route::post('delete_team_group', [TeamPlayerController::class, 'delete_team_group']);
        Route::get('get_user_team_groups/{teamId}', [TeamPlayerController::class, 'get_user_team_groups']);
        Route::post('add_user_group', [TeamPlayerController::class, 'add_user_group']);

        // *user
        Route::get('add_user_favourite_team/{teamId}', [UserController::class, 'add_user_favourite_team']);
        Route::get('add_user_favourite_player/{playerId}', [UserController::class, 'add_user_favourite_player']);
        Route::get('get_user_favourite_team', [UserController::class, 'get_user_favourite_team']);
        Route::get('get_user_favourite_player', [UserController::class, 'get_user_favourite_player']);
        Route::post('update_profile', [UserController::class, 'update_profile']);
        Route::get('my_profile', [UserController::class, 'profile']);

        // *women football
        Route::get('get_woment_football_posts/{categoryId}', [WomentFootballController::class, 'get_woment_football_posts']);
        Route::get('get_women_football_Category', [WomentFootballController::class, 'get_women_football_Category']);
        Route::get('toggle_liked_women_football_post/{womenFootballId}', [WomentFootballController::class, 'toggle_liked_women_football_post']);
        Route::get('get_woment_football_posts_details/{postId}', [WomentFootballController::class, 'get_woment_football_posts_details']);

        Route::get('get_product_categories', [MarketPlaceController::class, 'get_product_categories']);
        Route::get('get_products/{productCategory?}', [MarketPlaceController::class, 'get_products']);
        Route::get('get_all_products', [MarketPlaceController::class, 'get_all_products']);
        Route::post('store_product', [MarketPlaceController::class, 'store_product']);
        Route::get('delete_product/{productId}', [MarketPlaceController::class, 'deleteProduct']);
        Route::get('show_product/{productId}', [MarketPlaceController::class, 'show_product']);
        Route::post('update_product', [MarketPlaceController::class, 'update_product']);
        Route::get('get_user_products', [MarketPlaceController::class, 'get_user_products']);

        // * votes
        Route::get('get_votes', [VoteController::class, 'get_votes']);
        Route::post('store_vote_choice', [VoteController::class, 'store_vote_choice']);

        // * vars
        Route::get('get_vars', [VarController::class, 'get_vars']);
        Route::post('store_var_choice', [VarController::class, 'store_var_choice']);
        // * locations
        Route::get('get_countries', [LocationController::class, 'get_countries']);
        Route::get('get_cities/{countryId}', [LocationController::class, 'get_cities']);
        Route::get('get_areas/{cityId}', [LocationController::class, 'get_areas']);
        // * awards
        Route::get('get_awards', [AwardsController::class, 'get_awards']);
        // * award redeem
        Route::post('/awards/redeem', [App\Http\Controllers\Api\User\AwardRedeemController::class, 'redeem']);
        Route::get('/awards/my-requests', [App\Http\Controllers\Api\User\AwardRedeemController::class, 'myRequests']);

        // * plans
        Route::post('store_user_plan', [PlanController::class, 'store_plan']);
        Route::get('get_user_plan', [PlanController::class, 'get_user_plan']);
        Route::get('get_plans', [PlanController::class, 'get_plans']);
        Route::post('add_comment_to_plan', [PlanController::class, 'add_comment_to_plan']);
        Route::post('edit_plan_comment', [PlanController::class, 'edit_plan_comment']);
        Route::get('toggle_liked_plan/{planUserId}', [PlanController::class, 'toggle_liked_plan']);

        // *settings
        Route::get('get_settings', [SettingController::class, 'get_settings']);
        Route::get('get_system_points', [DynamicSettingsController::class, 'index']);

        // *notification
        Route::get('get_notifications', [NotificationController::class, 'get_notification']);
        Route::get('get_user_points_transction', [NotificationController::class, 'get_user_points_transction']);
        Route::get('get_firebase_notification', [NotificationController::class, 'get_firebase_notification']);

        // Points History
        Route::get('my-points', [\App\Http\Controllers\Api\User\PointsHistoryController::class, 'myPoints']);

        // FCM Token Management
        Route::post('update-fcm-token', [AuthController::class, 'updateFcmToken']);

        // Chat Routes

        Route::post('/chat/send', [App\Http\Controllers\Api\User\ChatController::class, 'sendMessage']);
        Route::get('/chat/user-messages', [App\Http\Controllers\Api\User\ChatController::class, 'getUserMessages']);
        Route::get('/chat/admin-messages', [App\Http\Controllers\Api\User\ChatController::class, 'getAdminMessages']);
        Route::post('/chat/mark-read', [App\Http\Controllers\Api\User\ChatController::class, 'markAsRead']);
        Route::get('/chat/unread-count', [App\Http\Controllers\Api\User\ChatController::class, 'getUnreadCount']);
        Route::get('/chat/last-message', [App\Http\Controllers\Api\User\ChatController::class, 'getLastMessage']);
    });
});

// Firebase Notification Routes
Route::prefix('notifications')->group(function () {
    Route::post('/send-to-user', [App\Http\Controllers\Admin\FirebaseNotificationController::class, 'sendPushNotification']);
    Route::post('/send-to-topic', [App\Http\Controllers\Admin\FirebaseNotificationController::class, 'sendToTopic']);
    Route::post('/subscribe-to-topic', [App\Http\Controllers\Admin\FirebaseNotificationController::class, 'subscribeToTopic']);
    Route::post('/send-to-me', [App\Http\Controllers\Admin\FirebaseNotificationController::class, 'sendToAuthenticatedUser'])->middleware('ApiLogin');
    Route::post('/test-notification', [App\Http\Controllers\Admin\FirebaseNotificationController::class, 'testNotification']);
});
