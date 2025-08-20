<?php

namespace NoopStudios\FilamentEditProfile\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ChangeEmailConfirmation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  string  $newEmail  The new email address to verify
     * @param  int  $userId  The ID of the user
     */
    public function __construct(public string $newEmail, public int $userId)
    {
        $this->newEmail = $newEmail;
        $this->userId = $userId;
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

        $url = URL::temporarySignedRoute(
            'verification.email.change',
            now()->addMinutes(60),
            [
                'id' => $this->userId,
                'email' => $this->newEmail,
                'hash' => sha1($this->newEmail),
            ]
        );

        return (new MailMessage)
            ->subject(__('filament-edit-profile::default.email_change_confirmation_subject'))
            ->line(__('filament-edit-profile::default.email_change_request_message'))
            ->line(__('filament-edit-profile::default.email_confirm_action_message'))
            ->action(__('filament-edit-profile::default.confirm_email_change'), $url)
            ->line(__('filament-edit-profile::default.email_change_no_action_message'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'new_email' => $this->newEmail,
        ];
    }
}
