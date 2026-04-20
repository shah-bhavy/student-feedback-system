<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Submit Feedback
        </h2>
    </x-slot>

    <div class="py-12">
        <div
            class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6"
            x-data="feedbackForm({
                isFriday: @js($isFriday),
                alreadySubmittedThisWeek: @js($alreadySubmittedThisWeek),
                serverMessage: @js($errors->first('message')),
                hasDraft: @js((bool) $draft),
            })"
            x-init="init()"
        >
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-slate-200 shadow-2xl shadow-black/20">
                <div class="space-y-2 text-sm">
                    <p>Effective school date: <span class="font-semibold text-cyan-300">{{ $effectiveDate->toDateString() }} ({{ $effectiveDate->format('l') }})</span></p>
                    <p>Friday only: <span class="font-semibold {{ $isFriday ? 'text-emerald-300' : 'text-rose-300' }}">{{ $isFriday ? 'Allowed today' : 'Not allowed today' }}</span></p>
                    <p>Once per week: <span class="font-semibold {{ $alreadySubmittedThisWeek ? 'text-rose-300' : 'text-emerald-300' }}">{{ $alreadySubmittedThisWeek ? 'Already submitted this week' : 'You can submit once this week' }}</span></p>
                </div>

                <form method="POST" action="{{ route('feedback.store') }}" class="mt-6 space-y-6" @submit.prevent="handleSubmit($event)">
                    @csrf
                    <input type="hidden" name="action" x-model="actionType">

                    <div>
                        <x-input-label for="title" :value="__('Title')" class="text-slate-100" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-xl border-gray-300 bg-white text-slate-900 placeholder-slate-400 focus:border-cyan-500 focus:ring-cyan-500" :value="old('title', $draft?->title)" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="message" :value="__('Feedback')" class="text-slate-100" />
                        <textarea id="message" name="message" rows="8" class="mt-1 block w-full rounded-xl border-gray-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('message', $draft?->message) }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <a href="{{ route('feedback.index') }}" class="text-sm font-medium text-slate-300 hover:text-white">Back to feedback</a>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center rounded-xl border border-white/20 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10"
                                @click="handleSubmit($event, 'draft')"
                            >
                                Save Draft
                            </button>
                            <x-primary-button type="button" @click="handleSubmit($event, 'submit')">
                                {{ __('Submit Feedback') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>

            <div
                x-show="modalOpen"
                x-cloak
                class="fixed inset-0 z-[1200] flex items-center justify-center bg-slate-950/70 px-4"
                @click.self="closeModal()"
                @keydown.escape.window="closeModal()"
            >
                <div class="w-full max-w-md rounded-2xl border p-6 shadow-2xl" :class="modalType === 'error' ? 'border-rose-400/40 bg-rose-950/95' : 'border-emerald-400/40 bg-emerald-950/95'">
                    <h3 class="text-lg font-semibold" :class="modalType === 'error' ? 'text-rose-200' : 'text-emerald-200'" x-text="modalType === 'error' ? 'Submission Blocked' : 'Submission Started'"></h3>
                    <p class="mt-3 text-sm text-slate-100" x-text="modalMessage"></p>

                    <div class="mt-5 flex justify-end">
                        <button
                            type="button"
                            class="rounded-xl border border-white/20 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10"
                            @click="closeModal()"
                        >
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function feedbackForm(config) {
            return {
                isFriday: config.isFriday,
                alreadySubmittedThisWeek: config.alreadySubmittedThisWeek,
                serverMessage: config.serverMessage,
                hasDraft: config.hasDraft,
                effectiveDateText: @js($effectiveDate->toDateString().' ('.$effectiveDate->format('l').')'),
                actionType: 'submit',
                modalOpen: false,
                modalType: 'error',
                modalMessage: '',
                init() {
                    if (this.serverMessage) {
                        this.showError(this.serverMessage);
                    }
                },
                handleSubmit(event, action = 'submit') {
                    this.actionType = action;

                    const form = event.target.closest('form') ?? event.target;

                    if (action === 'draft') {
                        this.showSuccess('Draft saved. You can come back and submit this on Friday.');

                        setTimeout(() => {
                            form.submit();
                        }, 250);

                        return;
                    }

                    if (!this.isFriday) {
                        this.showError('Feedback can only be submitted on Friday according to the admin calendar. Effective date: ' + this.effectiveDateText + '.');
                        return;
                    }

                    if (this.alreadySubmittedThisWeek) {
                        this.showError('You have already submitted feedback for this week.');
                        return;
                    }

                    this.showSuccess('All checks passed. Submitting your feedback now.');

                    setTimeout(() => {
                        form.submit();
                    }, 350);
                },
                showError(message) {
                    this.modalType = 'error';
                    this.modalMessage = message;
                    this.modalOpen = true;
                },
                showSuccess(message) {
                    this.modalType = 'success';
                    this.modalMessage = message;
                    this.modalOpen = true;
                },
                closeModal() {
                    this.modalOpen = false;
                },
            };
        }
    </script>
</x-app-layout>