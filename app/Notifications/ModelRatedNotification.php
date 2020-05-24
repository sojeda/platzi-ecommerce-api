<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModelRatedNotification extends Notification
{
    use Queueable;

    private string $rateableName;
    private float $score;
    private string $qualifierName;

    public function __construct(
        string $qualifierName,
        string $rateableName,
        float $score
    )
    {
        $this->qualifierName = $qualifierName;
        $this->rateableName = $rateableName;
        $this->score = $score;
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

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line("¡{$this->qualifierName} ha calificado tu producto
                    {$this->rateableName}! con {$this->score}
                    estrellas");
    }
}
