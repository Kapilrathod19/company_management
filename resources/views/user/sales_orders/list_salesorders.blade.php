@extends('user.layout.main_layout')
@section('title', 'User | Sales Order List')
@section('content')
    <style>
        .table-danger {
            background-color: #ffe5e5 !important;
        }
    </style>

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">Sales Order</h5>
                            </div>
                            <div class="header-action">
                                @if (isset($permissions['sales_order']) && $permissions['sales_order']->add == 1)
                                    <a class="btn btn-primary" href="{{ route('sales_order.create') }}" role="button"><i
                                            class="bi bi-plus"></i> Add Sales Order</a>
                                @endif
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
                                            <th>Customer Name</th>
                                            <th>PO No</th>
                                            <th>PO Date</th>
                                            <th>Component No</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Weight</th>
                                            <th>Total Weight</th>
                                            <th>Remaining Qty</th>
                                            <th>Unit No</th>
                                            <th>Rev No</th>
                                            <th>Delivery Date</th>
                                            <th>Drawing Attachment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesorders as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->party->name ?? '' }}</td>
                                                <td>{{ $item->po_no ?? '' }}</td>
                                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($item->po_date)) }}</td>
                                                <td>{{ $item->item->part_number ?? '' }}</td>
                                                <td>{{ $item->description ?? '' }}</td>
                                                <td>{{ $item->unit ?? '' }}</td>
                                                <td>{{ $item->qty ?? '' }}</td>
                                                <td>{{ $item->weight ?? '' }}</td>
                                                <td>{{ $item->total_weight ?? '' }}</td>
                                                <td>{{ $item->remain_qty ?? '' }}</td>
                                                <td>{{ $item->unit_no ?? '' }}</td>
                                                <td>{{ $item->rev_no ?? '' }}</td>
                                                <td class="text-nowrap">
                                                    {{ date('d-m-Y', strtotime($item->delivery_date)) }}</td>
                                                <td>
                                                    @if ($item->drawing_attachment)
                                                        <a href="{{ asset('drawing_attachment/' . $item->drawing_attachment) }}"
                                                            class="btn btn-outline-primary btn-sm" target="_blank">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> View
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($permissions['sales_order']) && $permissions['sales_order']->edit == 1)
                                                        <a class="btn btn-primary btn-sm mb-2"
                                                            href="{{ route('sales_order.edit', $item->id) }}"
                                                            role="button">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                    @if (isset($permissions['sales_order']) && $permissions['sales_order']->delete == 1)
                                                        <a class="btn btn-danger btn-sm delete-confirm mb-2"
                                                            href="javascript:void(0)" data-id="{{ $item->id }}"
                                                            role="button">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                    @endif
                                                    <button class="btn btn-success btn-sm mb-2 show-process-btn"
                                                        data-id="{{ $item->id }}" data-part_no="{{ $item->part_no }}"
                                                        data-unit_no="{{ $item->unit_no }}" title="Planning Process"
                                                        data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-diagram-3"></i>
                                                    </button>
                                                    <button class="btn btn-info btn-sm mb-2 show-documents-btn"
                                                        data-id="{{ $item->id }}" data-po_no="{{ $item->po_no }}"
                                                        title="Upload Documents" data-toggle="tooltip" data-placement="top">
                                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                                    </button>
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

    <div class="modal fade" id="processModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">All Processes
                        <small class="text-muted d-block" id="processMeta"></small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Process Number</th>
                                <th>Process Name</th>
                                <th>Employee</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="processData"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="saveProcessBtn">
                        Save Process Assignment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="documentsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Document
                        <small class="text-muted d-block" id="documentsMeta"></small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="uploadDocumentForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="titleInput" class="form-label">Document Title</label>
                                    <input type="text" id="titleInput" name="title" class="form-control"
                                        maxlength="255" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="fileInput" class="form-label">Choose File (Image, PDF, etc.)</label>
                                    <input type="file" id="fileInput" name="document" class="form-control"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
                                    <small class="text-muted">(Max 10MB per file)</small>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Uploaded Documents</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="documentsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Document Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="documentsData">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="uploadDocumentBtn">
                        <i class="bi bi-cloud-upload"></i> Upload Document
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let currentSalesOrderId = '';

        function loadDocuments() {
            fetch("{{ route('sales_order.get_documents', ':id') }}".replace(':id', currentSalesOrderId))
                .then(res => res.json())
                .then(data => {
                    let rows = '';
                    if (data.documents.length === 0) {
                        rows = '<tr><td colspan="3" class="text-center text-muted">No documents uploaded yet</td></tr>';
                    } else {
                        data.documents.forEach((doc, index) => {
                            rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>
                                    <a href="{{ asset('sales_order_documents') }}/${doc.document}" 
                                       target="_blank" class="text-primary">
                                        <i class="bi bi-file-earmark"></i> ${doc.title}
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-doc-btn" 
                                        data-doc-id="${doc.id}" type="button">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });
                    }
                    document.getElementById('documentsData').innerHTML = rows;
                });
        }

        // Event delegation for delete document buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-doc-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-doc-btn');
                const docId = btn.getAttribute('data-doc-id');

                Swal.fire({
                    title: 'Delete Document?',
                    text: `Are you sure you want to delete this document?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('sales_order.delete_document', ':id') }}".replace(':id', docId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status) {
                                    Swal.fire('Deleted!', 'Document deleted successfully.', 'success');
                                    loadDocuments();
                                } else {
                                    Swal.fire('Error!', 'Failed to delete document.', 'error');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Error!', 'An error occurred while deleting.', 'error');
                            });
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-confirm').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ URL::to('user/sales_order/destroy') }}/" + id;

                        }
                    });
                });
            });

            // Document upload button handler
            document.querySelectorAll('.show-documents-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const po_no = this.getAttribute('data-po_no');
                    currentSalesOrderId = id;

                    document.getElementById('documentsMeta').innerHTML =
                        `PO No: <strong>${po_no}</strong>`;

                    document.getElementById('fileInput').value = '';
                    document.getElementById('documentsData').innerHTML =
                        '<tr><td colspan="3" class="text-center">Loading...</td></tr>';

                    loadDocuments();
                    new bootstrap.Modal(document.getElementById('documentsModal')).show();
                });
            });

            // Upload document button handler (single file with title)
            document.getElementById('uploadDocumentBtn').addEventListener('click', function() {
                const form = document.getElementById('uploadDocumentForm');
                const fileInput = document.getElementById('fileInput');
                const titleInput = document.getElementById('titleInput');
                const file = fileInput.files[0];
                const title = titleInput.value.trim();

                if (!file) {
                    Swal.fire('Warning!', 'Please select a file to upload.', 'warning');
                    return;
                }
                if (!title) {
                    Swal.fire('Warning!', 'Please enter a document title.', 'warning');
                    return;
                }

                const formData = new FormData();
                formData.append('document', file);
                formData.append('title', title);
                formData.append('_token', '{{ csrf_token() }}');

                // Show loading state
                const btn = this;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>Uploading...';

                fetch("{{ route('sales_order.upload_document', ':id') }}".replace(':id',
                        currentSalesOrderId), {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;

                        if (data.status) {
                            Swal.fire('Success!', data.message, 'success');
                            fileInput.value = '';
                            titleInput.value = '';
                            loadDocuments();
                        } else {
                            Swal.fire('Error!', 'Failed to upload document.', 'error');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        Swal.fire('Error!', 'An error occurred while uploading.', 'error');
                    });
            });
        });

        const employees = @json($employees);

        document.querySelectorAll('.show-process-btn').forEach(btn => {

            btn.addEventListener('click', function() {

                currentComponentNo = this.dataset.part_no;
                currentUnitNo = this.dataset.unit_no;

                document.getElementById('processMeta').innerHTML =
                    `Unit No: <strong>${currentUnitNo}</strong>`;

                document.getElementById('processData').innerHTML =
                    '<tr><td colspan="5">Loading...</td></tr>';

                let url = "{{ route('sales_order.process.get', ':id') }}"
                    .replace(':id', currentComponentNo);

                url += `?component_no=${currentComponentNo}&unit_no=${currentUnitNo}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {

                        let rows = '';

                        data.forEach((p, index) => {

                            let selectedEmployee = p.process_assignment?.employee_id ?? '';
                            let selectedDate = p.process_assignment?.process_date ?? '';

                            let empOptions = '<option value="">Select Employee</option>';
                            employees.forEach(emp => {
                                empOptions += `
                                <option value="${emp.id}"
                                    ${emp.id == selectedEmployee ? 'selected' : ''}>
                                    ${emp.emp_no} - ${emp.employee_name}
                                </option>`;
                            });

                            rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${p.process_master.process_number}</td>
                                <td>${p.process_master.process_name}</td>
                                <td>
                                    <select class="form-control"
                                        name="processes[${p.process_master.id}][employee_id]">
                                        ${empOptions}
                                    </select>
                                </td>
                                <td>
                                    <input type="date"
                                        class="form-control"
                                        value="${selectedDate ?? ''}"
                                        name="processes[${p.process_master.id}][date]">
                                </td>
                            </tr>`;
                        });

                        document.getElementById('processData').innerHTML = rows;
                    });

                new bootstrap.Modal(document.getElementById('processModal')).show();
            });
        });


        let currentComponentNo = '';
        let currentUnitNo = '';

        document.getElementById('saveProcessBtn').addEventListener('click', function() {

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('component_no', currentComponentNo);
            formData.append('unit_no', currentUnitNo);

            let hasError = false;

            document.querySelectorAll('#processData tr').forEach(row => {

                // reset previous error state
                row.classList.remove('table-danger');

                let select = row.querySelector('select');
                let date = row.querySelector('input[type="date"]');

                if (!select || !date) return;

                let employeeVal = select.value;
                let dateVal = date.value;

                if (
                    (employeeVal && !dateVal) ||
                    (!employeeVal && dateVal)
                ) {
                    hasError = true;
                    row.classList.add('table-danger');
                }

                if (employeeVal && dateVal) {
                    let id = select.name.match(/\d+/)[0];
                    formData.append(`processes[${id}][employee_id]`, employeeVal);
                    formData.append(`processes[${id}][date]`, dateVal);
                }
            });

            if (hasError) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Process Assignment',
                    text: 'Please select BOTH Employee and Date for highlighted rows.'
                });
                return;
            }

            fetch("{{ route('process.assign') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status) {
                        Swal.fire({
                            icon: "success",
                            text: "Process Assignment Saved!",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $('#processModal').modal('hide');
                    }
                });
        });
    </script>
@endsection
