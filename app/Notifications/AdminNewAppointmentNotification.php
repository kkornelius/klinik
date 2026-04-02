<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewAppointmentNotification extends Notification
{
    use Queueable;

    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Baru dari Pasien')
            ->greeting('Halo Admin,')
            ->line('Ada appointment baru yang perlu ditinjau.')
            ->line('ID: #' . ($this->payload['appointment_id'] ?? '-'))
            ->line('Pasien: ' . ($this->payload['pasien_nama'] ?? '-'))
            ->line('Dokter: ' . ($this->payload['dokter_nama'] ?? '-'))
            ->line('Tanggal: ' . ($this->payload['tanggal'] ?? '-'))
            ->action('Lihat Appointment', route('admin.appointments.index'))
            ->line('Silakan lakukan konfirmasi melalui panel admin.');
    }
}
