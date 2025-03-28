<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
            📊 Dashboard Overview
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow">
                    <div class="text-sm text-gray-500">Total Jobs</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $jobCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow">
                    <div class="text-sm text-gray-500">Companies</div>
                    <div class="text-3xl font-bold text-green-500">{{ $companyCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow">
                    <div class="text-sm text-gray-500">Locations</div>
                    <div class="text-3xl font-bold text-yellow-500">{{ $locationCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow">
                    <div class="text-sm text-gray-500">Admins</div>
                    <div class="text-3xl font-bold text-purple-500">{{ $userCount }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-green-100 dark:bg-green-900 rounded-2xl p-5 shadow text-center">
                    <div class="text-sm text-green- dark:text-green-200">Published Jobs</div>
                    <div class="text-3xl font-bold text-green- dark:text-green-300">{{ $publishedCount }}</div>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 rounded-2xl p-5 shadow text-center">
                    <div class="text-sm text-yellow-500 dark:text-yellow-200">Draft Jobs</div>
                    <div class="text-3xl font-bold text-yellow-500 dark:text-yellow-300">{{ $draftCount }}</div>
                </div>
                <div class="bg-gray-200 dark:bg-gray-800 rounded-2xl p-5 shadow text-center">
                    <div class="text-sm text-gray-700 dark:text-gray-300">Archived Jobs</div>
                    <div class="text-3xl font-bold text-gray-700 dark:text-gray-300">{{ $archivedCount }}</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📈 Jobs Per Month</h3>
                <canvas id="jobsChart" height="120"></canvas>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📋 Latest Jobs</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
                            <tr>
                                <th class="py-3 px-4">Title</th>
                                <th class="py-3 px-4">Company</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestJobs as $job)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-3 px-4 font-semibold">{{ $job->title }}</td>
                                    <td class="py-3 px-4">{{ $job->company_name }}</td>
                                    <td class="py-3 px-4 capitalize">{{ $job->job_type }}</td>
                                    <td class="py-3 px-4">
                                        @php
                                            $colors = [
                                                'published' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                'draft' => 'bg-yellow-100 text-yellow-500 dark:bg-yellow-900 dark:text-yellow-300',
                                                'archived' => 'bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $colors[$job->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">{{ $job->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-400">No jobs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('jobsChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($jobsPerMonthLabels) !!},
                datasets: [{
                    label: 'Jobs Created',
                    data: {!! json_encode($jobsPerMonthCounts) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.5,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</x-app-layout>
