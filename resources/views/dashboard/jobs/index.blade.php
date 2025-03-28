<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight font-sans">
            📋 Manage Jobs
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-blue-700 dark:text-blue-400 font-sans text-blue-500">All Jobs</h3>
                <a href="{{ route('dashboard.jobs.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 transition">
                    ➕ Add New Job
                </a>
            </div>

            @if ($jobs->count())
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        <thead
                            class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white text-sm uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Title</th>
                                <th class="px-6 py-3 text-left">Company</th>
                                <th class="px-6 py-3 text-left">Type</th>
                                <th class="px-6 py-3 text-left">Remote</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Salary</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                            @foreach ($jobs as $index => $job)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-6 py-4 font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $job->title }}</td>
                                    <td class="px-6 py-4">{{ $job->company_name }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">
                                            {{ ucfirst($job->job_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($job->is_remote)
                                            <span class="text-green-600 font-semibold">✔ Yes</span>
                                        @else
                                            <span class="text-red-500 font-semibold">✖ No</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'draft' => 'text-gray-500',
                                                'published' => 'text-green-500',
                                                'archived' => 'text-red-500',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 text-sm rounded-full font-medium {{ $statusColors[$job->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        ${{ $job->salary_min }} - ${{ $job->salary_max }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('dashboard.jobs.show', $job) }}"
                                                class="text-green-600 hover:text-green-800 text-sm font-semibold">
                                                👁 View
                                            </a>
                                            <a href="{{ route('dashboard.jobs.edit', $job) }}"
                                                class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold">
                                                ✏️ Edit
                                            </a>
                                            <form action="{{ route('dashboard.jobs.destroy', $job) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this job?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                                    🗑 Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6 pb-3">
                    {{ $jobs->links() }}
                </div>
            @else
                <div
                    class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-100 dark:bg-yellow-900 p-4 rounded text-center">
                    ⚠️ No jobs found.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
