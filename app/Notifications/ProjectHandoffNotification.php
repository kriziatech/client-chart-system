<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectHandoffNotification extends Notification
{
    use Queueable;

    private $lead;
    private $client;

    public function __construct(\App\Models\Pitch\PitchLead $lead, \App\Models\Client $client)
    {
        $this->lead = $lead;
        $this->client = $client;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "New project handoff: \"{$this->lead->name}\" has been converted and assigned to you.",
            'type' => 'handoff',
            'lead_id' => $this->lead->id,
            'client_id' => $this->client->id,
            'action_url' => route('clients.show', $this->client->id),
        ];
    }
}