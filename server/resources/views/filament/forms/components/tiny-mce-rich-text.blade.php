<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    class="relative z-0"
>
    <div
        x-data="{ state: $wire.entangle('{{ $getStatePath() }}'), initialized: false }"
        x-init="(() => {
            $nextTick(() => {
                tinymce.createEditor('tiny-editor-{{ $getId() }}', {
                    license_key: 'gpl',
                    branding: false,
                    promotion: false,
                    base_url: window.filamentData.tinymceBaseUrl,
                    target: $refs.tinymce,
                    deprecation_warnings: false,
                    skin: {
                        light: 'oxide',
                        dark: 'oxide-dark',
                        system: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide',
                    }[typeof theme === 'undefined' ? 'light' : theme],
                    @if ($getContentCss())
                    content_css: @js($getContentCss()),
                    @else
                    content_css: {
                        light: 'default',
                        dark: 'dark',
                        system: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default',
                    }[typeof theme === 'undefined' ? 'light' : theme],
                    @endif
                    max_height: {{ $getMaxHeight() }},
                    min_height: {{ $getMinHeight() }},
                    plugins: @js($getPlugins()),
                    external_plugins: @js($getExternalPlugins()),
                    toolbar: '{{ $getToolbar() }}',
                    toolbar_mode: 'sliding',
@if ($getLrcLanguages())
                    content_langs: [
                    @foreach ($getLrcLanguages() as $lang)
                        { title: '{{ $lang->title }}', code: '{{ $lang->code }}' },
                    @endforeach
                    ],
@endif
@if ($getImageUploadUrl())
                    images_upload_url: @js($getImageUploadUrl()),
                    image_caption: true,
                    image_advtab: true,
                    file_picker_callback: null,
@endif
                    automatic_uploads: true,
                    setup: function(editor) {
                        editor.on('blur', function(e) {
                            state = editor.getContent()
                        })

                        editor.on('init', function(e) {
                            if (state != null) {
                                editor.setContent(state)
                            }
                        })

                        editor.on('OpenWindow', function(e) {
                            target = e.target.container.closest('.fi-modal')
                            if (target) target.setAttribute('x-trap.noscroll', 'false')

                            target = e.target.container.closest('.jetstream-modal')
                            if (target) {
                                targetDiv = target.children[1]
                                targetDiv.setAttribute('x-trap.inert.noscroll', 'false')
                            }
                        })

                        editor.on('CloseWindow', function(e) {
                            target = e.target.container.closest('.fi-modal')
                            if (target) target.setAttribute('x-trap.noscroll', 'isOpen')

                            target = e.target.container.closest('.jetstream-modal')
                            if (target) {
                                targetDiv = target.children[1]
                                targetDiv.setAttribute('x-trap.inert.noscroll', 'show')
                            }
                        })

@if ($getLrcCharSequences())
{{-- This is probably better off as a plugin for reuse. --}}
                        editor.ui.registry.addButton('lrc_charsequences', {
                            icon: 'insert-character',
                            tooltip: 'Insert special character',
                            onAction: () => {
                                const seqButtons = [
@foreach ($getLrcCharSequences() as $i => $seq)
                                    { type: 'button', name: 'seq_{{ $i }}', text: @js($seq[1]) },
@endforeach
                                ];
                                const nameToVal = {
@foreach ($getLrcCharSequences() as $i => $seq)
                                    'seq_{{ $i }}': @js($seq[0]),
@endforeach
                                };
                                editor.windowManager.open({
                                    title: 'Insert special character',
                                    size: 'medium',
                                    body: {
                                        type: 'panel',
                                        items: seqButtons
                                    },
                                    buttons: [
                                        { type: 'cancel', name: 'close', text: 'Close' }
                                    ],
                                    onAction: (api, details) => {
                                        const content = nameToVal[details.name];
                                        if (typeof content !== 'undefined') {
                                            editor.insertContent(content);
                                            api.close();
                                        }
                                    }
                                });
                            }
                        });
@endif

                        function putCursorToEnd() {
                            editor.selection.select(editor.getBody(), true);
                            editor.selection.collapse(false);
                        }

                        $watch('state', function(newstate) {
                            // unfortunately livewire doesn't provide a way to 'unwatch' so this listener sticks
                            // around even after this component is torn down. Which means that we need to check
                            // that editor.container exists. If it doesn't exist we do nothing because that means
                            // the editor was removed from the DOM
                            if (editor.container && newstate !== editor.getContent()) {
                                editor.resetContent(newstate || '');
                                putCursorToEnd();
                            }
                        });
                    },
                    {{ $getCustomConfigs() }}
                }).render();
            });

            // We initialize here because if the component is first loaded from within a modal DOMContentLoaded
            // won't fire and if we want to register a Livewire.hook listener Livewire.hook isn't available from
            // inside the once body
            if (!window.tinyMceInitialized) {
                window.tinyMceInitialized = true;
                $nextTick(() => {
                    Livewire.hook('morph.removed', (el, component) => {
                        if (el.el.nodeName === 'INPUT' && el.el.getAttribute('x-ref') === 'tinymce') {
                            tinymce.get(el.el.id)?.remove();
                        }
                    });
                });
            }
        })()"
        x-cloak
        class="overflow-hidden"
        wire:ignore
    >
        @unless($isDisabled())
            <input
                id="tiny-editor-{{ $getId() }}"
                type="hidden"
                x-ref="tinymce"
            >
        @else
            <div
                x-html="state"
                @style([
                    'max-height: '.$getPreviewMaxHeight().'px' => $getPreviewMaxHeight() > 0,
                    'min-height: '.$getPreviewMinHeight().'px' => $getPreviewMinHeight() > 0,
                ])
                class="block w-full max-w-none rounded-lg border border-gray-300 bg-white p-3 opacity-70 shadow-sm transition duration-75 prose dark:prose-invert dark:border-gray-600 dark:bg-gray-700 dark:text-white overflow-y-auto"
            ></div>
        @endunless
    </div>
</x-dynamic-component>
