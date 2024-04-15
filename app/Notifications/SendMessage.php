<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendMessage extends Notification
{
    use Queueable;

    private $message;
    private $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, string $url)
    {
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'id' => $this->id,
            'message' => $this->message,
            'url' => $this->url,
        ];

//        if ($notifiable->fcm_token){
//            $this->send_firebase_notification($this->message, $this->url, $notifiable->fcm_token);
//        }

        return $data;
    }

    private function send_firebase_notification($message, $url, $token)
    {
        $firebaseToken = [$token];

        $SERVER_API_KEY = 'AAAAAqqjtGY:APA91bGqBtuYddBnAnliS0HOL1PBuf8cbWgdkNWMpOJCMFuWPVq2nCZoLTZIcxDQMJf8OwAsWRYYan5BpXC6qFdoIpyWW91OCUOu-eDOggSmBv-Oi5ebT2FWdSRid7OV1iP02_9rGftS';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $message,
                "body" => '',
//                "image" => 'https://mpsystem.ir/assets/media/image/logo.png',
//                "content_available" => true,
                "priority" => "high",
            ],
            "webpush" => [
                "headers" => [
                    "image" => "https://mpsystem.ir/assets/media/image/logo.png",
                ]
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }
}
