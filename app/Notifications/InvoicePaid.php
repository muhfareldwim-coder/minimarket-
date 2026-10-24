<?php
    
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notifikasi Pembayaran')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pembayaran kamu berhasil diproses.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }
}
