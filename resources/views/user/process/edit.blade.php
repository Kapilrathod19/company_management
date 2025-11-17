@extends('user.layout.main_layout')
@section('title', 'Edit Process')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <h4>Edit Process</h4>

            <form method="POST" action="{{ route('process.update', [$item->id, $process->id]) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Process Name</label>
                    <input type="text" name="process_name" class="form-control" value="{{ $process->process_name }}">
                </div>

                <div class="mb-3">
                    <label>Details</label>
                    <textarea name="details" class="form-control">{{ $process->details }}</textarea>
                </div>

                <button class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>
@endsection
