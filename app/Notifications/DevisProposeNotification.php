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
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'devis_id' => $this->devis->id,
            'message' => "Le responsable a proposé un tarif pour votre devis.",
            'tarif' => $this->devis->tarif,
            'lien' => route('devis.client.show', $this->devis->id), // lien direct
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('💼 Votre devis est prêt : tarif proposé')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Suite à votre demande de devis concernant une commande personnalisée, nous avons le plaisir de vous transmettre une **proposition tarifaire**.")
            ->line("📄 **Détails du devis :**")
            ->line("- Numéro du devis : #{$this->devis->id}")
            ->line("- Tarif proposé : **" . number_format($this->devis->tarif, 2) . " DA**")
            ->line("- Date de la proposition : " . $this->devis->updated_at->format('d/m/Y à H:i'))
            ->action('📥 Consulter le devis', route('devis.client.show', $this->devis->id))
            ->line("Nous vous invitons à consulter le devis et à **accepter ou refuser la proposition** directement depuis votre espace client.")
            ->line("💡 Pour toute question ou précision, n'hésitez pas à nous contacter.")
            ->salutation('— L’équipe Lebsa Zina');
    }
}
