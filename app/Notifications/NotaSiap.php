<?php

namespace App\Notifications;

use App\Models\RequestBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotaSiap extends Notification
{
    use Queueable;
    public function __construct(public RequestBarang $request) {}
    public function via($notifiable): array { return ['database']; }
    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Nota Siap Diambil',
            'message' => "Barang untuk request {$this->request->no_transaksi} telah diserahkan. Nota siap dicetak.",
            'icon'    => 'receipt',
            'color'   => 'cyan',
            'url'     => route('transaksi.nota.show', $this->request->id),
        ];
    }
}
