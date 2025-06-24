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
            ->subject('🎉 Votre commande est terminée et prête à être traitée')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Nous avons le plaisir de vous informer que votre commande n°{$this->commande->id} a été **finalisée** avec succès.")
            ->line("Nos équipes ont soigneusement traité votre commande, et elle est désormais prête pour la prochaine étape (expédition, retrait ou mise à disposition).")
            ->line("Détails de la commande :")
            ->line("- Numéro : #{$this->commande->id}")
            ->line("- Date : " . $this->commande->created_at->format('d/m/Y'))
            ->line("- Montant total : " . number_format($this->commande->montant_total, 2) . " €")
            ->action('Consulter ma commande', route('detail-commandes.showClient', $this->commande->id))
            ->line("Nous vous remercions pour votre confiance et restons à votre disposition pour toute question ou assistance.")
            ->salutation('— L’équipe Lebsa Zina');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Votre commande n°{$this->commande->id} a été terminée.",
            'commande_id' => $this->commande->id,
            'lien' => route('detail-commandes.showClient', $this->commande->id),
        ];
    }
}
