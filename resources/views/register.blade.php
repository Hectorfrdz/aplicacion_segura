<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6Le6G18pAAAAAAwGu5EtUDYnmMV4kC2DilVq38_E"></script>
    <title>Incio</title>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Registro</div>
                <div class="card-body">
                    <form method="POST" id="demo-form" >
                        @csrf
                        <!-- Campo de nombre -->
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nombre</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <br>
                        <!-- Campo de correo -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Correo Electrónico</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <br>
                        <!-- Campo de contraseña -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <br>
                        <!-- Confirmación de contraseña -->
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirmar Contraseña</label>
                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                <div id="password-confirmation-error" class="alert alert-danger" style="display: none;"></div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                            <button class="g-recaptcha btn btn-primary"
                                data-sitekey="6LdOYV8pAAAAAPIQ9oMKW-cBicj9TzhFZFqIcaU-" 
                                data-callback='onSubmit' 
                                data-action='submit'>
                                Registrarse
                            </button>  
                            </div>
                            <div id="error-container"></div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-3 text-center">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function onSubmit(token) {
        // Obtén los datos del formulario
        const formData = new FormData(document.getElementById("demo-form"));
        
        // Limpia mensajes de error anteriores
        const errorContainer = document.getElementById("error-container");
            errorContainer.innerHTML = '';

        // Validar contraseñas
        const password = formData.get('password');
        const passwordConfirmation = formData.get('password_confirmation');

        if (password !== passwordConfirmation) {
            // Mostrar mensaje de error
            document.getElementById('password-confirmation-error').innerText = 'Las contraseñas no coinciden.';
            document.getElementById('password-confirmation-error').style.display = 'block';
            return; // Detener el envío del formulario
        } else {
            // Limpiar mensaje de error si las contraseñas coinciden
            document.getElementById('password-confirmation-error').style.display = 'none';
        }

        // Realiza la solicitud a la ruta de la API
        axios.post('/api/registro-usuario', formData)
        .then(response => {
            // Redirigir al login
            window.location.href = '/';
        })
        .catch(error => {
            // Muestra mensajes de error
            if (error.response.status === 422) {
                // Recibe los errores
                const errors = error.response.data.error;

                for (const field in errors) {
                    const errorMessages = errors[field];
                    
                    // Crea un contenedor para los mensajes de error de un campo
                    const fieldErrorContainer = document.createElement('div');
                    fieldErrorContainer.className = 'field-error-container';

                    // Itera sobre los mensajes de error y crea elementos span para cada uno
                    for (const errorMessage of errorMessages) {
                        const errorSpan = document.createElement('span');
                        errorSpan.className = 'text-danger';
                        errorSpan.textContent = errorMessage;

                        // Agrega el span al contenedor de errores del campo
                        fieldErrorContainer.appendChild(errorSpan);
                    }

                    // Agrega el contenedor de errores del campo al contenedor principal
                    errorContainer.appendChild(fieldErrorContainer);
                }
            } else {
                // Recibe los errores;
                const errorSpan = document.createElement('span');
                errorSpan.className = 'text-danger';
                errorSpan.textContent = error.response.data.error;

                // Inserta el mensaje de error en el contenedor principal
                errorContainer.appendChild(errorSpan);
            }
        });
    }
</script>
</body>
</html>