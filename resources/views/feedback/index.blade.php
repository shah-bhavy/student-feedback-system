<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $user->isStudent() ? 'My Feedback' : 'All Feedback' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            @if ($user->isStudent())
                <div class="flex justify-end">
                    <a href="{{ route('feedback.create') }}" class="inline-flex items-center rounded-xl bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-cyan-300">
                        Submit Feedback
                    </a>
                </div>

                @if ($draft)
                    <div class="rounded-2xl border border-amber-400/20 bg-amber-400/10 px-4 py-3 text-amber-100">
                        <p class="text-sm font-semibold">Draft saved</p>
                        <p class="mt-1 text-xs">Last updated: {{ $draft->updated_at?->format('d M Y, h:i A') }}. Open Submit Feedback to continue.</p>
                    </div>
                @endif
            @endif

            <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl shadow-black/20">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                    <thead class="bg-white/5 text-xs uppercase tracking-wider text-slate-300">
                        <tr>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Week</th>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Submitted</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Message</th>
                            @if (! $user->isStudent())
                                <th class="px-6 py-4">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($feedbacks as $feedback)
                            <tr class="align-top">
                                <td class="px-6 py-4 font-medium text-white">{{ $feedback->title }}</td>
                                <td class="px-6 py-4">{{ $feedback->feedback_week }}</td>
                                <td class="px-6 py-4">{{ $feedback->student?->name }}</td>
                                <td class="px-6 py-4">{{ optional($feedback->submitted_at)->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = match ($feedback->workflow_status) {
                                            'approved' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
                                            'rejected' => 'border-rose-400/30 bg-rose-400/10 text-rose-200',
                                            default => 'border-cyan-400/30 bg-cyan-400/10 text-cyan-200',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ str($feedback->workflow_status)->replace('_', ' ')->title() }}
                                    </span>
                                    @if ($feedback->status_note)
                                        <p class="mt-2 text-xs text-slate-300">{{ $feedback->status_note }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-xl text-slate-300">{{ $feedback->message }}</td>
                                @if (! $user->isStudent())
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-2">
                                            @if (($user->isFaculty() && $feedback->workflow_status === 'pending_faculty') || ($user->isPrincipal() && $feedback->workflow_status === 'pending_principal') || ($user->isAdmin() && $feedback->workflow_status === 'pending_admin'))
                                                <form method="POST" action="{{ route('feedback.approve', $feedback) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-emerald-400 px-3 py-1.5 text-xs font-semibold text-slate-950 hover:bg-emerald-300">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('feedback.reject', $feedback) }}" class="space-y-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="status_note" placeholder="Rejection note (optional)" class="block w-full rounded-lg border border-white/20 bg-white text-xs text-slate-900" />
                                                    <button type="submit" class="inline-flex w-full justify-center rounded-lg border border-rose-300/40 bg-rose-500/20 px-3 py-1.5 text-xs font-semibold text-rose-100 hover:bg-rose-500/30">
                                                        Reject
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400">No action available</span>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $user->isStudent() ? 6 : 7 }}" class="px-6 py-10 text-center text-slate-300">No feedback found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>