<x-layout>
    <div class="container">
        <div class="row my-4 mx-1">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Usuarios</h1>
                <button class="btn btn-primary btn-sm" onclick="execute('/usuarios/agregar')">
                    <i class="bi bi-plus"></i>
                    <span class="d-none d-sm-inline">Agregar</span>
                </button>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="table-responsive">
                <table id="myTableUsers" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th class="text-end text-nowrap w-auto">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @section('js')
    <script>
        $('#myTableUsers').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route("users.data") }}',
                type: 'GET'
            },
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'actions', orderable: false, searchable: false }
            ],
            pageLength: 10,
            lengthMenu: [[10,25,50,100],[10,25,50,100]],
        });

        function execute(url) {
            window.open(url, '_self');
        }

        function deleteRecord(url) {
            if (confirm('¿Está seguro de eliminar este registro?')) {
                $('<form>', {'action': url, 'method': 'POST'})
                .append($('<input>', {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}))
                .append($('<input>', {type: 'hidden', name: '_method', value: 'DELETE'}))
                .appendTo('body')
                .submit()
                .remove();
            }
        }

        @if (session('success'))
            alert(`{{ session('success') }}`);
        @endif
        @if (session('error'))
            alert(`{{ session('error') }}`);
        @endif
    </script>
    @endsection
</x-layout>
