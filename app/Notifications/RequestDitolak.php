<?php

namespace App\Notifications;

use App\Models\RequestBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestDitolak extends Notification
{
    use Queueable;
    public function __construct(public RequestBarang $request) {}
    public function via($notifiable): array { return ['database']; }
    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Request Ditolak',
            'message' => "Request {$this->request->no_transaksi} Anda ditolak. Alasan: {$this->request->alasan_reject}",
            'icon'    => 'x-circle',
            'color'   => 'red',
            'url'     => route('transaksi.request.show', $this->request->id),
        ];
    }
}
