<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeTerminee extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $commande) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre commande est prête')
            ->line("Votre commande n°{$this->commande->id} a été terminée.")
            ->action('Voir la commande', url('/commandes/' . $this->commande->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Votre commande n°{$this->commande->id} a été terminée.",
            'commande_id' => $this->commande->id,
        ];
    }
}
