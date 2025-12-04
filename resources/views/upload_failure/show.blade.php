@extends('layouts.main')

@section('title', 'Detail Upload Failure')

@section('content')
    <div class="pagetitle">
        <h1>Upload Failure</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('upload_failure.index') }}">Upload Failure</a></li>
                <li class="breadcrumb-item active">Data Gagal Upload</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="container">

            <div class="card">
                <div class="card-body">
                    <h6 class="mt-3">Module: {{ $failure->module }}</h6>

                    <h6>Error Message: {{ $failure->error_message }}</h6>

                    {{-- <h6>Detail Data Gagal ({{ count($rows) }} baris)</h6> --}}

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-2 small">
                                <thead class="table-light text-center">
                                    <tr>
                                        @foreach(array_keys($rows[0]) as $key)
                                            <th>{{ $headers[$key] ?? ucwords(str_replace('_', ' ', $key)) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($rows as $row)
                                        <tr>
                                            @foreach($row as $value)
                                                <td>{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content gap-2 mt-3">
                        <a href="{{ route('upload_failure.index') }}" class="btn btn-sm btn-outline-secondary mb-3">Back</a>

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

        </div>
    </section>
@endsection