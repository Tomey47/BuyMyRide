<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var (\Closure(mixed, string): string)|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var (\Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage)|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if (static::$createUrlCallback) {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = $this->resetUrl($notifiable);
        }

        return (new MailMessage)
            ->subject(Lang::get('Paroles atiestatīšana'))
            ->greeting('Sveiki!')
            ->line(Lang::get('Jūs saņēmāt šo e-pastu, jo mēs saņēmām paroles atiestatīšanas pieprasījumu jūsu kontam.'))
            ->action(Lang::get('Atiestatīt paroli'), $url)
            ->line(Lang::get('Šī paroles atiestatīšanas saite būs derīga :count minūtes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Ja jūs nepieprasījāt paroles atiestatīšanu, nav nepieciešams veikt nekādas darbības.'))
            ->salutation('Ar cieņu, ' . config('app.name'));
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Paroles atiestatīšana'))
            ->greeting('Sveiki!')
            ->line(Lang::get('Jūs saņēmāt šo e-pastu, jo mēs saņēmām paroles atiestatīšanas pieprasījumu jūsu kontam.'))
            ->action(Lang::get('Atiestatīt paroli'), $url)
            ->line(Lang::get('Šī paroles atiestatīšanas saite būs derīga :count minūtes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Ja jūs nepieprasījāt paroles atiestatīšanu, nav nepieciešams veikt nekādas darbības.'))
            ->salutation('Ar cieņu, ' . config('app.name'));
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60)),
            [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        );
    }

    /**
     * Set a callback that should be used when creating the reset password button URL.
     *
     * @param  \Closure(mixed, string): string  $callback
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
} 