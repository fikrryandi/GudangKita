<?php

namespace App\Notifications;

use App\Models\RequestBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestBaru extends Notification
{
    use Queueable;

    public function __construct(public RequestBarang $request) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Request Barang Baru',
            'message' => "{$this->request->peminta->nama_lengkap} mengajukan request {$this->request->no_transaksi}",
            'icon'    => 'clipboard-list',
            'color'   => 'blue',
            'url'     => route('transaksi.approval.show', $this->request->id),
        ];
    }
}
