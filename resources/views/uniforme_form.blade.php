<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($uniforme) ? 'Editar Uniforme' : 'Agregar Uniforme' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 800px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .form-input,
        .form-textarea,
        .form-select {
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            font-size: 16px;
            color: #2c3e50;
            width: 100%;
            box-sizing: border-box;
        }

        .form-textarea {
            height: 100px;
            resize: vertical;
        }

        .form-select {
            appearance: none;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTcgMTBsNS41IDUuNSA1LjUtNS41IiBzdHJva2U9IiM3ZjhjOGQiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 24px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary {
            background-color: #e67e22;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #d35400;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #fff;
            color: #2c3e50;
            border: 1px solid #e9ecef;
        }

        .btn-secondary:hover {
            background-color: #f5f6fa;
            transform: translateY(-2px);
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .existing-photos {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .existing-photo {
            position: relative;
            width: 100px;
            height: 100px;
            flex-shrink: 0;
        }

        .existing-photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .remove-existing-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #ff4444;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-size: 12px;
            line-height: 20px;
            text-align: center;
        }

        .remove-existing-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>{{ isset($uniforme) ? 'Editar Uniforme' : 'Agregar Uniforme' }}</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
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
        
        <form action="{{ isset($uniforme) ? route('uniformes.update', $uniforme->id) : route('uniformes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($uniforme))
                @method('PUT')
            @endif
            
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-input" value="{{ old('nombre', isset($uniforme) ? $uniforme->nombre : '') }}" {{ !isset($uniforme) ? 'required' : '' }}>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-textarea" {{ !isset($uniforme) ? 'required' : '' }}>{{ old('descripcion', isset($uniforme) ? $uniforme->descripcion : '') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria" class="form-select" {{ !isset($uniforme) ? 'required' : '' }}>
                    <option value="">Selecciona una categoría</option>
                    <option value="Industriales" {{ old('categoria', isset($uniforme) ? $uniforme->categoria : '') == 'Industriales' ? 'selected' : '' }}>Industriales</option>
                    <option value="Médicos" {{ old('categoria', isset($uniforme) ? $uniforme->categoria : '') == 'Médicos' ? 'selected' : '' }}>Médicos</option>
                    <option value="Escolares" {{ old('categoria', isset($uniforme) ? $uniforme->categoria : '') == 'Escolares' ? 'selected' : '' }}>Escolares</option>
                    <option value="Corporativos" {{ old('categoria', isset($uniforme) ? $uniforme->categoria : '') == 'Corporativos' ? 'selected' : '' }}>Corporativos</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" name="tipo" id="tipo" class="form-input" value="{{ old('tipo', isset($uniforme) ? $uniforme->tipo : '') }}" placeholder="Ej. Overol, Batas, Playeras, Blusas" {{ !isset($uniforme) ? 'required' : '' }}>
            </div>
            
            @if (isset($uniforme) && $uniforme->fotos->isNotEmpty())
                <div class="form-group">
                    <label>Fotos Existentes</label>
                    <div class="existing-photos">
                        @foreach ($uniforme->fotos as $foto)
                            <div class="existing-photo">
                                <img src="{{ asset('storage/' . $foto->foto_path) }}" alt="Foto existente" class="existing-photo-img">
                                <form action="{{ route('fotos.destroy', $foto->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-existing-btn" onclick="return confirm('¿Estás seguro de eliminar esta foto?')">X</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="form-group">
                <label for="fotos">Nuevas Fotos (puedes seleccionar varias)</label>
                <input type="file" name="fotos[]" id="fotos" class="form-input" multiple accept="image/*">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ isset($uniforme) ? 'Actualizar' : 'Guardar' }}</button>
                <a href="{{ route('uniformes.index') }}" class="btn btn-secondary">Ver Lista</a>
            </div>
        </form>
    </div>
</body>
</html>