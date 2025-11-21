@extends('user.layout.main_layout')
@section('title', 'Add Process')

@section('content')
    <div class="content-page">
        <div class="container-fluid">

            <h4>Add Process for Item: {{ $item->part_number }}</h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('process.store', $item->id) }}">
                @csrf

                <div class="mb-3">
                    <label>Process Name</label>
                    @if ($ProcessMaster->count() > 0)
                        <select name="process_id" class="form-control">
                            <option value="">Select Process</option>
                            @foreach ($ProcessMaster as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->process_number }} - {{ $pm->process_name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <button class="btn btn-primary">Save</button>
            </form>

        </div>
    </div>
@endsection
