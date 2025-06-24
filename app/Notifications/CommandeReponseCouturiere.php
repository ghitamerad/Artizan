<?php

namespace App\Notifications;

use App\Models\DetailCommande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CommandeReponseCouturiere extends Notification
{
    use Queueable;

    public string $decision;
    public DetailCommande $detail;

    public function __construct(DetailCommande $detail, string $decision)
    {
        $this->detail = $detail;
        $this->decision = $decision; // 'acceptee' ou 'refusee'
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📢 Réponse de la couturière à une commande')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("La couturière **{$this->detail->couturiere?->name}** a **{$this->decision}** la commande n°{$this->detail->commande_id}.")
            ->action('Voir la commande', route('commandes.show', $this->detail->commande_id))
            ->salutation('— L’équipe Lebsa Zina');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "La couturière {$this->detail->couturiere?->name} a {$this->decision} la commande n°{$this->detail->commande_id}.",
            'commande_id' => $this->detail->commande_id,
            'lien' => route('commandes.show', $this->detail->commande_id),
        ];
    }
}
