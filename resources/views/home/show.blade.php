@extends('layouts.web')

@section('title', $job->title)

@section('content')
    <div class="container py-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-5">
                <h2 class="text-primary fw-bold mb-3 d-flex align-items-center">
                    <i class="bi bi-briefcase-fill me-3 fs-3"></i> {{ $job->title }}
                </h2>

                <div class="mb-4 text-muted small">
                    <i class="bi bi-building me-1"></i> {{ $job->company_name }} ·
                    <i class="bi bi-clock ms-3 me-1"></i> {{ ucfirst($job->job_type) }} ·
                    <i class="bi bi-geo-alt ms-3 me-1"></i> {{ $job->locations->pluck('city')->join(', ') }}
                </div>

                <div class="mb-5">
                    <p class="fs-5 lh-lg text-dark border-start border-3 ps-4 border-primary">
                        {{ $job->description }}
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded-3 h-100 border border-1">
                            <h5 class="fw-semibold mb-4 text-primary">
                                <i class="bi bi-info-circle me-2"></i>Job Information
                            </h5>

                            <ul class="list-unstyled mb-0 text-dark">
                                <li class="mb-3">
                                    <i class="bi bi-cash-stack me-2 text-muted"></i>
                                    <strong>Salary:</strong> ${{ $job->salary_min }} - ${{ $job->salary_max }}
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-house-door me-2 text-muted"></i>
                                    <strong>Remote:</strong>
                                    <span class="badge {{ $job->is_remote ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $job->is_remote ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-tags me-2 text-muted"></i>
                                    <strong>Categories:</strong>
                                    {{ $job->categories->pluck('name')->join(', ') }}
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-translate me-2 text-muted"></i>
                                    <strong>Languages:</strong>
                                    {{ $job->languages->pluck('name')->join(', ') }}
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-calendar-check me-2 text-muted"></i>
                                    <strong>Published At:</strong>
                                    {{ $job->published_at ? \Carbon\Carbon::parse($job->published_at)->format('F j, Y g:i A') : '—' }}
                                </li>
                            </ul>
                            
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded-3 h-100 border border-1">
                            <h5 class="fw-semibold mb-4 text-primary">
                                <i class="bi bi-sliders me-2"></i>Additional Details
                            </h5>

                            <ul class="list-unstyled text-dark mb-0">
                                @forelse ($job->attributeValues as $attrValue)
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle me-2 text-muted"></i>
                                        <strong>{{ $attrValue->attribute->name }}:</strong>
                                        {{ $attrValue->value }}
                                    </li>
                                @empty
                                    <li class="text-muted">No additional attributes.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-5">
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                        <i class="bi bi-arrow-left-circle me-2"></i> Back to Listings
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
