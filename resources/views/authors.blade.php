<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Autores</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-navbar />
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h1>Lista de Autores</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarLibroModal" @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                    Agregar Autor
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>
                            <button type="button" class="btn btn-warning editar-libro-btn" data-toggle="modal" data-target="#editarAutorModal" data-id="{{ $author->id }}" data-name="{{ $author->name }}"
                            @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                                Editar
                            </button>
                            <button type="button" class="btn btn-danger eliminar-libro-btn" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="{{ $author->id }}" 
                            @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $authors->links() }} {{-- Paginación --}}
    </div>

    <!-- Modal Agregar Autores -->
    <div class="modal fade" id="agregarLibroModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Autor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarAutorForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre del Autor</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="agregarLibroBtn">Guardar</button>
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
                    ¿Estás seguro de que quieres eliminar este libro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="eliminarAutorBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Autores -->
    <div class="modal fade" id="editarAutorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Autor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarAutorForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit-name">Nombre del Autor</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <input type="hidden" id="edit-id" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="editarAutorBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#agregarLibroBtn').click(function(){
                $.ajax({
                    url: '{{ route("create-author") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#agregarAutorForm').serialize(),
                    success: function(response){
                        alert('El autor se ha creado correctamente.');
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
                var autorId = button.data('id'); // ID del autor obtenido del botón
                var modal = $(this); // Modal de confirmación

                // Función para manejar el clic en el botón de eliminar dentro del modal
                $('#eliminarAutorBtn').click(function(){
                    // Envía el ID del autor al controlador para eliminarlo
                    $.ajax({
                        url: '/api/delete-author/' + autorId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response){
                            alert('El autor se ha eliminado correctamente.');
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
            $('#editarAutorModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var autorId = button.data('id'); // ID del autor obtenido del botón
                var autorNombre = button.data('name'); // Nombre del autor obtenido del botón
                var modal = $(this); // Modal de edición

                // Llenar el formulario con la información del autor seleccionado
                modal.find('.modal-body #edit-name').val(autorNombre);
                modal.find('.modal-body #edit-id').val(autorId);

                // Función para manejar el clic en el botón de guardar dentro del modal
                $('#editarAutorBtn').click(function(){
                    // Envía los datos actualizados del autor al controlador
                    $.ajax({
                        url: '/api/update-author/' + autorId,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#editarAutorForm').serialize(),
                        success: function(response) {
                            alert('El autor se ha actualizado correctamente.');
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
