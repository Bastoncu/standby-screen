<?php

namespace App\Events;

use App\QueueListModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueList implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queueList;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(QueueListModel $queueList)
    {
        $this->queueList = $queueList;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('queue-list');
    }

    public function broadcastWith()
    {
        return [
            'fullname' => $this->queueList->fullname,
            'id' => $this->queueList->id,
            'class_id' => $this->queueList->class_id
        ];
    }
}
