<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
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
        $type = $this->transaction->type;
        $montant = $this->transaction->montant;
        $date = $this->transaction->date;

        return (new MailMessage)
            ->subject('Nouvelle Transaction Effectuée')
            ->line("Une nouvelle transaction de type **{$type}** a été effectuée.")
            ->line("Montant : {$montant} €")
            ->line("Date : {$date}")
            ->line('Merci de vérifier votre compte pour plus de détails.');
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
