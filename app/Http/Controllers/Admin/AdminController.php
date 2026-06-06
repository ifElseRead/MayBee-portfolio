<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     // Let web.php handle this, or change it to match your route middleware:
    //     $this->middleware(['auth', 'verified']);
    // }   

    public function messages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);

        return Inertia::render('Admin/Messages', [
            'messages' => $messages,
        ]);
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        return Inertia::render('Admin/ShowMessage', [
            'message' => $message,
        ]);
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();

        return back()->with('success', 'Message deleted.');
    }
}
