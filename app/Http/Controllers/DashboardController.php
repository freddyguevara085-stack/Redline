<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\History;
use App\Models\LibraryItem;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $eventsCount = Event::count();
        $histCount   = History::count();
        $libCount    = LibraryItem::count();
        $users       = User::count();

        return view('dashboard.index', compact('eventsCount', 'histCount', 'libCount', 'users'));
    }
}