<?php

namespace App\Jobs;

use App\Mail\SendOrderConfirmationMail;
use App\Models\Order;
use App\Services\Order as OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Config;
use Mail;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $orderService;
    public $orderUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, OrderService $orderService, $orderUrl)
    {
        $this->order = $order;
        $this->orderService = $orderService;
        $this->orderUrl = $orderUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //GenerateTicketsJob::dispatchNow($this->order);
        try {
            $mail = new SendOrderConfirmationMail($this->order, $this->orderService, $this->orderUrl);

            //IMPORTANTE WOOPI DESCOMENTAR ESTA LINEA

//            Mail::to($this->order->email)
//                ->locale(Config::get('app.locale'))
//                ->send($mail);
        } catch (\Exception $e) {
            // No hacer nada; simplemente continuar con la ejecución
        }

    }
}
