<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            School Calendar Control
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-slate-200 shadow-2xl shadow-black/20">
                <p class="text-sm text-slate-300">
                    Effective date used for student rules:
                    <span class="font-semibold text-white">{{ $effectiveDate->toDateString() }} ({{ $effectiveDate->format('l') }})</span>
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    If you set this date to Friday, students can submit weekly feedback according to this admin calendar.
                </p>

                <form method="POST" action="{{ route('admin.calendar.update') }}" class="mt-6 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="override_date" :value="__('Override Date (Optional)')" class="text-slate-100" />
                        <x-text-input
                            id="override_date"
                            name="override_date"
                            type="date"
                            class="mt-1 block w-full rounded-xl border-gray-300 bg-white text-slate-900"
                            :value="old('override_date', $overrideDate)"
                        />
                        <x-input-error :messages="$errors->get('override_date')" class="mt-2" />
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-primary-button>
                            Save Calendar Date
                        </x-primary-button>
                        <p class="text-xs text-slate-400">Leave empty and save to reset to real system date.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
