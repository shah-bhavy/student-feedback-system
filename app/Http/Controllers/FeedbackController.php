<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Models\FeedbackDraft;
use App\Models\User;
use App\Notifications\FeedbackSubmittedNotification;
use App\Support\SchoolCalendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Feedback::with('student')->latest('submitted_at');
        $draft = null;

        if ($user->isStudent()) {
            $query->where('student_id', $user->id);
            $draft = FeedbackDraft::query()->where('student_id', $user->id)->first();
        }

        $feedbacks = $query->paginate(10);

        return view('feedback.index', compact('feedbacks', 'user', 'draft'));
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isStudent(), 403);

        $effectiveToday = SchoolCalendar::today();
        $draft = FeedbackDraft::query()
            ->where('student_id', $request->user()->id)
            ->first();

        return view('feedback.create', [
            'alreadySubmittedThisWeek' => $this->hasSubmittedThisWeek($request->user()->id),
            'isFriday' => SchoolCalendar::isFriday(),
            'effectiveDate' => $effectiveToday,
            'draft' => $draft,
        ]);
    }

    public function store(StoreFeedbackRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if (($validated['action'] ?? 'submit') === 'draft') {
            FeedbackDraft::query()->updateOrCreate(
                ['student_id' => $user->id],
                [
                    'title' => $validated['title'] ?? null,
                    'message' => $validated['message'] ?? null,
                ],
            );

            return redirect()
                ->route('feedback.create')
                ->with('status', 'Draft saved. You can submit it when the admin calendar reaches Friday.');
        }

        if (! SchoolCalendar::isFriday()) {
            throw ValidationException::withMessages([
                'message' => 'Feedback can only be submitted on Friday.',
            ]);
        }

        if ($this->hasSubmittedThisWeek($user->id)) {
            throw ValidationException::withMessages([
                'message' => 'You have already submitted feedback for this week.',
            ]);
        }

        $feedback = Feedback::create([
            'student_id' => $user->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'feedback_week' => SchoolCalendar::weekKey(),
            'submitted_at' => now(),
            'workflow_status' => Feedback::STATUS_PENDING_FACULTY,
        ]);

        FeedbackDraft::query()->where('student_id', $user->id)->delete();

        $recipients = User::query()
            ->whereIn('role', ['admin', 'principal', 'faculty'])
            ->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new FeedbackSubmittedNotification($feedback));
        }

        return redirect()
            ->route('feedback.index')
            ->with('status', 'Feedback submitted successfully.');
    }

    public function approve(Request $request, Feedback $feedback): RedirectResponse
    {
        $user = $request->user();

        if ($user->isFaculty() && $feedback->workflow_status === Feedback::STATUS_PENDING_FACULTY) {
            $feedback->update([
                'workflow_status' => Feedback::STATUS_PENDING_PRINCIPAL,
                'approved_by_faculty_at' => now(),
                'status_note' => null,
            ]);

            return back()->with('status', 'Feedback approved by faculty and forwarded to principal.');
        }

        if ($user->isPrincipal() && $feedback->workflow_status === Feedback::STATUS_PENDING_PRINCIPAL) {
            $feedback->update([
                'workflow_status' => Feedback::STATUS_PENDING_ADMIN,
                'approved_by_principal_at' => now(),
                'status_note' => null,
            ]);

            return back()->with('status', 'Feedback approved by principal and forwarded to admin.');
        }

        if ($user->isAdmin() && $feedback->workflow_status === Feedback::STATUS_PENDING_ADMIN) {
            $feedback->update([
                'workflow_status' => Feedback::STATUS_APPROVED,
                'approved_by_admin_at' => now(),
                'status_note' => null,
            ]);

            return back()->with('status', 'Feedback fully approved by admin.');
        }

        abort(403);
    }

    public function reject(Request $request, Feedback $feedback): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'status_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $canReject = ($user->isFaculty() && $feedback->workflow_status === Feedback::STATUS_PENDING_FACULTY)
            || ($user->isPrincipal() && $feedback->workflow_status === Feedback::STATUS_PENDING_PRINCIPAL)
            || ($user->isAdmin() && $feedback->workflow_status === Feedback::STATUS_PENDING_ADMIN);

        if (! $canReject) {
            abort(403);
        }

        $feedback->update([
            'workflow_status' => Feedback::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejected_by_user_id' => $user->id,
            'status_note' => $request->input('status_note'),
        ]);

        return back()->with('status', 'Feedback rejected in approval workflow.');
    }

    private function hasSubmittedThisWeek(int $studentId): bool
    {
        return Feedback::query()
            ->where('student_id', $studentId)
            ->where('feedback_week', SchoolCalendar::weekKey())
            ->exists();
    }
}
