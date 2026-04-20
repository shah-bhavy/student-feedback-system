<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Manage Users
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl shadow-black/20">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                    <thead class="bg-white/5 text-xs uppercase tracking-wider text-slate-300">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 font-medium text-white">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->isAdmin())
                                        <span class="rounded-full bg-cyan-400/10 px-3 py-1 text-xs font-semibold text-cyan-200">Protected Admin</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="rounded-lg border-white/10 bg-slate-900 text-white focus:border-cyan-400 focus:ring-cyan-400">
                                                @foreach (['admin', 'principal', 'faculty', 'student'] as $role)
                                                    <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="rounded-lg bg-cyan-400 px-3 py-2 text-xs font-semibold text-slate-950 hover:bg-cyan-300">
                                                Save
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->trashed())
                                        <span class="rounded-full bg-rose-400/10 px-3 py-1 text-xs font-semibold text-rose-200">Trashed</span>
                                    @else
                                        <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-200">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($user->trashed())
                                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                                                @csrf
                                                <button class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:bg-white/10">Restore</button>
                                            </form>
                                        @else
                                            @if ($user->isAdmin())
                                                <span class="rounded-lg border border-cyan-400/20 bg-cyan-400/10 px-3 py-2 text-xs font-semibold text-cyan-100">Protected</span>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Move this user to trash?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="rounded-lg border border-rose-400/20 bg-rose-400/10 px-3 py-2 text-xs font-semibold text-rose-100 hover:bg-rose-400/20">Trash</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-300">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>