<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class orderSuccessfullNotification extends Notification
{
    use Queueable;
    public $orderNumber;
    public $bookgetter1;
    public $bookgetter2;
    // public $bookgetter3;
   

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($orderNumber,$bookgetter1,$bookgetter2)
    {

        $this->orderNumber = $orderNumber;
        $this->bookgetter1=$bookgetter1;
        $this->bookgetter2=$bookgetter2;
        // $this->bookgetter3=$bookgetter3;
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $bookgetterPrices = json_decode($this->bookgetter2);
        $totalPrice = 0;
        foreach ($bookgetterPrices as $p) {
           $totalPrice += $p->price;
        }

        $bookgetter1 = implode(',', array_map(function($x) { return $x->name; }, json_decode($this->bookgetter1)));
        //$bookgetter2 = implode(',', array_map(function($x) { return $x->price; }, json_decode($this->bookgetter2)));
       // $bookgetter3 = implode(',', array_map(function($x) { return $x->author; }, json_decode($this->bookgetter3)));
        $bookgetter2 = implode(',', array_map(function($x) { return $x->price; }, $bookgetterPrices));
        return (new MailMessage)
                    ->subject("order successfully placed")

                    ->line("You'r order has been placed successfully.. ")
                    ->line('This is the order-id keep it furthur!')
                    ->with($this->orderNumber)
                    ->line("These are the books you ordered")
                    ->with($bookgetter1)
                    // ->with($bookgetter2)
                    ->line("The total summary of your orders is")
                    ->with($totalPrice);
                    
}
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
