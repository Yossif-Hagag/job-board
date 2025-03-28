<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight font-sans">
            👁 View Job: {{ $job->title }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">

            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 mx-5 my-3 rounded">
                <h3 class="text-xl font-bold text-blue-700 text-blue-500 mb-4 font-sans">
                    🧾 Job Details
                </h3>

                <p class="text-gray-700 dark:text-gray-300 mb-6 border-l-4 border-blue-600 pl-4 font-sans">
                    {{ $job->description }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Job Info --}}
                    <div class="bg-gray-50 dark:bg-gray-900 rounded py-3 px-3 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📌 Basic Info</h4>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">🏢 Company:</strong>
                            <span class="text-gray-500">{{ $job->company_name }}</span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">💰 Salary:</strong>
                            <span class="text-gray-500">${{ $job->salary_min }} - ${{ $job->salary_max }}</span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">⏳ Type:</strong>
                            <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-white px-2 py-1 rounded text-sm">
                                {{ ucfirst($job->job_type) }}
                            </span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">🏠 Remote:</strong>
                            <span class="text-gray-500">{{ $job->is_remote ? 'Yes' : 'No' }}</span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">📂 Status:</strong>
                            @php
                                $statusColors = [
                                    'published' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'archived' => 'bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                                ];
                                $statusClass = $statusColors[$job->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-block px-3 py-1 rounded text-sm font-semibold {{ $statusClass }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">📅 Published At:</strong>
                            <span class="text-gray-500">
                                {{ $job->published_at ? \Carbon\Carbon::parse($job->published_at)->format('F j, Y g:i A') : '—' }}
                            </span>
                        </p>
                    </div>

                    {{-- Requirements --}}
                    <div class="bg-gray-50 dark:bg-gray-900 rounded py-3 px-3 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📋 Requirements</h4>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">🏷️ Categories:</strong>
                            <span class="text-gray-500">{{ $job->categories->pluck('name')->join(', ') }}</span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">🌍 Locations:</strong>
                            <span class="text-gray-500">{{ $job->locations->pluck('city')->join(', ') }}</span>
                        </p>

                        <p class="mb-2">
                            <strong class="text-gray-600 dark:text-gray-300">🗣 Languages:</strong>
                            <span class="text-gray-500">{{ $job->languages->pluck('name')->join(', ') }}</span>
                        </p>

                        @foreach ($job->attributeValues as $attrValue)
                            <p class="mb-2">
                                <strong class="text-gray-600 dark:text-gray-300">⚙️ {{ $attrValue->attribute->name }}:</strong>
                                <span class="text-gray-500">{{ $attrValue->value }}</span>
                            </p>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('dashboard.jobs.index') }}"
                        class="inline-flex items-center px-5 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm hover:bg-blue-600 hover:text-white transition text-gray-500">
                        ⬅️ Back to Jobs
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
