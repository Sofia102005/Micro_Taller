@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modificar Nota</h1>

    <!-- Formulario para editar la nota -->
    <form action="{{ route('notas.update', $nota->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="actividad">Actividad</label>
            <input type="text" class="form-control" id="actividad" name="actividad" value="{{ $nota->actividad }}" required>
        </div>

        <div class="form-group">
            <label for="nota">Nota</label>
            <input type="number" class="form-control" id="nota" name="nota" value="{{ $nota->nota }}" step="0.01" min="0" max="5" required>
        </div>

        <input type="hidden" name="codEstudiante" value="{{ $nota->codEstudiante }}">

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection