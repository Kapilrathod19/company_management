@extends('user.layout.main_layout')
@section('title', 'Item Processes')

@section('content')
    <div class="content-page">
        <div class="container-fluid">

            <h4>Processes for Item: <b>{{ $item->part_number }}</b></h4>

            <a href="{{ route('process.create', $item->id) }}" class="btn btn-primary mb-3">
                + Add Process
            </a>

            <div class="list-group-item bg-light fw-bold d-flex justify-content-between">
                <div style="width: 40%">Process Name</div>
                <div style="width: 40%">Details</div>
                <div style="width: 20%">Action</div>
            </div>

            <ul id="sortable" class="list-group">
                @foreach ($processes as $index => $p)
                    <li class="list-group-item d-flex justify-content-between align-items-center"
                        data-id="{{ $p->id }}">

                        <div style="width: 40%">
                            {{ $p->process_name }}
                        </div>

                        <div style="width: 40%">
                            {{ $p->details ?? '—' }}
                        </div>

                        <div style="width: 20%">
                            <a href="{{ route('process.edit', [$item->id, $p->id]) }}" class="btn btn-sm btn-info"><i
                                    class="bi bi-pencil-square"></i></a>

                            <button type="button" class="btn btn-sm btn-danger delete-confirm"
                                data-url="{{ route('process.delete', [$item->id, $p->id]) }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>

                    </li>
                @endforeach
            </ul>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sorting
        $("#sortable").sortable({
            update: function(event, ui) {
                var order = [];
                $("#sortable li").each(function(index) {
                    order.push($(this).data('id'));
                });

                $.post("{{ route('process.sort', $item->id) }}", {
                    _token: "{{ csrf_token() }}",
                    order: order
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-confirm').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    let deleteUrl = this.getAttribute('data-url');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This process will be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = deleteUrl;

                            form.innerHTML = `
                                @csrf
                                @method('DELETE')
                            `;

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

@section('footer')
