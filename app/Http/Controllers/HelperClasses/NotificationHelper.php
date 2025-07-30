<?php

namespace App\Http\Controllers\HelperClasses;   

use App\Events\DashboardActionPerformed;

class NotificationHelper {


    public static  function sendNotification($title = null,$body = null,$data = []){

        $title = 'Action Performed';
        $body = 'An important action has been performed on the dashboard.';
        $data = ['key' => 'test notification from helper class'];

        event(new DashboardActionPerformed($title, $body, $data));
    } 






}