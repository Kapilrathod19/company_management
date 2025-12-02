@extends('user.layout.main_layout')
@section('title', 'User | Edit GRN')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="card-title">Edit GRN</h5>
                        </div>
                    </div>
                </div>

                <div class="card">
                    {{-- Validation & Alerts --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Form --}}
                    <div class="card-body">
                        <form id="myform" action="{{ route('grn.update', $grn->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="grn_date" class="form-label">GRN Date</label>
                                    <input type="date" name="grn_date" id="grn_date" class="form-control" value="{{ old('grn_date', $grn->grn_date) }}">
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="Customer" {{ old('category', $grn->category)=='Customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="Supplier" {{ old('category', $grn->category)=='Supplier' ? 'selected' : '' }}>Supplier</option>
                                        <option value="Jobwork" {{ old('category', $grn->category)=='Jobwork' ? 'selected' : '' }}>Jobwork</option>
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="party_name" class="form-label">Party Name</label>
                                    <select name="party_name" id="party_name" class="form-control">
                                        <option value="">Select Party</option>
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="po_no" class="form-label">PO Number</label>
                                    <select name="po_no" id="po_no" class="form-control">
                                        <option value="">Select PO Number</option>
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="party_challan_no" class="form-label">Party Challan No</label>
                                    <input type="text" name="party_challan_no" id="party_challan_no" class="form-control" value="{{ old('party_challan_no', $grn->party_challan_no) }}">
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="party_challan_date" class="form-label">Party Challan Date</label>
                                    <input type="date" name="party_challan_date" id="party_challan_date" class="form-control" value="{{ old('party_challan_date', $grn->party_challan_date) }}">
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="unit_no" class="form-label">Unit No</label>
                                    <select name="unit_no" id="unit_no" class="form-control">
                                        <option value="">Select Unit No</option>
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="part_no" class="form-label">Part No</label>
                                    <select name="part_no" id="part_no" class="form-control">
                                        <option value="">Select Part No</option>
                                    </select>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description', $grn->description) }}</textarea>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="qty" class="form-label">Quantity</label>
                                    <input type="number" name="qty" id="qty" class="form-control" value="{{ old('qty', $grn->qty) }}">
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="number" step="0.01" name="weight" id="weight" class="form-control" value="{{ old('weight', $grn->weight) }}">
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="total_weight" class="form-label">Total Weight</label>
                                    <input type="number" step="0.01" name="total_weight" id="total_weight" class="form-control" value="{{ old('total_weight', $grn->total_weight) }}" readonly>
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="remark" class="form-label">Remark</label>
                                    <input type="text" name="remark" id="remark" class="form-control" value="{{ old('remark', $grn->remark) }}">
                                    <span class="text-danger"></span>
                                </div>

                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function calculateTotalWeight() {
    let qty = parseFloat($('#qty').val()) || 0;
    let weight = parseFloat($('#weight').val()) || 0;
    $('#total_weight').val((qty * weight).toFixed(2));
}
$('#qty, #weight').on('input', calculateTotalWeight);

$(document).ready(function(){

    function resetParty(){ $('#party_name').empty().append('<option value="">Select Party</option>'); resetPO(); resetFields(); }
    function resetPO(){ $('#po_no').empty().append('<option value="">Select PO Number</option>'); resetFields(); }
    function resetFields(){ $('#unit_no,#part_no,#description,#qty,#weight,#total_weight').val(''); }

    function loadParties(category, selectedParty=''){
        resetParty();
        if(!category) return;
        $.ajax({
            url: "{{ route('get.parties.by.category') }}",
            type: "GET",
            data:{category:category},
            success:function(res){
                if(res.status){
                    $.each(res.data,function(k,party){
                        let selected = party.id==selectedParty?'selected':'';
                        $('#party_name').append('<option value="'+party.id+'" '+selected+'>'+party.name+'</option>');
                    });
                    if(selectedParty) loadPoNumbers(category, selectedParty,'{{ $grn->po_no }}');
                }
            }
        });
    }

    function loadPoNumbers(category, partyId, selectedPO=''){
        resetPO();
        if(!category || !partyId) return;
        $.ajax({
            url:"{{ route('get.po.by.party') }}",
            type:"GET",
            data:{category:category, party_id:partyId},
            success:function(res){
                if(res.status){
                    $.each(res.data,function(k,po){
                        let selected = po==selectedPO?'selected':'';
                        $('#po_no').append('<option value="'+po+'" '+selected+'>'+po+'</option>');
                    });
                    if(selectedPO) loadPoItems(category, partyId, selectedPO);
                }
            }
        });
    }

    function loadPoItems(category, partyId, poNo){
        if(!category || !partyId || !poNo) return;
        $.ajax({
            url:"{{ route('grn.getPoItems') }}",
            type:"GET",
            data:{category:category, party_id:partyId, po_no:poNo},
            success:function(res){
                $('#unit_no').html('<option value="">Select Unit</option>');
                $('#part_no').html('<option value="">Select Part</option>');
                res.unit_numbers.forEach(u => $('#unit_no').append(`<option value="${u}">${u}</option>`));
                res.part_numbers.forEach(p => $('#part_no').append(`<option value="${p}">${p}</option>`));

                // Auto-select existing unit/part if matching
                $('#unit_no').val('{{ $grn->unit_no }}');
                $('#part_no').val('{{ $grn->part_no }}');
                $('#description').val('{{ $grn->description }}');
                $('#qty').val('{{ $grn->qty }}');
                $('#weight').val('{{ $grn->weight }}');
                calculateTotalWeight();
            }
        });
    }

    $('#unit_no').on('change', function(){
        $.ajax({
            url:"{{ route('grn.getItemByUnit') }}",
            data:{
                unit_no: $(this).val(),
                category: $('#category').val(),
                party_id: $('#party_name').val(),
                po_no: $('#po_no').val()
            },
            success:function(res){
                if(res.status){
                    $('#part_no').val(res.data.part_no);
                    $('#description').val(res.data.description);
                    $('#qty').val(res.data.qty);
                    $('#weight').val(res.data.weight);
                    calculateTotalWeight();
                }
            }
        });
    });

    $('#part_no').on('change', function(){
        $.ajax({
            url:"{{ route('grn.getItemByPart') }}",
            data:{
                part_no: $(this).val(),
                category: $('#category').val(),
                party_id: $('#party_name').val(),
                po_no: $('#po_no').val()
            },
            success:function(res){
                if(res.status){
                    $('#unit_no').val(res.data.unit_no);
                    $('#description').val(res.data.description);
                    $('#qty').val(res.data.qty);
                    $('#weight').val(res.data.weight);
                    calculateTotalWeight();
                }
            }
        });
    });

    // Initial load
    let initialCategory='{{ $grn->category }}';
    let initialParty='{{ $grn->party_name }}';
    if(initialCategory){ loadParties(initialCategory, initialParty); }

    $('#category').on('change', function(){ loadParties($(this).val()); });
    $('#party_name').on('change', function(){ loadPoNumbers($('#category').val(), $(this).val()); });
    $('#po_no').on('change', function(){ loadPoItems($('#category').val(), $('#party_name').val(), $(this).val()); });

});
</script>
@endsection
