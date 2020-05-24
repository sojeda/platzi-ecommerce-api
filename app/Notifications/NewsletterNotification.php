<?php

namespace App\Notifications;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterNotification extends Notification
{
    use Queueable;

    /**
     * @var Product[]
     */
    private array $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage;
        $mailMessage->line('Estos son los productos con mas rating en la ultima semana');

        foreach ($this->products as $product) {
            $mailMessage->line($product['name']);
        }

        $mailMessage->line('Thank you for using our application!');

        return $mailMessage;
    }

}
