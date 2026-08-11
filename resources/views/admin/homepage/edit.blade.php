@extends('layouts.admin')
@section('title', 'Página de inicio')
@section('heading', 'Página de inicio')
@section('content')
<div class="admin-actions"><p>Edita el contenido principal y asigna imágenes desde la biblioteca de medios.</p><a class="button" href="{{ route('admin.homepage.preview') }}" target="_blank">Vista previa del borrador ↗</a></div>
<form class="homepage-editor" method="POST" action="{{ route('admin.homepage.update') }}">@csrf @method('PUT')
    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">Organización</p><h2>Visibilidad y orden</h2></div></div><div class="section-control-grid">
        @foreach(['hero' => 'Portada', 'about' => 'Introducción', 'benefits' => 'Beneficios', 'product' => 'Producto destacado', 'collection' => 'Catálogo', 'ritual' => 'Ritual', 'testimonial' => 'Testimonio'] as $key => $label)
            <div class="section-control"><label class="check"><input type="checkbox" name="layout[{{ $key }}][visible]" value="1" @checked(old("layout.$key.visible", data_get($layout, "$key.visible")))> {{ $label }}</label><label>Orden<input type="number" min="0" max="100" name="layout[{{ $key }}][order]" value="{{ old("layout.$key.order", data_get($layout, "$key.order")) }}" required></label></div>
        @endforeach
    </div></section>
    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">01</p><h2>Portada</h2></div></div><div class="form-grid">
        <label>Texto superior<input name="content[hero][eyebrow]" value="{{ old('content.hero.eyebrow', data_get($content, 'hero.eyebrow')) }}" required></label>
        @include('admin.homepage._media-select', ['label' => 'Imagen principal', 'slot' => 'hero'])
        <label>Título<input name="content[hero][heading]" value="{{ old('content.hero.heading', data_get($content, 'hero.heading')) }}" required></label>
        <label>Texto destacado<input name="content[hero][highlight]" value="{{ old('content.hero.highlight', data_get($content, 'hero.highlight')) }}" required></label>
        <label class="span-2">Descripción<textarea name="content[hero][body]" rows="3" required>{{ old('content.hero.body', data_get($content, 'hero.body')) }}</textarea></label>
        <label>Texto del botón<input name="content[hero][button]" value="{{ old('content.hero.button', data_get($content, 'hero.button')) }}" required></label>
    </div></section>

    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">02</p><h2>Introducción de marca</h2></div></div><div class="form-grid">
        <label>Texto superior<input name="content[about][eyebrow]" value="{{ old('content.about.eyebrow', data_get($content, 'about.eyebrow')) }}" required></label>
        <label class="span-2">Título<textarea name="content[about][heading]" rows="2" required>{{ old('content.about.heading', data_get($content, 'about.heading')) }}</textarea></label>
        <label class="span-2">Descripción<textarea name="content[about][body]" rows="3" required>{{ old('content.about.body', data_get($content, 'about.body')) }}</textarea></label>
    </div></section>

    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">03</p><h2>Carrusel de beneficios</h2></div></div><div class="form-grid">
        <label>Texto superior<input name="content[benefits][eyebrow]" value="{{ old('content.benefits.eyebrow', data_get($content, 'benefits.eyebrow')) }}" required></label>
        <label class="span-2">Título<textarea name="content[benefits][heading]" rows="2" required>{{ old('content.benefits.heading', data_get($content, 'benefits.heading')) }}</textarea></label>
        <div class="span-2 repeatable-list" data-repeatable="benefits">
        @foreach(data_get($content, 'benefits.captions') as $index => $caption)
            <div class="repeatable-row" data-repeatable-row>
            @include('admin.homepage._media-select', ['label' => 'Imagen '.($index + 1), 'slot' => 'benefit_'.$index])
            <label>Leyenda {{ $index + 1 }}<input name="content[benefits][captions][{{ $index }}]" value="{{ old('content.benefits.captions.'.$index, $caption) }}" required></label>
            <button class="text-link repeatable-remove" type="button">Quitar</button></div>
        @endforeach
        </div><button class="button button-small span-2" type="button" data-repeatable-add="benefits">Agregar diapositiva</button>
    </div></section>

    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">04</p><h2>Producto destacado</h2></div></div><div class="form-grid">
        <label>Texto superior<input name="content[product][eyebrow]" value="{{ old('content.product.eyebrow', data_get($content, 'product.eyebrow')) }}" required></label>
        <label>Título<input name="content[product][heading]" value="{{ old('content.product.heading', data_get($content, 'product.heading')) }}" required></label>
        <label class="span-2">Descripción<textarea name="content[product][body]" rows="3" required>{{ old('content.product.body', data_get($content, 'product.body')) }}</textarea></label>
        @foreach(range(0, 2) as $index)
            @include('admin.homepage._media-select', ['label' => 'Imagen del carrusel '.($index + 1), 'slot' => 'product_'.$index])
            <label>Beneficio {{ $index + 1 }}<input name="content[product][benefits][{{ $index }}]" value="{{ old('content.product.benefits.'.$index, data_get($content, 'product.benefits.'.$index)) }}" required></label>
        @endforeach
    </div></section>

    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">05</p><h2>Ritual</h2></div></div><div class="form-grid">
        @include('admin.homepage._media-select', ['label' => 'Imagen del ritual', 'slot' => 'ritual'])
        <label>Texto superior<input name="content[ritual][eyebrow]" value="{{ old('content.ritual.eyebrow', data_get($content, 'ritual.eyebrow')) }}" required></label>
        <label class="span-2">Título<input name="content[ritual][heading]" value="{{ old('content.ritual.heading', data_get($content, 'ritual.heading')) }}" required></label>
        <label class="span-2">Nota de seguridad<textarea name="content[ritual][note]" rows="2" required>{{ old('content.ritual.note', data_get($content, 'ritual.note')) }}</textarea></label>
    </div></section>

    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">06</p><h2>Testimonio</h2></div></div><div class="form-grid">
        @include('admin.homepage._media-select', ['label' => 'Imagen del testimonio', 'slot' => 'testimonial'])
        <label>Texto superior<input name="content[testimonial][eyebrow]" value="{{ old('content.testimonial.eyebrow', data_get($content, 'testimonial.eyebrow')) }}" required></label>
        <label class="span-2">Testimonio<textarea name="content[testimonial][quote]" rows="2" required>{{ old('content.testimonial.quote', data_get($content, 'testimonial.quote')) }}</textarea></label>
        <label>Descripción<textarea name="content[testimonial][body]" rows="3" required>{{ old('content.testimonial.body', data_get($content, 'testimonial.body')) }}</textarea></label>
        <label>Nota legal<textarea name="content[testimonial][note]" rows="3" required>{{ old('content.testimonial.note', data_get($content, 'testimonial.note')) }}</textarea></label>
    </div></section>
    <section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">Historial</p><h2>Revisiones publicadas</h2></div></div><div class="revision-list">@forelse($revisions as $revision)<div class="revision-row"><span><strong>{{ $revision->created_at->format('d/m/Y H:i') }}</strong><small>{{ $revision->user?->name ?? 'Sistema' }}</small></span><button class="button button-small" type="submit" form="restore-{{ $revision->id }}">Restaurar como borrador</button></div>@empty<p>Aún no existen revisiones anteriores.</p>@endforelse</div></section>
    <div class="homepage-schedule"><label>Programar publicación<input type="datetime-local" name="scheduled_for" value="{{ old('scheduled_for', $homepage?->scheduled_for?->format('Y-m-d\TH:i')) }}"></label>@if($homepage?->scheduled_for)<small>Programada para {{ $homepage->scheduled_for->format('d/m/Y H:i') }}</small>@endif</div>
    <div class="homepage-editor-actions"><button class="button" type="submit" name="action" value="draft">Guardar borrador</button><button class="button" type="submit" name="action" value="schedule">Programar</button><button class="button button-dark" type="submit" name="action" value="publish">Publicar cambios</button></div>
</form>
@foreach($revisions as $revision)<form id="restore-{{ $revision->id }}" method="POST" action="{{ route('admin.homepage.revisions.restore', $revision) }}">@csrf</form>@endforeach
@endsection
