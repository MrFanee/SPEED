@extends('layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Upload Failure</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Upload Failure</li>
                <li class="breadcrumb-item active">Data Gagal Upload</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="container">
            <a href="{{ route('upload_failure.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mt-3">Module: {{ $failure->module }}</h6>
                    {{-- <p>{{ $failure->module }}</p> --}}

                    <h6 class="fw-bold">Error Message: {{ $failure->error_message }}</h6>
                    {{-- <p>{{ $failure->error_message }}</p> --}}

                    <h6 class="fw-bold">Raw Data:</h6>
                    <pre class="bg-light p-3">{{ json_encode($failure->raw_data, JSON_PRETTY_PRINT) }}</pre>

                    <form action="{{ route('upload_failure.retry', $failure->id) }}" method="POST">
                        @csrf
                        @if($failure->status !== 'success')
                            <button class="btn btn-sm btn-outline-success">
                                <i class="bi bi-upload"></i> Reupload
                            </button>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection