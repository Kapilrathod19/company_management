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
                    @if ($ProcessMaster->count() > 0)
                        <select name="process_id" class="form-control">
                            <option value="">Select Process</option>
                            @foreach ($ProcessMaster as $pm)
                                <option value="{{ $pm->id }}" {{ $process->process_id == $pm->id ? 'selected' : '' }}>
                                    {{ $pm->process_number }} - {{ $pm->process_name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <button class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>
@endsection
