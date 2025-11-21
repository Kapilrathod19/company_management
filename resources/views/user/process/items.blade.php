@extends('user.layout.main_layout')
@section('title', 'User | Select Item for Processes')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Select Item to View Processes</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table data-table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Part No</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->part_number ?? '' }}</td>
                                                <td>{{ $item->description ?? '' }}</td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        onclick="openProcessList({{ $item->id }}, '{{ $item->part_number }}')"
                                                        class="btn btn-primary btn-sm mb-2" title="View Processes" data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-diagram-3"></i>
                                                    </a>
                                                    
                                                    <a href="javascript:void(0)"
                                                        onclick="openProcessModal({{ $item->id }}, '{{ $item->part_number }}')"
                                                        class="btn btn-success btn-sm mb-2" title="Add Process"
                                                        data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-plus"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- View Processes Modal -->
    <div class="modal fade" id="viewProcessModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="viewProcessTitle">Item Processes</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="processListContainer">
                    <!-- AJAX Loaded Content Here -->
                    <div class="text-center py-5">
                        <div class="spinner-border"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Process Add Modal -->
    <div class="modal fade" id="processModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="processForm" method="POST">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="processModalTitle">Add Processes</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <table class="table table-bordered" id="processTable">
                            <thead>
                                <tr>
                                    <th>Process</th>
                                    <th width="50px">
                                        <button type="button" class="btn btn-success btn-sm" id="addRowBtn">+</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="process_id[]" class="form-control process-select">
                                            <option value="">Select Process</option>
                                            @foreach ($ProcessMaster as $pm)
                                                <option value="{{ $pm->id }}">
                                                    {{ $pm->process_number }} - {{ $pm->process_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="errorBox" class="text-danger"></div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save All</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Edit Process Modal -->
    <div class="modal fade" id="editProcessModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editProcessForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Process</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body" id="editProcessBody">
                        <!-- AJAX Loaded Form -->
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openProcessList(itemId, partNumber) {
            currentItemId = itemId;

            $("#viewProcessTitle").html(`Processes for Item: <strong>` + partNumber + `</strong>`);

            $("#processListContainer").html(`
                <div class='text-center py-5'>
                    <div class='spinner-border'></div>
                </div>
            `);

            $("#viewProcessModal").modal("show");

            $.get("/user/process-item/" + itemId, function(html) {
                $("#processListContainer").html(html);
            });
        }

        function openProcessModal(itemId, partNumber) {

            document.getElementById("processForm").action =
                "/user/process-item/store-multiple/" + itemId;

            document.getElementById("processModalTitle").innerHTML =
                "Add Process for Item: <strong>" + partNumber + "</strong>";

            $("#processModal").modal('show');
        }

        // Add new process row
        $("#addRowBtn").click(function() {
            let row = `
            <tr>
                <td>
                    <select name="process_id[]" class="form-control process-select">
                        <option value="">Select Process</option>
                        @foreach ($ProcessMaster as $pm)
                            <option value="{{ $pm->id }}">
                                {{ $pm->process_number }} - {{ $pm->process_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                </td>
            </tr>
        `;
            $("#processTable tbody").append(row);
        });

        // Remove row
        $(document).on("click", ".removeRow", function() {
            $(this).closest("tr").remove();
        });

        // Validation before submit
        $("#processForm").submit(function(e) {
            let valid = true;
            $("#errorBox").html("");

            $(".process-select").each(function() {
                if ($(this).val() === "") {
                    valid = false;
                }
            });

            if (!valid) {
                e.preventDefault();
                $("#errorBox").html("Please select process in all rows.");
            }
        });
    </script>

@endsection
