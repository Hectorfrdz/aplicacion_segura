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
                        <div id="error-container"></div>
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

        // Validar contraseñas
        const password = formData.get('password');
        const passwordConfirmation = formData.get('password_confirmation');

        if (password !== passwordConfirmation) {
            // Mostrar mensaje de error
            document.getElementById('password-confirmation-error').innerText = 'Las contraseñas no coinciden.';
            document.getElementById('password-confirmation-error').style.display = 'block';
            return; // Detener el envío del formulario
        }

        // Realiza la solicitud a la ruta de la API
        axios.post('/api/registro-usuario', formData)
        .then(response => {
            // Manejar respuesta exitosa
            console.log(response.data.message);
            // Redirigir a la página deseada (por ejemplo, /dashboard)
            window.location.href = '/login';
        })
        .catch(error => {
            // Restablecer mensaje de error de confirmación de contraseña
            document.getElementById('password-confirmation-error').style.display = 'none';

            // Mostrar mensajes de error generales
            const generalErrorContainer = document.getElementById('general-error');
            
            if (generalErrorContainer) {
                generalErrorContainer.remove();
            }

            if (error.response.status === 422) {
                // Errores de validación
                const errors = error.response.data.error;
                Object.keys(errors).forEach(field => {
                    // Muestra los mensajes de error en tu formulario
                    const inputElement = document.getElementById(field);
                    const errorContainer = document.createElement('div');
                    errorContainer.className = 'alert alert-danger';
                    errorContainer.innerHTML = errors[field][0];
                    // Elimina mensajes de error previos
                    const existingErrorContainer = inputElement.nextElementSibling;
                    if (existingErrorContainer) {
                        existingErrorContainer.remove();
                    }
                    // Inserta el mensaje de error después del campo
                    inputElement.parentNode.insertBefore(errorContainer, inputElement.nextSibling);
                });
            } else {
                // Recibe los errores;
                const errorContainer = document.getElementById("error-container");
                // Limpia mensajes de error anteriores
                errorContainer.innerHTML = '';
                // Muestra los mensajes
                const errorSpan = document.createElement('span');
                errorSpan.className = 'text-danger';
                errorSpan.textContent = error.response.data.error;
                errorContainer.appendChild(errorSpan);    
            }
        });
    }
</script>
</body>
</html>