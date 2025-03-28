@extends('layouts.web')

@section('title', 'Available Jobs')

@section('css')
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        .hover-shadow:hover {
            transform: scale(1.02);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08) !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">

        {{-- Filters --}}
        <div class="card shadow-sm border-0 rounded-4 mb-5">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('jobs.index') }}">
                    <div class="row g-4 align-items-end">
                        {{-- Job Type --}}
                        <div class="col-md-3">
                            <label for="job_type" class="form-label fw-semibold">💼 Job Type</label>
                            <select name="job_type" id="job_type" class="form-select shadow-sm">
                                <option value="">All</option>
                                <option value="full-time" {{ request('job_type') === 'full-time' ? 'selected' : '' }}>
                                    Full-Time</option>
                                <option value="part-time" {{ request('job_type') === 'part-time' ? 'selected' : '' }}>
                                    Part-Time</option>
                                <option value="contract" {{ request('job_type') === 'contract' ? 'selected' : '' }}>Contract
                                </option>
                            </select>
                        </div>

                        {{-- Category --}}
                        <div class="col-md-3">
                            <label for="category_id" class="form-label fw-semibold">📂 Category</label>
                            <select name="category_id" id="category_id" class="form-select shadow-sm">
                                <option value="">All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-3">
                            <label for="location_id" class="form-label fw-semibold">📍 Location</label>
                            <select name="location_id" id="location_id" class="form-select shadow-sm">
                                <option value="">All</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Language --}}
                        <div class="col-md-3">
                            <label for="language_id" class="form-label fw-semibold">🗣️ Language</label>
                            <select name="language_id" id="language_id" class="form-select shadow-sm">
                                <option value="">All</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->id }}"
                                        {{ request('language_id') == $language->id ? 'selected' : '' }}>
                                        {{ $language->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dynamic Attributes --}}
                        @foreach ($attributes as $attribute)
                            <div class="col-md-3">
                                <label for="attr_{{ $attribute->id }}" class="form-label fw-semibold">
                                    🧩 {{ $attribute->name }}
                                </label>
                                <select name="attr_{{ $attribute->id }}" id="attr_{{ $attribute->id }}"
                                    class="form-select shadow-sm">
                                    <option value="">All</option>
                                    @foreach ($attribute->values as $value)
                                        <option value="{{ $value->value }}"
                                            {{ request('attr_' . $attribute->id) == $value->value ? 'selected' : '' }}>
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach

                        {{-- Remote --}}
                        <div class="col-md-3">
                            <label for="is_remote" class="form-label fw-semibold">🌍 Remote</label>
                            <select name="is_remote" id="is_remote" class="form-select shadow-sm">
                                <option value="">All</option>
                                <option value="1" {{ request('is_remote') === '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('is_remote') === '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>

                        {{-- Keyword Search --}}
                        <div class="col-md-4">
                            <label for="keyword" class="form-label fw-semibold">🔍 Search Job Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="keyword" id="keyword" class="form-control shadow-sm"
                                    value="{{ request('keyword') }}" placeholder="e.g. Web Developer">
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-md-8 d-flex justify-content-end gap-3 mt-5">
                            <a href="{{ route('jobs.index') }}"
                                class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-arrow-repeat"></i> Reset
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-filter-circle me-1"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        {{-- Heading --}}
        <h1 class="mb-5 text-center fw-bold display-6 text-primary">Available Jobs</h1>

        {{-- Job Cards --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse($jobs as $job)
                <div class="col fade-in">
                    <div class="card h-100 border-0 shadow-lg rounded-4 hover-shadow transition">
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <h5 class="card-title text-primary fw-bold mb-3">
                                    <i class="bi bi-briefcase-fill me-2"></i>{{ $job->title }}
                                </h5>
                                <p class="text-muted small">{{ Str::limit($job->description, 100) }}</p>

                                <ul class="list-unstyled small mt-3 mb-4">
                                    <li><strong><i class="bi bi-building me-1"></i> Company:</strong>
                                        {{ $job->company_name }}</li>
                                    <li><strong><i class="bi bi-cash me-1"></i> Salary:</strong> ${{ $job->salary_min }} -
                                        ${{ $job->salary_max }}</li>
                                    <li><strong><i class="bi bi-house-door me-1"></i> Remote:</strong>
                                        <span class="badge {{ $job->is_remote ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $job->is_remote ? 'Yes' : 'No' }}
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="badge bg-info text-dark text-capitalize">{{ $job->job_type }}</span>
                                <a href="{{ route('jobs.show', $job->id) }}"
                                    class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center p-4 rounded-3">
                        <i class="bi bi-info-circle"></i> No jobs available at the moment.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="row mt-5">
            <div class="col">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
@endsection
