<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class DevisProposeNotification extends Notification
{
    use Queueable;

    protected $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    public function via($notifiable)
    {
        return ['database','mail']; // ou ['database', 'mail'] si tu veux l'email aussi
    }

    public function toDatabase($notifiable)
    {
        return [
            'devis_id' => $this->devis->id,
            'message' => "Le responsable a proposé un tarif pour votre devis.",
            'tarif' => $this->devis->tarif,
        ];
    }

        public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Réponse à votre demande de devis')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Le responsable a proposé un tarif pour votre demande de devis.')
                    ->line('Tarif proposé : ' . number_format($this->devis->tarif, 2) . ' DA')
                    ->action('Voir le devis', url(route('devis.show', $this->devis->id)))
                    ->line('Merci de votre confiance.');
    }
}
