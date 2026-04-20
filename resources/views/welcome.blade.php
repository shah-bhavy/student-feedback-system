<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset(config('app.logo_path')) }}">

        <title>{{ config('app.name', 'Smart School Feedback') }}</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-slate-950 text-white antialiased">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.20),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(245,158,11,0.18),_transparent_28%),linear-gradient(135deg,_#020617_0%,_#0f172a_50%,_#111827_100%)]"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px); background-size: 72px 72px;"></div>

            <div class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-6 py-8 lg:px-10">
                <header class="flex items-center justify-between gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-white">
                        <x-application-logo class="h-11 w-11 text-cyan-300" />
                        <div>
                            <div class="text-lg font-semibold tracking-wide">Smart School</div>
                            <div class="text-xs uppercase tracking-[0.35em] text-slate-400">Student Feedback System</div>
                        </div>
                    </a>

                    <div class="flex items-center gap-3 text-sm font-medium">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-full border border-white/15 bg-white/5 px-4 py-2 text-white backdrop-blur hover:bg-white/10">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-white/15 bg-white/5 px-4 py-2 text-white backdrop-blur hover:bg-white/10">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full bg-cyan-400 px-4 py-2 text-slate-950 hover:bg-cyan-300">Register</a>
                            @endif
                        @endauth
                    </div>
                </header>

                <main class="grid flex-1 items-center gap-10 py-12 lg:grid-cols-[1.1fr_0.9fr]">
                    <section class="space-y-8 text-white">
                        <div class="inline-flex items-center rounded-full border border-cyan-400/25 bg-cyan-400/10 px-4 py-2 text-sm text-cyan-200 backdrop-blur">
                            Friday-only feedback. Once per week. Role-based access.
                        </div>

                        <div class="space-y-5">
                            <h1 class="max-w-2xl text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                                A clean Laravel app for student feedback and admin control.
                            </h1>
                            <p class="max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                                Students submit feedback only on Fridays, once per week. Admins, principals, and faculty review submissions through protected dashboards built with Laravel MVC, middleware, and Blade.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('login') }}" class="rounded-2xl bg-white px-6 py-3 font-semibold text-slate-950 hover:bg-slate-200">Enter system</a>
                            <a href="#features" class="rounded-2xl border border-white/15 bg-white/5 px-6 py-3 font-semibold text-white backdrop-blur hover:bg-white/10">See features</a>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <div class="text-sm text-slate-400">Access</div>
                                <div class="mt-2 text-xl font-semibold">4 roles</div>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <div class="text-sm text-slate-400">Feedback rule</div>
                                <div class="mt-2 text-xl font-semibold">Friday only</div>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <div class="text-sm text-slate-400">Security</div>
                                <div class="mt-2 text-xl font-semibold">Soft deletes</div>
                            </div>
                        </div>
                    </section>

                    <section class="relative">
                        <div class="absolute -inset-6 rounded-[2rem] bg-cyan-400/10 blur-3xl"></div>
                        <div class="relative rounded-[2rem] border border-white/10 bg-slate-950/70 p-6 shadow-2xl shadow-black/30 backdrop-blur-xl">
                            <div class="rounded-[1.5rem] border border-white/10 bg-gradient-to-br from-slate-900 to-slate-800 p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm uppercase tracking-[0.35em] text-cyan-300">Dashboard preview</div>
                                        <h2 class="mt-2 text-2xl font-semibold">Built for schools</h2>
                                    </div>
                                    <div class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-300">Live logic</div>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                        <div class="text-sm text-slate-400">Student</div>
                                        <div class="mt-2 font-semibold">Submits one feedback per week</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                        <div class="text-sm text-slate-400">Admin</div>
                                        <div class="mt-2 font-semibold">Manages users and deleted records</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                        <div class="text-sm text-slate-400">Principal</div>
                                        <div class="mt-2 font-semibold">Reviews all submissions</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                        <div class="text-sm text-slate-400">Faculty</div>
                                        <div class="mt-2 font-semibold">Tracks classroom feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

                <section id="features" class="grid gap-4 border-t border-white/10 py-8 text-sm text-slate-300 sm:grid-cols-3">
                    <div>Laravel MVC structure with routes, controllers, models, and Blade views.</div>
                    <div>Role-based middleware protects admin and student-only actions.</div>
                    <div>Email notifications and soft deletes keep the workflow practical.</div>
                </section>
            </div>
        </div>
    </body>
</html>
