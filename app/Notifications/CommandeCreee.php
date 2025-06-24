<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeCreee extends Notification
{
    use Queueable;

    public $commande;

    /**
     * Crée une nouvelle instance de notification.
     */
    public function __construct($commande, public $detailCommande)
    {
        $this->commande = $commande;
    }

    /**
     * Canaux de notification (mail + base de données).
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Notification par email.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('🧵 Commande créée à partir de votre devis')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Suite à votre demande de devis, nous avons le plaisir de vous informer que votre commande n°{$this->commande->id} a bien été créée.")
            ->line("Nos équipes ont pris en charge votre commande et la traiteront dans les plus brefs délais.")
            ->line("Vous pouvez consulter les détails de la commande dans votre espace client.")
            ->action('Voir ma commande', route('detail-commandes.showClient', $this->commande->id))
            ->salutation('— L’équipe Lebsa Zina');
    }

    /**
     * Notification en base de données.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Votre commande n°{$this->commande->id} a été créée.",
            'commande_id' => $this->commande->id,
            'lien' => route('detail-commandes.showClient', $this->commande->id),
        ];
    }
}
