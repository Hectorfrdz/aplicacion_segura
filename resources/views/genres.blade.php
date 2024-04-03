<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Generos</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-navbar />
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h1>Lista de Generos</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarGeneroModal" @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                    Agregar Genero
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
                @foreach($genres as $genre)
                    <tr>
                        <td>{{ $genre->name }}</td>
                        <td>
                            <button type="button" class="btn btn-warning editar-Genero-btn" data-toggle="modal" data-target="#editarGeneroModal" data-id="{{ $genre->id }}" data-name="{{ $genre->name }}"
                            @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                                Editar
                            </button>
                            <button type="button" class="btn btn-danger eliminar-Genero-btn" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="{{ $genre->id }}"
                            @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $genres->links() }} {{-- Paginación --}}
    </div>

    <!-- Modal Agregar Generoes -->
    <div class="modal fade" id="agregarGeneroModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Genero</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarGeneroForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre del Genero</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="agregarGeneroBtn">Guardar</button>
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
                    ¿Estás seguro de que quieres eliminar este Genero?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="eliminarGeneroBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Generoes -->
    <div class="modal fade" id="editarGeneroModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Genero</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarGeneroForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit-name">Nombre del Genero</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <input type="hidden" id="edit-id" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="editarGeneroBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#agregarGeneroBtn').click(function(){
                $.ajax({
                    url: '{{ route("create-genre") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#agregarGeneroForm').serialize(),
                    success: function(response){
                        alert('El Genero se ha creado correctamente.');
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
                var GeneroId = button.data('id'); // ID del Genero obtenido del botón
                var modal = $(this); // Modal de confirmación

                // Función para manejar el clic en el botón de eliminar dentro del modal
                $('#eliminarGeneroBtn').click(function(){
                    // Envía el ID del Genero al controlador para eliminarlo
                    $.ajax({
                        url: '/api/delete-genre/' + GeneroId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response){
                            alert('El Genero se ha eliminado correctamente.');
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
            $('#editarGeneroModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var GeneroId = button.data('id'); // ID del Genero obtenido del botón
                var GeneroNombre = button.data('name'); // Nombre del Genero obtenido del botón
                var modal = $(this); // Modal de edición

                // Llenar el formulario con la información del Genero seleccionado
                modal.find('.modal-body #edit-name').val(GeneroNombre);
                modal.find('.modal-body #edit-id').val(GeneroId);

                // Función para manejar el clic en el botón de guardar dentro del modal
                $('#editarGeneroBtn').click(function(){
                    // Envía los datos actualizados del Genero al controlador
                    $.ajax({
                        url: '/api/update-genre/' + GeneroId,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#editarGeneroForm').serialize(),
                        success: function(response) {
                            alert('El Genero se ha actualizado correctamente.');
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
