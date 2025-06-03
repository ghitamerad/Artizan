<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DevisReponduParClientNotification extends Notification
{
    use Queueable;

    public $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // ou ['database'] si tu ne veux pas d’email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Réponse au devis')
            ->line("Le client a {$this->devis->statut} le devis #{$this->devis->id}.")
            ->action('Voir le devis', route('devis.show', $this->devis));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Le client a {$this->devis->statut} le devis #{$this->devis->id}.",
            'devis_id' => $this->devis->id,
        ];
    }
}
