<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">
            ➕ Add New Job
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 container">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">
                    <strong class="block mb-2">There were some errors:</strong>
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl p-8">
                <form method="POST" action="{{ route('dashboard.jobs.store') }}" class="space-y-8">
                    @csrf

                    {{-- Basic Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Job
                                Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company
                                Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Job
                                Description</label>
                            <textarea name="description" rows="4" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    {{-- Salary + Job Type --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Salary
                                Min</label>
                            <input type="number" name="salary_min" value="{{ old('salary_min') }}" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Salary
                                Max</label>
                            <input type="number" name="salary_max" value="{{ old('salary_max') }}" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Job
                                Type</label>
                            <select name="job_type" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                                <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>
                                    Full-Time</option>
                                <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>
                                    Part-Time</option>
                                <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Contract
                                </option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" required
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>
                    </div>
                    {{-- Remote --}}
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_remote" value="1" id="is_remote"
                            class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500"
                            {{ old('is_remote') ? 'checked' : '' }}>
                        <label for="is_remote" class="text-sm text-gray-700 dark:text-gray-300">Remote</label>
                    </div>

                    {{-- Categories / Locations / Languages --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categories</label>
                            <select name="category_ids[]" multiple required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ collect(old('category_ids'))->contains($category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Locations</label>
                            <select name="location_ids[]" multiple
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ collect(old('location_ids'))->contains($location->id) ? 'selected' : '' }}>
                                        {{ $location->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Languages</label>
                            <select name="language_ids[]" multiple required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                                @foreach ($languages as $language)
                                    <option value="{{ $language->id }}"
                                        {{ collect(old('language_ids'))->contains($language->id) ? 'selected' : '' }}>
                                        {{ $language->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- EAV Attributes --}}
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🧩 Additional Attributes
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($attributes as $attribute)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ $attribute->name }}
                                    </label>
                                    <input type="text" name="attr_{{ $attribute->id }}"
                                        value="{{ old('attr_' . $attribute->id) }}"
                                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-between mt-3">
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white rounded-lg hover:bg-gray-300">
                            ← Cancel
                        </a>
                        <button type="submit"
                            class="border inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            💾 Save Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
