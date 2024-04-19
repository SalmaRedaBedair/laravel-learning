<?php

namespace App\Notifications;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProposalNotification extends Notification
{
    use Queueable;
    protected $proposal, $freelancer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Proposal $proposal, User $freelancer)
    {
        $this->proposal=$proposal;
        $this->freelancer=$freelancer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array // notifiable: person to whom i will send notification
    {
        return ['database']; // channel i will send notification throw
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable)
    {
        $body= sprintf(
            '%s applied for a job %s',
            $this->freelancer->name,
            $this->proposal->project->title
        );
        // that form must be unique for all notification classes you make
        return [
            'title'=>'New proposal',
            'body'=>$body,
            'icon'=>'icon-material-outline-group',
            'url'=>route('client.projects.show', $this->proposal->project_id),
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // toArray works for all databases
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
