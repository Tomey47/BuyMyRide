<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Apstipriniet savu e-pasta adresi - BuyMyRide')
            ->greeting('Sveiki!')
            ->line('Paldies, ka reģistrējāties BuyMyRide!')
            ->line('Lūdzu, noklikšķiniet uz zemāk esošās pogas, lai apstiprinātu savu e-pasta adresi.')
            ->action('Apstiprināt e-pasta adresi', $verificationUrl)
            ->line('Ja jums ir problēmas ar pogas noklikšķināšanu, kopējiet un ielīmējiet zemāk esošo saiti savā tīmekļa pārlūkprogrammā:')
            ->line($verificationUrl)
            ->line('Šī saite būs derīga 60 minūtes.')
            ->line('Ja jūs neveicāt reģistrāciju BuyMyRide, nav nepieciešams veikt nekādas darbības.')
            ->salutation('Ar cieņu, BuyMyRide komanda');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
