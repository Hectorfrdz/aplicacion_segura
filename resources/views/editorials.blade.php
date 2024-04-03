<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Editoriales</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-navbar />
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h1>Lista de Editoriales</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarEditorialModal">
                    Agregar Editorial
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Direccion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($editorials as $editorial)
                    <tr>
                        <td>{{ $editorial->name }}</td>
                        <td>{{ $editorial->phone }}</td>
                        <td>{{ $editorial->email }}</td>
                        <td>{{ $editorial->direction }}</td>
                        <td>
                            <button type="button" class="btn btn-warning editar-Editorial-btn" data-toggle="modal" data-target="#editarEditorialModal" data-id="{{ $editorial->id }}" data-name="{{ $editorial->name }}"
                            data-direction="{{ $editorial->direction }}" data-phone="{{ $editorial->phone }}" data-email="{{ $editorial->email }}">
                                Editar
                            </button>
                            <button type="button" class="btn btn-danger eliminar-Editorial-btn" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="{{ $editorial->id }}">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $editorials->links() }} {{-- Paginación --}}
    </div>

    <!-- Modal Agregar Editoriales -->
    <div class="modal fade" id="agregarEditorialModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Editorial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarEditorialForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre de la Editorial</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label for="name">Correo de la Editorial</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                            <label for="name">Telefono de la Editorial</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                            <label for="name">Direccion de la Editorial</label>
                            <input type="text" class="form-control" id="direction" name="direction" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="agregarEditorialBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar este Editorial?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="eliminarEditorialBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Editoriales -->
    <div class="modal fade" id="editarEditorialModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Editorial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarEditorialForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit-name">Nombre de la Editorial</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                            <label for="edit-email">Correo de la Editorial</label>
                            <input type="text" class="form-control" id="edit-email" name="email" required>
                            <label for="edit-phone">Telefono de la Editorial</label>
                            <input type="text" class="form-control" id="edit-phone" name="phone" required>
                            <label for="edit-direction">Direccion de la Editorial</label>
                            <input type="text" class="form-control" id="edit-direction" name="direction" required>
                        </div>
                        <input type="hidden" id="edit-id" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="editarEditorialBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- agregar -->
    <script>
        $(document).ready(function(){
            $('#agregarEditorialBtn').click(function(){
                $.ajax({
                    url: '{{ route("create-editorial") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#agregarEditorialForm').serialize(),
                    success: function(response){
                        alert('El Editorial se ha creado correctamente.');
                        window.location.reload();
                    },
                    error: function(xhr, status, error){
                        var errors = xhr.responseJSON.error;
                        var errorMessage = "Errores de validación:\n";
                        $.each(errors, function(key, value) {
                            errorMessage += key + ': ' + value.join(', ') + '\n'; // Une los errores del mismo campo
                        });
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>

    <!-- eliminar -->
    <script>
        $(document).ready(function(){
            // Se ejecuta cuando se muestra el modal de confirmación
            $('#confirmarEliminarModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var EditorialId = button.data('id'); // ID del Editorial obtenido del botón
                var modal = $(this); // Modal de confirmación

                // Función para manejar el clic en el botón de eliminar dentro del modal
                $('#eliminarEditorialBtn').click(function(){
                    // Envía el ID del Editorial al controlador para eliminarlo
                    $.ajax({
                        url: '/api/delete-editorial/' + EditorialId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response){
                            alert('El Editorial se ha eliminado correctamente.');
                            window.location.reload();
                        },
                        error: function(xhr, status, error){
                            var errors = xhr.responseJSON.error;
                            var errorMessage = "Errores de validación:\n";
                            $.each(errors, function(key, value) {
                                errorMessage += key + ': ' + value.join(', ') + '\n'; // Une los errores del mismo campo
                            });
                            alert(errorMessage);
                        }
                    });
                });
            });
        });
    </script>

    <!-- editar -->
    <script>
        $(document).ready(function(){
            // Se ejecuta cuando se muestra el modal de edición
            $('#editarEditorialModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var EditorialId = button.data('id'); // ID del Editorial obtenido del botón
                var EditorialNombre = button.data('name'); // Nombre del Editorial obtenido del botón
                var EditorialDirection = button.data('direction'); // Direccion del Editorial obtenido del botón
                var EditorialPhone = button.data('phone'); // Telefono del Editorial obtenido del botón
                var EditorialEmail = button.data('email'); // Correo del Editorial obtenido del botón
                var modal = $(this); // Modal de edición

                // Llenar el formulario con la información del Editorial seleccionado
                modal.find('.modal-body #edit-name').val(EditorialNombre);
                modal.find('.modal-body #edit-direction').val(EditorialDirection);
                modal.find('.modal-body #edit-phone').val(EditorialPhone);
                modal.find('.modal-body #edit-email').val(EditorialEmail);
                modal.find('.modal-body #edit-id').val(EditorialId);

                // Función para manejar el clic en el botón de guardar dentro del modal
                $('#editarEditorialBtn').click(function(){
                    // Envía los datos actualizados del Editorial al controlador
                    $.ajax({
                        url: '/api/update-editorial/' + EditorialId,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#editarEditorialForm').serialize(),
                        success: function(response) {
                            alert('El Editorial se ha actualizado correctamente.');
                            window.location.reload();
                        },
                        error: function(xhr, status, error){
                            var errors = xhr.responseJSON.error;
                            var errorMessage = "Errores de validación:\n";
                            $.each(errors, function(key, value) {
                                errorMessage += key + ': ' + value.join(', ') + '\n'; // Une los errores del mismo campo
                            });
                            alert(errorMessage);
                        }
                    });
                });
            });
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
