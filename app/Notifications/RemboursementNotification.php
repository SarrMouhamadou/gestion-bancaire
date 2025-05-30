<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemboursementNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $remboursement;
    public function __construct($remboursement)
    {
        $this->remboursement = $remboursement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $montant = $this->remboursement->montant;
        $date = $this->remboursement->date;
        $creditId = $this->remboursement->credit_id;

        return (new MailMessage)
            ->subject('Nouveau Remboursement Enregistré')
            ->line("Un remboursement a été enregistré pour votre crédit #{$creditId}.")
            ->line("Montant : {$montant} €")
            ->line("Date : {$date}")
            ->line('Merci de vérifier votre crédit pour plus de détails.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
