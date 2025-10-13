<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostLikedNotification extends Notification
{
    use Queueable;
    protected User $liker ;
    protected Post $post ;

    public function __construct( User $liker, Post $post)
    {
        $this->liker = $liker;
        $this->post = $post;
    }


    public function via( $notifiable): array
    {
        return ['database','mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('Liked Post')
                ->greeting('Hello!' . $notifiable->first_name)
                ->line('user' . $this->liker->first_name . 'Liked Post')
                ->line('title' . $this->post->title)
                ->action('lets me see' , url('/posts/' . $this->post->id))
                ->line('Thank you');
    }


    public function toDatabase( $notifiable): array
    {
        return [
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->first_name,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'message' => $this->liker ->first_name . 'Liked Post',
        ];
    }
}
