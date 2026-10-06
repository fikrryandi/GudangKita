<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifications()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function show($id)
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        $url = $notif->data['url'] ?? route('dashboard');
        return redirect($url);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function destroy($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }
}
