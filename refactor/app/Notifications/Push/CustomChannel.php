<?php

namespace App\Notifications\Push;


use Illuminate\Notifications\Notification;

class CustomChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notification->toPush($notifiable);

        // Send notification to the $notifiable instance...
    }
}
