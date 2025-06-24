<?php

namespace App\Notifications;

use App\Models\DetailCommande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeTermineeParCouturiere extends Notification
{
    use Queueable;

   protected $detail;

    public function __construct(DetailCommande $detail)
    {
        $this->detail = $detail;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📦 Commande finalisée par la couturière')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("La commande n°{$this->detail->commande->id} a été entièrement terminée par la couturière.")
            ->line("Client : {$this->detail->commande->user->name}")
            ->line("Modèle : {$this->detail->modele->nom}")
            ->action('Voir la commande', route('commandes.show', $this->detail->commande->id))
            ->salutation('— L’équipe Lebsa Zina');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "La commande n°{$this->detail->commande->id} a été finalisée par la couturière.",
            'commande_id' => $this->detail->commande->id,
            'detail_commande_id' => $this->detail->id,
            'lien' => route('commandes.show', $this->detail->commande->id),
        ];
    }
}
