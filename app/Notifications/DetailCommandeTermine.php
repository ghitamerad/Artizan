<?php

namespace App\Notifications;

use App\Models\DetailCommande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DetailCommandeTermine extends Notification
{
    use Queueable;

    protected $detail;

    /**
     * Crée une nouvelle instance de notification.
     */
    public function __construct(DetailCommande $detail)
    {
        $this->detail = $detail;
    }

    /**
     * Canaux utilisés pour la notification.
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Notification email (optionnel).
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🔧 Un élément de votre commande est terminé')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Un élément de votre commande n°{$this->detail->commande->id} a été marqué comme terminé par la couturière.")
            ->line("Modèle : {$this->detail->modele->nom}")
            ->action('Voir ma commande', route('detail-commandes.showClient', $this->detail->id))
            ->salutation('— L’équipe Lebsa Zina');
    }

    /**
     * Notification base de données.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Un élément de votre commande n°{$this->detail->commande->id} a été terminé.",
            'commande_id' => $this->detail->commande->id,
            'detail_commande_id' => $this->detail->id,
            'lien' => route('detail-commandes.showClient', $this->detail->id),
        ];
    }
}
