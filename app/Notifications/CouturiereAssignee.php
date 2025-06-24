<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouturiereAssignee extends Notification
{
    use Queueable;
   protected $commande;
    protected $detailCommande;

    public function __construct($commande, $detailCommande)
    {
        $this->commande = $commande;
        $this->detailCommande = $detailCommande;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // tu peux enlever 'mail' si tu ne veux que les notifications internes
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle commande assignée')
            ->line("Une nouvelle commande vous a été assignée.")
            ->action('Voir la commande', url(route('commandes.show', $this->commande->id)))
            ->line('Merci pour votre travail !');
    }

    public function toDatabase($notifiable)
    {
        return [
            'commande_id' => $this->commande->id,
            'detail_commande_id' => $this->detailCommande->id,
            'message' => 'Une nouvelle commande vous a été assignée.',
            'lien' => route('commandes.show', $this->commande->id), // 💡 Lien vers la commande

        ];
    }
}
