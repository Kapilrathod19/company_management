@extends('admin.layout.main_layout')

@section('content')
    <div class="content-page">
        <div class="container-fluid">

            <h4>User Permission: {{ $user->name }}</h4>

            <form action="{{ route('admin.user.permissions.store', $user->id) }}" method="POST">
                @csrf

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Module</th>

                            <th>
                                View
                                <input type="checkbox" id="checkAllView" class="m-1">
                            </th>

                            <th>
                                Add
                                <input type="checkbox" id="checkAllAdd" class="m-1">
                            </th>

                            <th>
                                Edit
                                <input type="checkbox" id="checkAllEdit" class="m-1">
                            </th>

                            <th>
                                Delete
                                <input type="checkbox" id="checkAllDelete" class="m-1">
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($modules as $module)
                            @php $perm = $permissions[$module] ?? null; @endphp
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $module)) }}</td>

                                <td>
                                    <input type="checkbox" class="viewBox" name="permissions[{{ $module }}][view]"
                                        {{ $perm?->view ? 'checked' : '' }}>
                                </td>

                                <td>
                                    <input type="checkbox" class="addBox" name="permissions[{{ $module }}][add]"
                                        {{ $perm?->add ? 'checked' : '' }}>
                                </td>

                                <td>
                                    <input type="checkbox" class="editBox" name="permissions[{{ $module }}][edit]"
                                        {{ $perm?->edit ? 'checked' : '' }}>
                                </td>

                                <td>
                                    <input type="checkbox" class="deleteBox" name="permissions[{{ $module }}][delete]"
                                        {{ $perm?->delete ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button class="btn btn-success">Save Permissions</button>
            </form>

        </div>
    </div>

    <script>
        document.getElementById('checkAllView').addEventListener('change', function() {
            document.querySelectorAll('.viewBox').forEach(cb => cb.checked = this.checked);
        });

        document.getElementById('checkAllAdd').addEventListener('change', function() {
            document.querySelectorAll('.addBox').forEach(cb => cb.checked = this.checked);
        });

        document.getElementById('checkAllEdit').addEventListener('change', function() {
            document.querySelectorAll('.editBox').forEach(cb => cb.checked = this.checked);
        });

        document.getElementById('checkAllDelete').addEventListener('change', function() {
            document.querySelectorAll('.deleteBox').forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
