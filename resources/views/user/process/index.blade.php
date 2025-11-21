<div class="list-group-item bg-light fw-bold d-flex justify-content-between">
    <div style="width: 40%">Process Number</div>
    <div style="width: 40%">Process Name</div>
    <div style="width: 20%">Action</div>
</div>

<ul id="sortable" class="list-group">
    @foreach ($processes as $p)
        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $p->id }}">

            <div style="width: 40%">
                {{ $p->processMaster->process_number }}
            </div>

            <div style="width: 40%">
                {{ $p->processMaster->process_name }}
            </div>

            <div style="width: 20%">

                <a href="javascript:void(0);" onclick="openEditProcess({{ $item->id }}, {{ $p->id }})"
                    class="btn btn-sm btn-info">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <button type="button" class="btn btn-sm btn-danger delete-confirm"
                    data-url="{{ route('process.delete', [$item->id, $p->id]) }}">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>

        </li>
    @endforeach
</ul>
<script>
    function openEditProcess(itemId, processId) {

        $("#editProcessBody").html(`
            <div class='text-center py-5'>
                <div class='spinner-border'></div>
            </div>
        `);

        $("#editProcessModal").modal("show");

        $.get(`/user/process-item/${itemId}/edit/${processId}`, function(response) {

            let html = `
                <div class="form-group">
                    <label>Select Process</label>
                    <select name="process_id" class="form-control">
                        ${response.masters.map(pm => `
                            <option value="${pm.id}"
                                ${pm.id == response.process.process_id ? "selected" : ""}>
                                ${pm.process_number} - ${pm.process_name}
                            </option>
                        `).join('')}
                    </select>
                </div>
            `;

            $("#editProcessBody").html(html);

            $("#editProcessForm").attr("action",
                `/user/process-item/${itemId}/update/${processId}`
            );
        });
    }

    $("#editProcessForm").off("submit").on("submit", function(e) {
        e.preventDefault();

        let url = $(this).attr("action");
        let formData = $(this).serialize();

        $("#editProcessBody").html(`
            <div class='text-center py-5'>
                <div class='spinner-border'></div>
            </div>
        `);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function() {

                $("#editProcessModal").modal("hide");

                openProcessList(currentItemId);

                Swal.fire({
                    icon: "success",
                    text: "Process updated!",
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    });


    // DELETE
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

                    $.ajax({
                        url: deleteUrl,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function() {
                            button.closest("li").remove();
                            Swal.fire({
                                icon: "success",
                                text: "Process updated!",
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }
                    });

                }
            });
        });
    });
    
    $("#sortable").sortable({
        placeholder: "ui-state-highlight",
        update: function(event, ui) {

            let order = [];
            $("#sortable li").each(function() {
                order.push($(this).data("id"));
            });

            $.ajax({
                url: "/user/process-item/{{ $item->id }}/sort",
                method: "POST",
                data: {
                    order: order,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        text: "Position updated!",
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    $("#sortable").disableSelection();
</script>
