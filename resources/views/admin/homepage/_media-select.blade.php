<label>{{ $label }}
    <select name="media[{{ $slot }}]">
        <option value="">Usar imagen original del sitio</option>
        @foreach($mediaAssets as $asset)
            <option value="{{ $asset->id }}" @selected(($selectedMedia[$slot] ?? null) == $asset->id)>{{ $asset->title ?: $asset->image()?->file_name }} — {{ $asset->alt_text }}</option>
        @endforeach
    </select>
</label>
