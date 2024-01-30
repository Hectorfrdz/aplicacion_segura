<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">¡Bienvenido!</h5>
                        <p class="card-text">Haz clic en el botón de abajo para activar tu usuario.</p>
                        
                        <a href="{{$url}}" class="btn btn-primary">
                            Activar
                        </a>
                        
                        <!-- <p>En caso de haber pasado 5 minutos, da clic aquí para volver a mandar el correo:</p>
                        <a href="{{ url('/reenviar-correo/'.$id) }}">Reenviar correo</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>