@extends('user.layout.main_layout')
@section('title', 'Add Process')

@section('content')
    <div class="content-page">
        <div class="container-fluid">

            <h4>Add Process for Item: {{ $item->part_number }}</h4>

            <form method="POST" action="{{ route('process.store', $item->id) }}">
                @csrf

                <div class="mb-3">
                    <label>Process Name</label>
                    <input type="text" name="process_name" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Details</label>
                    <textarea name="details" class="form-control"></textarea>
                </div>

                <button class="btn btn-primary">Save</button>
            </form>

        </div>
    </div>
@endsection
