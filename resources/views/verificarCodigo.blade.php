<!-- resources/views/codigo/ingresar.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Código</title>
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6Le6G18pAAAAAAwGu5EtUDYnmMV4kC2DilVq38_E"></script>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Incio</title>
   
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Ingresar Código</div>
                    <div class="card-body">
                        <form method="POST" id="demo-form" action="/api/verificar-usuario">
                            @csrf
                            <!-- Campo de código -->
                            <div class="form-group row">
                                <input type="hidden" name="id_user" value="{{ request()->query('user') }}">
                                <label for="code" class="col-md-4 col-form-label text-md-right">Código</label>
                                <div class="col-md-6">
                                    <input id="code" type="text" name="code" required>
                                    <div id="error-container"></div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" 
                                        class="g-recaptcha btn btn-primary"
                                        data-sitekey="6LdOYV8pAAAAAPIQ9oMKW-cBicj9TzhFZFqIcaU-" 
                                        data-callback='onSubmit' 
                                        data-action='submit'>
                                        Verificar Código
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
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

        // Realiza la solicitud a la ruta de la API
        axios.post('/api/verificar-usuario', formData)
        .then(response => {
            console.log(response);
            window.location.href = '/index';
        })
        .catch(error => {
            console.log(error.response.status);
            if (error.response.status === 422) {
                 // Recibe los errores
                const errors = error.response.data.error;
                const errorContainer = document.getElementById("error-container");
                
                // Limpia mensajes de error anteriores
                errorContainer.innerHTML = '';

                // Muestra mensajes de error
                for (const field in errors) {
                    const errorMessage = errors[field][0];
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'text-danger';
                    errorSpan.textContent = errorMessage;
                    errorContainer.appendChild(errorSpan);
                }
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
