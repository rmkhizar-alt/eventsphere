<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        $user = auth()->user();

        $totalEvents = Event::count();
        $totalParticipants = User::where('role', 'participant')->count();
        $upcomingThisWeek = Event::where('event_date', '>=', now())
            ->where('event_date', '<=', Carbon::now()->addWeek())
            ->count();
        $registrationsThisMonth = Registration::whereMonth('created_at', now()->month)->count();
        $completedEvents = Event::where('status', 'completed')->count();

        return view('dashboard', compact(
            'user',
            'totalEvents',
            'totalParticipants',
            'upcomingThisWeek',
            'registrationsThisMonth',
            'completedEvents',
        ));
    }
}
