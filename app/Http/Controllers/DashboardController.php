<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use App\Support\SchoolCalendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        return redirect()->route($user->dashboardRouteName());
    }

    public function show(Request $request, string $role): View
    {
        $user = $request->user();
        $effectiveDate = SchoolCalendar::today();
        $currentWeek = SchoolCalendar::weekKey();

        $metrics = [
            'totalUsers' => User::count(),
            'totalFeedback' => Feedback::count(),
            'weekFeedback' => Feedback::where('feedback_week', $currentWeek)->count(),
            'myFeedback' => $user->isStudent() ? Feedback::where('student_id', $user->id)->count() : 0,
        ];

        return view('dashboard', compact('user', 'metrics', 'role', 'effectiveDate'));
    }
}