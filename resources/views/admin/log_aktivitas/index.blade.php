@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid">


    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>No</th>
                    <th>User</th>
                    <th>Aktivitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ $log->aktivitas }}</td>
                    <td class="text-center">
                        {{ $log->created_at->format('d-m-Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Log aktivitas belum tersedia
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
