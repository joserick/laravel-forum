@php
    $wireModelAttributes = $attributes->whereStartsWith('wire:model')->getAttributes();
    $wireModel = reset($wireModelAttributes) ?: null;
    $usesEditor = $editor !== null && $wireModel !== null;
    $editorOptions = $usesEditor && ! empty($editor->getOptions()) ? json_encode($editor->getOptions()) : null;
@endphp

@if ($usesEditor && $source === 'cdn')
    @foreach ($editor->getCdnAssets()['css'] as $stylesheet)
        <link rel="stylesheet" href="{{ $stylesheet }}">
    @endforeach
    @foreach ($editor->getCdnAssets()['js'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach
@endif

<div {!! isset($xShow) && !empty($xShow) ? "x-show=\"{$xShow}\"" : "" !!} class="mb-4">
    @if (isset($label))
        <label for="{{ $id }}" class="block mb-2 font-medium text-gray-900 dark:text-slate-400">{{ $label }}</label>
    @endif

    @if ($usesEditor)
        <div
            wire:ignore
            wire:key="forum-editor-{{ $wireModel }}"
            @if ($editorOptions) data-forum-editor-options="{{ $editorOptions }}" @endif
            x-data="forumEditor($wire.entangle('{{ $wireModel }}'), '{{ $editor->getDataAttribute() }}')"
            x-init="init($refs.editor)">
            <div x-ref="editor" x-show="quill"></div>
            <textarea
                x-ref="fallback"
                x-show="!quill"
                x-model="model"
                id="{{ $id }}"
                class="block p-2.5 w-full min-h-36 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                {{ $attributes->whereDoesntStartWith('wire:model') }}></textarea>
        </div>
    @else
        <textarea
            id="{{ $id }}"
            class="block p-2.5 w-full min-h-36 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            {{ $attributes }}></textarea>
    @endif

    @include ('forum::components.form.error')
</div>
