<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Libros</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-navbar />
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h1>Lista de Libros</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#agregarLibroModal" @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                    Agregar Libro
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>ISBN</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Libro</th>
                    <th @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>Acciones</th>
                </tr>
            </thead>
            @if(isset($books) && $books->count() > 0)
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->name }}</td>
                        <td>{{ $book->ISBN }}</td>
                        <td>{{ $book->author->name }}</td>
                        <td>{{ $book->genre->name }}</td>
                        <td>{{ $book->editorial->name }}</td>
                        <td>
                            <button type="button" class="btn btn-warning editar-Book-btn" data-toggle="modal" data-target="#editarLibroModal" data-id="{{ $book->id }}" 
                            data-name="{{ $book->name }}" data-author="{{ $book->author->id }}" data-editorial="{{ $book->editorial->id }}" data-genre="{{ $book->genre->id }}" data-isbn="{{ $book->ISBN }}" @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                                Editar
                            </button>
                            <button type="button" class="btn btn-danger eliminar-Book-btn" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="{{ $book->id }}" @if(Auth::user()->role != 1 && Auth::user()->role != 2) style="display: none;" @endif>
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            @else
                <p>No hay libros disponibles.</p>
            @endif
        </table>
        {{ $books->links() }} {{-- Paginación --}}
    </div>

    <!-- Modal Agregar Libro -->
    <div class="modal fade" id="agregarLibroModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarLibroForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre del Libro</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="ISBN">ISBN</label>
                            <input type="text" class="form-control" id="ISBN" name="ISBN" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Autor</label>
                            <select class="form-control" id="author" name="author" required>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="genre">Género</label>
                            <select class="form-control" id="genre" name="genre" required>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editorial">Editorial</label>
                            <select class="form-control" id="editorial" name="editorial" required>
                                @foreach($editorials as $editorial)
                                    <option value="{{ $editorial->id }}">{{ $editorial->name }}</option>
                                @endforeach
                            </select>
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
                    ¿Estás seguro de que quieres eliminar este Libro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="eliminarLibroBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Editar Editoriales -->
    <div class="modal fade" id="editarLibroModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Editorial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarLibroForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit-name">Nombre del Libro</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-ISBN">ISBN</label>
                            <input type="text" class="form-control" id="edit-ISBN" name="ISBN" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-author">Autor</label>
                            <select class="form-control" id="edit-author" name="author" required>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-genre">Género</label>
                            <select class="form-control" id="edit-genre" name="genre" required>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-editorial">Editorial</label>
                            <select class="form-control" id="edit-editorial" name="editorial" required>
                                @foreach($editorials as $editorial)
                                    <option value="{{ $editorial->id }}">{{ $editorial->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="edit-id" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="editarLibroBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- crear -->
    <script>
        $(document).ready(function(){
            $('#agregarLibroBtn').click(function(){
                $.ajax({
                    url: '{{ route("create-book") }}',
                    type: 'POST',
                    data: $('#agregarLibroForm').serialize(),
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        alert('El Libro se ha creado correctamente.');
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
                var LibroId = button.data('id'); // ID del Libro obtenido del botón
                var modal = $(this); // Modal de confirmación

                // Función para manejar el clic en el botón de eliminar dentro del modal
                $('#eliminarLibroBtn').click(function(){
                    // Envía el ID del Libro al controlador para eliminarlo
                    $.ajax({
                        url: '/api/delete-book/' + LibroId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response){
                            alert('El Libro se ha eliminado correctamente.');
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
            $('#editarLibroModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var LibroId = button.data('id');
                var LibroNombre = button.data('name'); 
                var LibroAuthor = button.data('author'); 
                var LibroISBN = button.data('isbn');
                var LibroEditorial = button.data('editorial');
                var LibroGenre = button.data('genre'); 
                var modal = $(this);

                // Llenar el formulario con la información del Editorial seleccionado
                modal.find('.modal-body #edit-name').val(LibroNombre);
                modal.find('.modal-body #edit-ISBN').val(LibroISBN);
                modal.find('.modal-body #edit-author').val(LibroAuthor);
                modal.find('.modal-body #edit-editorial').val(LibroEditorial);
                modal.find('.modal-body #edit-genre').val(LibroGenre);
                modal.find('.modal-body #edit-id').val(LibroId);

                // Función para manejar el clic en el botón de guardar dentro del modal
                $('#editarLibroBtn').click(function(){
                    // Envía los datos actualizados del Editorial al controlador
                    $.ajax({
                        url: '/api/update-book/' + LibroId,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#editarLibroForm').serialize(),
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
