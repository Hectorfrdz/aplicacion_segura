<!-- resources/views/usuarios/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-navbar />
    <div class="container">
        <div class="row mb-3">
                <div class="col-md-6">
                    <h1>Lista de Usuarios</h1>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarUsuarioModal" @if(Auth::user()->role != 1) style="display: none;" @endif>
                        Agregar Usuario
                    </button>
                </div>
            </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Status</th>
                    <th>Rol</th>
                    <th @if(Auth::user()->role != 1) style="display: none;" @endif>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->status == 0)
                            Inactivo
                        @else
                            Activo
                        @endif
                    </td>
                    <td>
                        @if ($user->role == 1)
                            Administrador
                        @elseif ($user->role == 2)
                            Coordinador
                        @else
                            Guest
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning editar-Usuario-btn" data-toggle="modal" data-target="#editarUsuarioModal" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}" data-status="{{ $user->status }}" data-role="{{ $user->role }}"
                        @if(Auth::user()->role != 1) style="display: none;" @endif>
                            Editar
                        </button>
                        <button type="button" class="btn btn-danger eliminar-Usuario-btn" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="{{ $user->id }}"
                        @if(Auth::user()->role != 1) style="display: none;" @endif>
                            Eliminar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        <!-- Modal Agregar Usuario -->
        <div class="modal fade" id="agregarUsuarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarUsuarioForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre del Usuario</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email del Usuario</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña del Usuario</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Estado del Usuario</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Rol del Usuario</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="1">Administrador</option>
                                <option value="2">Coordinador</option>
                                <option value="3">Guest</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="agregarUsuarioBtn">Guardar</button>
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
                    ¿Estás seguro de que quieres eliminar este Usuario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="eliminarUsuarioBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarUsuarioForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit-name">Nombre del Usuario</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email del Usuario</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-status">Estado del Usuario</label>
                            <select class="form-control" id="edit-status" name="status" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-role">Rol del Usuario</label>
                            <select class="form-control" id="edit-role" name="role" required>
                                <option value="1">Administrador</option>
                                <option value="2">Coordinador</option>
                                <option value="3">Guest</option>
                            </select>
                        </div>
                        <input type="hidden" id="edit-id" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="editarUsuarioBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#agregarUsuarioBtn').click(function(){
                $.ajax({
                    url: '{{ route("create-user") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#agregarUsuarioForm').serialize(),
                    success: function(response){
                        alert('El Usuario se ha creado correctamente.');
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

    <script>
        $(document).ready(function(){
            // Se ejecuta cuando se muestra el modal de confirmación
            $('#confirmarEliminarModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var UsuarioId = button.data('id'); // ID del Usuario obtenido del botón
                var modal = $(this); // Modal de confirmación

                // Función para manejar el clic en el botón de eliminar dentro del modal
                $('#eliminarUsuarioBtn').click(function(){
                    // Envía el ID del Usuario al controlador para eliminarlo
                    $.ajax({
                        url: '/api/delete-user/' + UsuarioId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response){
                            alert('El Usuario se ha eliminado correctamente.');
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

    <script>
        $(document).ready(function(){
            // Se ejecuta cuando se muestra el modal de edición
            $('#editarUsuarioModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var UsuarioId = button.data('id'); // ID del Usuario obtenido del botón
                var UsuarioNombre = button.data('name'); // Nombre del Usuario obtenido del botón
                var UsuarioCorreo = button.data('email'); // Nombre del Usuario obtenido del botón
                var UsuarioStatus = button.data('status'); // Nombre del Usuario obtenido del botón
                var UsuarioRol = button.data('role'); // Nombre del Usuario obtenido del botón
                var modal = $(this); // Modal de edición

                // Llenar el formulario con la información del Usuario seleccionado
                modal.find('.modal-body #edit-name').val(UsuarioNombre);
                modal.find('.modal-body #edit-email').val(UsuarioCorreo);
                modal.find('.modal-body #edit-status').val(UsuarioStatus);
                modal.find('.modal-body #edit-role').val(UsuarioRol);
                modal.find('.modal-body #edit-id').val(UsuarioId);

                // Función para manejar el clic en el botón de guardar dentro del modal
                $('#editarUsuarioBtn').click(function(){
                    // Envía los datos actualizados del Usuario al controlador
                    $.ajax({
                        url: '/api/update-user/' + UsuarioId,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#editarUsuarioForm').serialize(),
                        success: function(response) {
                            alert('El Usuario se ha actualizado correctamente.');
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
