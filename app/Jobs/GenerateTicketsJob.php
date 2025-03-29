<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Teatro2;
/**
 * Generate all the tickets in 1 order
 */
class GenerateTicketsJob extends GenerateTicketsJobBase
{
    public $order;
	public $teatro;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->attendees = $order->attendees;
        $this->event = $order->event;
        $this->file_name = $order->order_reference;
        $this->order = $order;
		$this->teatro = Teatro2::findOrFail($this->event->venue_name);
    }
}
