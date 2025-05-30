<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketSupportNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $ticket;
    public function __construct($ticket)
    {
         $this->ticket = $ticket;
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
        $sujet = $this->ticket->sujet;
        $statut = $this->ticket->statut;

        return (new MailMessage)
            ->subject('Mise à Jour de Votre Ticket de Support')
            ->line("Votre ticket de support intitulé **{$sujet}** a été mis à jour.")
            ->line("Nouveau statut : {$statut}")
            ->line('Veuillez consulter votre espace client pour plus de détails.');
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
