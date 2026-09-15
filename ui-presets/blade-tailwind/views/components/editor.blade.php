@if ($editor !== null && $source === 'cdn')
    @foreach ($editor->getCdnAssets()['css'] as $stylesheet)
        <link rel="stylesheet" href="{{ $stylesheet }}">
    @endforeach
    @foreach ($editor->getCdnAssets()['js'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach
@endif

<textarea @if ($editor !== null)data-forum-editor="{{ $editor->getDataAttribute() }}" @endif{{ $attributes->merge(['class' => 'px-3 py-1 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md border shadow-sm']) }}>{{ $slot }}</textarea>
