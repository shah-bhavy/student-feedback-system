<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ ucfirst($role) }} Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-black/20 backdrop-blur">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-cyan-300/80">Smart School Feedback System</p>
                        <h3 class="mt-2 text-3xl font-semibold text-white">Welcome, {{ $user->name }}</h3>
                        <p class="mt-2 max-w-2xl text-sm text-slate-300">
                            You are signed in as {{ ucfirst($user->role) }}. The interface below reflects your role and the feedback rules are enforced on the server.
                        </p>
                        <p class="mt-2 text-xs text-slate-400">
                            Effective school date: {{ $effectiveDate->toDateString() }} ({{ $effectiveDate->format('l') }})
                        </p>
                    </div>

                    <div class="rounded-2xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-3 text-sm text-cyan-100">
                        @if ($user->isStudent())
                            Student feedback is only available on Friday and once per week.
                        @elseif ($user->isAdmin())
                            You can manage users and review every feedback entry.
                        @else
                            You can review submitted feedback from your dashboard.
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="text-sm text-slate-400">Total Users</div>
                    <div class="mt-2 text-3xl font-semibold text-white">{{ $metrics['totalUsers'] }}</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="text-sm text-slate-400">Total Feedback</div>
                    <div class="mt-2 text-3xl font-semibold text-white">{{ $metrics['totalFeedback'] }}</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="text-sm text-slate-400">This Week</div>
                    <div class="mt-2 text-3xl font-semibold text-white">{{ $metrics['weekFeedback'] }}</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="text-sm text-slate-400">My Feedback</div>
                    <div class="mt-2 text-3xl font-semibold text-white">{{ $metrics['myFeedback'] }}</div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <h4 class="text-lg font-semibold text-white">Quick Actions</h4>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('feedback.index') }}" class="inline-flex items-center rounded-xl bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-cyan-300">
                            View Feedback
                        </a>
                        @if ($user->isStudent())
                            <a href="{{ route('feedback.create') }}" class="inline-flex items-center rounded-xl border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                                Submit Feedback
                            </a>
                        @endif
                        @if ($user->isAdmin())
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-xl border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                                Manage Users
                            </a>
                            <a href="{{ route('admin.calendar.edit') }}" class="inline-flex items-center rounded-xl border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                                Manage Calendar
                            </a>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <h4 class="text-lg font-semibold text-white">Role Summary</h4>
                    <div class="mt-4 space-y-3 text-sm text-slate-300">
                        <p>Admin: full access to users and feedback.</p>
                        <p>Principal: read-only feedback oversight.</p>
                        <p>Faculty: track student feedback submissions.</p>
                        <p>Student: submit one feedback entry each week on Friday.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
