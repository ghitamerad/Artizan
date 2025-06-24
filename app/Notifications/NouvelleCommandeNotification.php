<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleCommandeNotification extends Notification
{
 use Queueable;

    protected $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function via($notifiable)
    {
        return ['database']; // tu peux ajouter 'mail' si tu veux aussi
    }

    public function toDatabase($notifiable)
    {
        return [
            'commande_id' => $this->commande->id,
            'client_nom' => $this->commande->user->name,
            'message' => 'Une nouvelle commande a été passée.',
            'lien' => route('commandes.show', $this->commande->id), // 🔗 Lien vers la commande

        ];
    }
}
