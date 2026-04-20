<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SchoolCalendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function edit(): View
    {
        return view('admin.calendar.edit', [
            'overrideDate' => SchoolCalendar::overrideDate(),
            'effectiveDate' => SchoolCalendar::today(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'override_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        if (! empty($validated['override_date'])) {
            SchoolCalendar::setOverrideDate($validated['override_date']);

            return back()->with('status', 'School calendar date updated. Students now follow this date.');
        }

        SchoolCalendar::clearOverrideDate();

        return back()->with('status', 'School calendar override cleared. System date is active.');
    }
}
