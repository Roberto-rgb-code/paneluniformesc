<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Uniformes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        .uniformes-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .uniforme-card {
            flex: 1;
            min-width: 200px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .uniforme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .uniforme-images {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 10px;
        }

        .uniforme-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .uniforme-image.placeholder {
            width: 100px;
            height: 100px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 14px;
            border-radius: 5px;
        }

        .uniforme-content {
            padding: 10px;
            text-align: center;
        }

        .uniforme-content h3 {
            font-size: 18px;
            color: #2c3e50;
            margin: 10px 0;
        }

        .uniforme-content p {
            font-size: 14px;
            color: #7f8c8d;
            margin: 5px 0;
        }

        .uniforme-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #e67e22;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #d35400;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: transparent;
            color: #2c3e50;
            border: 1px solid #2c3e50;
        }

        .btn-secondary:hover {
            background-color: rgba(44, 62, 80, 0.1);
            transform: translateY(-2px);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Uniformes</h1>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <a href="{{ route('uniformes.create') }}" class="btn btn-primary">Agregar Nuevo Uniforme</a>
        
        @if ($uniformes->isEmpty())
            <p>No hay uniformes registrados.</p>
        @else
            <div class="uniformes-grid">
                @foreach ($uniformes as $uniforme)
                    <div class="uniforme-card">
                        <div class="uniforme-images">
                            @if ($uniforme->fotos->isNotEmpty())
                                @foreach ($uniforme->fotos as $foto)
                                    <img src="{{ asset('storage/' . $foto->foto_path) }}" alt="{{ $uniforme->nombre }}" class="uniforme-image">
                                @endforeach
                            @else
                                <div class="uniforme-image placeholder">No hay foto</div>
                            @endif
                        </div>
                        <div class="uniforme-content">
                            <h3>{{ $uniforme->nombre }}</h3>
                            <p>{{ $uniforme->descripcion }}</p>
                            <p>Categoría: {{ $uniforme->categoria }}</p>
                            <p>Tipo: {{ $uniforme->tipo }}</p>
                            <div class="uniforme-actions">
                                <a href="{{ route('uniformes.edit', $uniforme->id) }}" class="btn btn-primary">Editar</a>
                                <form action="{{ route('uniformes.destroy', $uniforme->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('¿Estás seguro de eliminar este uniforme?')">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>