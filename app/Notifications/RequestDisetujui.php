<?php

namespace App\Notifications;

use App\Models\RequestBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestDisetujui extends Notification
{
    use Queueable;
    public function __construct(public RequestBarang $request) {}
    public function via($notifiable): array { return ['database']; }
    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Request Disetujui',
            'message' => "Request {$this->request->no_transaksi} Anda telah disetujui dan sedang diproses.",
            'icon'    => 'check-circle',
            'color'   => 'emerald',
            'url'     => route('transaksi.request.show', $this->request->id),
        ];
    }
}
