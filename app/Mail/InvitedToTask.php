<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\InvitationToTask;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitedToTask extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Task
     */
    private $task;

    /**
     * @var InvitationToTask
     */
    private $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task, InvitationToTask $invitation)
    {
        $this->task = $task;
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.task.invited', [
            'title' => $this->task->title,
            'url'   => route('tasks.handle-invitation', $this->invitation->code)
        ]);
    }
}
