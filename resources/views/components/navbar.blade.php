<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Seguridad</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{route('logout')}}">
                <button type="button" class="btn btn-outline-primary me-2">
                Salir
                </button>
            </a>
            </li>
            <li>
                <h1 class="me-2">{{Auth::user()->name}}</h1>
            </li>
            <li>
            @if(Auth::user()->role == 1)
                <p>Admin</p>
            @else
                <p>Normal</p>
            @endif
            </li>
        </ul>
        </div>
    </div>
    </nav>
</body>
</html>