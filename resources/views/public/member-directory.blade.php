<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Directory') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <form method="GET" action="{{ route('member.directory') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                            <div class="flex-1">
                                <label for="q" class="block text-sm font-medium text-gray-700">
                                    {{ __('Search') }}
                                </label>
                                <input
                                    id="q"
                                    name="q"
                                    type="text"
                                    value="{{ request('q') }}"
                                    placeholder="{{ __('Type member name or contact number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>

                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ __('Search') }}
                            </button>
                        </form>
                    </div>

                    @php
                        $members = \App\Models\Member::query()
                            ->with(['position', 'club'])
                            ->when(request('q'), function ($query, $q) {
                                $q = trim($q);
                                $query->where(function ($sub) use ($q) {
                                    $sub->where('name', 'like', "%{$q}%")
                                        ->orWhere('contact_number', 'like', "%{$q}%")
                                        ->orWhere('slug', 'like', "%{$q}%");
                                });
                            })
                            ->orderBy('name')
                            ->paginate(12);
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Name') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Club') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Position') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">
                                        {{ __('Profile') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($members as $member)
                                    <tr>
                                        <td class="px-3 py-3.5 text-sm text-gray-900">
                                            {{ $member->name }}
                                        </td>
                                        <td class="px-3 py-3.5 text-sm text-gray-900">
                                            {{ optional($member->club)->name }}
                                        </td>
                                        <td class="px-3 py-3.5 text-sm text-gray-900">
                                            {{ optional($member->position)->name }}
                                        </td>
                                        <td class="px-3 py-3.5 text-sm text-right">
                                            <a
                                                href="{{ route('member.profile', $member->slug) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md text-xs font-semibold hover:bg-indigo-100"
                                            >
                                                {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-8 text-sm text-gray-500 text-center">
                                            {{ __('No members found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $members->appends(['q' => request('q')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
