<?php

namespace App\Jobs;

use App\Models\Attendee;
use App\Models\Teatro2;

/**
 * Generate a single ticket for 1 attendee
 */
class GenerateTicketJob extends GenerateTicketsJobBase
{
	public $teatro;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Attendee $attendee)
    {
        $this->attendees = [$attendee];
        $this->event = $attendee->event;
        $this->file_name = $attendee->getReferenceAttribute();
        $this->order = $attendee->order;
		$this->teatro = Teatro2::findOrFail($this->event->venue_name);
    }
}
