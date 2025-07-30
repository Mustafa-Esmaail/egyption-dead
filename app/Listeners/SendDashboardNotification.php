<?php

namespace App\Listeners;

use App\Events\DashboardActionPerformed;
use App\Models\User;
use App\Notifications\PushNotification;

class SendDashboardNotification
{
    public function handle(DashboardActionPerformed $event)
    {
        // Get users (filter based on your logic)

        $user = userApi()->user();
        // $users = User::whereNotNull('fcm_token')->get();

        // foreach ($users as $user) {
            $user->notify(new PushNotification(
                $event->title,
                $event->body,
                $event->data
            ));
        // }
    }
}
