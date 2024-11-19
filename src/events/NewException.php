<?php

namespace kahoiz\ExceptionLogger\events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewException
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;
    public $queue = 'new-exception';
    /**
     * Create a new event instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }



}
