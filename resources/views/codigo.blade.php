<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header bg-primary text-white">
                        Confirmación de Código
                    </div>
                    <div class="card-body">
                        <p>Codigo de verificacion:</p>
                        <h2 class="text-center text-danger">{{ $code }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>