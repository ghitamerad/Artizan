<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DevisProposeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $devis;

    public function __construct($devis)
    {
        $this->devis = $devis;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Un devis vous a été proposé')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Un tarif vous a été proposé pour le devis n°' . $this->devis->id)
            ->line('Tarif : ' . number_format($this->devis->tarif, 2, ',', ' ') . ' DA')
            ->action('Voir le devis', route('mes-devis.show', $this->devis->id)) // adapte cette route
            ->line('Veuillez accepter ou refuser ce devis depuis votre espace client.');
    }

    public function toArray($notifiable)
    {
        return [
            'devis_id' => $this->devis->id,
            'message' => 'Un tarif vous a été proposé pour votre demande de devis.',
        ];
    }
}
