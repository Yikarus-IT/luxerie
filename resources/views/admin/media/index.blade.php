@extends('layouts.admin')
@section('title', 'Medios')
@section('heading', 'Biblioteca de medios')
@section('content')
<div class="admin-actions"><p>Sube y organiza las imágenes que después podrás asignar a las secciones del sitio.</p></div>
<form class="media-filters" method="GET"><input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar por título o descripción"><select name="type"><option value="">Todos los tipos</option>@foreach($usageTypes as $value=>$label)<option value="{{ $value }}" @selected(request('type')===$value)>{{ $label }}</option>@endforeach</select><button class="button">Filtrar</button></form>
<section class="form-panel media-upload-panel">
    <div class="panel-heading"><div><p class="eyebrow">Nueva imagen</p><h2>Agregar a la biblioteca</h2></div></div>
    <form class="admin-form" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">@csrf
        <div class="form-grid">
            <label>Archivo de imagen<input type="file" name="image" accept="image/jpeg,image/png,image/webp" required><small>JPG, PNG o WebP. Máximo 8 MB.</small></label>
            <label>Tipo<select name="usage_type" required>@foreach($usageTypes as $value => $label)<option value="{{ $value }}" @selected(old('usage_type') === $value)>{{ $label }}</option>@endforeach</select></label>
            <label>Título interno<input type="text" name="title" value="{{ old('title') }}" maxlength="120" placeholder="Ej. Producto abierto sobre fondo crema"></label>
            <label>Texto alternativo<input type="text" name="alt_text" value="{{ old('alt_text') }}" maxlength="255" required placeholder="Describe brevemente lo visible en la imagen"></label>
        </div>
        <div class="form-actions"><button class="button button-dark" type="submit">Subir imagen</button></div>
    </form>
</section>
<section class="media-library" aria-label="Imágenes disponibles">
    @forelse($assets as $asset)
        @php($image = $asset->image())
        <article class="media-card">
            <div class="media-card-preview">@if($image)<img src="{{ $image->getUrl() }}" alt="{{ $asset->alt_text }}">@endif</div>
            <form method="POST" action="{{ route('admin.media.update', $asset) }}" enctype="multipart/form-data">@csrf @method('PUT')
                <label>Título<input type="text" name="title" value="{{ $asset->title }}" maxlength="120"></label>
                <label>Texto alternativo<input type="text" name="alt_text" value="{{ $asset->alt_text }}" maxlength="255" required></label>
                <label>Tipo<select name="usage_type" required>@foreach($usageTypes as $value => $label)<option value="{{ $value }}" @selected($asset->usage_type === $value)>{{ $label }}</option>@endforeach</select></label>
                <label>Reemplazar archivo<input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
                <div class="focal-grid"><label>Foco horizontal %<input type="number" name="focal_x" min="0" max="100" value="{{ $asset->focal_x }}"></label><label>Foco vertical %<input type="number" name="focal_y" min="0" max="100" value="{{ $asset->focal_y }}"></label></div>
                <div class="media-card-meta"><span>{{ $image?->human_readable_size }}</span><span>{{ $image?->mime_type }}</span></div>
                @if($asset->in_use)<span class="status success">En uso</span>@endif
                <button class="button button-small" type="submit">Guardar cambios</button>
            </form>
            <form method="POST" action="{{ route('admin.media.destroy', $asset) }}" data-confirm="¿Eliminar esta imagen? Esta acción también borra el archivo.">@csrf @method('DELETE')<button class="media-delete" type="submit" @disabled($asset->in_use)>{{ $asset->in_use ? 'No se puede eliminar: imagen en uso' : 'Eliminar imagen' }}</button></form>
        </article>
    @empty
        <div class="catalog-empty"><p class="eyebrow">Biblioteca vacía</p><h2>Aún no hay imágenes administrables.</h2><p>Sube la primera imagen con el formulario superior.</p></div>
    @endforelse
</section>
{{ $assets->links() }}
@endsection
