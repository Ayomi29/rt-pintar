<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\OptionsPriorities;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;

class PushNotification extends Model
{
    use HasFactory;

    protected $title;
    protected $body;
    protected $to;
    protected $screen;

    protected $data;

    protected $sound;

    protected $image;

    protected $icon;

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function __construct($title, $body, $image)
    {
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
    }

    /**
     * Set property to
     *
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Redirect to screen in mobile apps
     *
     * @param string $screen
     */
    public function redirectTo($screen)
    {
        $this->screen = $screen;
        return $this;
    }

    /**
     * Send Notification
     *
     * @return $this
     */
    public function send()
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $optionBuilder->setPriority(OptionsPriorities::high);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle($this->title);
        $notificationBuilder->setBody($this->body);
        // $notificationBuilder->setImage($this->image);
        $notificationBuilder->setSound("rush.mp3");
        $notificationBuilder->setClickAction("FLUTTER_NOTIFICATION_CLICK");
        $dataBuilder = new PayloadDataBuilder();

        if ($this->screen) {
            $dataBuilder->addData(['screen' =>  $this->screen]);
        }
        $dataBuilder->addData($this->data);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($this->to, $option, $notification, $data);

        if (count($downstreamResponse->tokensToRetry())) {
            $this->to($downstreamResponse->tokensToRetry());
            $this->send();
        }

        $response["number_success"] = $downstreamResponse->numberSuccess();
        $response["number_failure"] = $downstreamResponse->numberFailure();
        $response["number_modification"] = $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $response["tokens_to_delete"] = $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $response["tokens_to_modify"] = $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $response["tokens_to_retry"] = $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $response["tokens_with_error"] = $downstreamResponse->tokensWithError();

        return $response;
    }
}
