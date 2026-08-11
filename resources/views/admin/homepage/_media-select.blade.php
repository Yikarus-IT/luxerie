<label class="media-selector">{{ $label }}
    <select name="media[{{ $slot }}]" data-media-select>
        <option value="">Usar imagen original del sitio</option>
        @foreach($mediaAssets as $asset)
            <option value="{{ $asset->id }}" data-preview="{{ $asset->image()?->getUrl() }}" @selected(($selectedMedia[$slot] ?? null) == $asset->id)>{{ $asset->title ?: $asset->image()?->file_name }} — {{ $asset->alt_text }}</option>
        @endforeach
    </select>
    <img class="media-selector-preview" src="{{ $mediaAssets->firstWhere('id', $selectedMedia[$slot] ?? null)?->image()?->getUrl() ?? '' }}" alt="" @if(!isset($selectedMedia[$slot])) hidden @endif>
</label>
