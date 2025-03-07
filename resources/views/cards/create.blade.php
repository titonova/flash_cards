@extends('layouts.app')


@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    <!-- Highlight.js -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/atom-one-dark.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
    <script type="text/javascript" src="/js/dist/highlight-js/blade.min.js"></script>

    @verbatim


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const easyMDE = new EasyMDE({
                element: document.getElementById('solution-textarea'),
                toolbar: ["bold", "italic", "heading", "|",
                    "quote", "preview", "side-by-side", "fullscreen", "guide",
                    {
                        name: "custom",
                        action: (editor) => {
                            // Surround the selection with <pre><code> tags and place the cursor within the tags
                            var selectedText = editor.codemirror.getSelection();
                            var text = "<pre><code>" + selectedText + "</code></pre>";
                            editor.codemirror.replaceSelection(text);
                            editor.codemirror.focus();

                            // set the cursor within the <pre><code> tags
                            var cursorPosition = editor.codemirror.getCursor();
                            cursorPosition.ch = cursorPosition.ch - 13;
                            editor.codemirror.setCursor(cursorPosition);


                        },
                        className: "fa fa-code",
                    },
                ],
                renderingConfig: {
                    codeSyntaxHighlighting: true,

                },
                spellChecker: false,

            });

        });
    </script>
    @endverbatim
@endpush

@push('styles')
    <style>

    /* Hack to remove the border and background whenebver the new code block btn is selected */
    .editor-toolbar button.active, .editor-toolbar button:hover {
         background: inherit;
        border-color: white !important;
    }


    </style>

@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card dark-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                        Create Flash Card
                        <a type="button" href="{{ url('/dashboard') }}" class="btn btn-sm btn-inverse">
                            Dashboard
                        </a>
                </div>

                <div class="card-body">
                    @include('alerts.status')

                    {!! Form::open(['class' => 'form', 'route' => 'card.save']) !!}

                        {{ Form::hidden( 'card_id', $cardRow->id ?? null ) }}

                        <!-- Card Category -->
                        <div class="form-group">
                            {!! Form::label('category', 'Category') !!}
                            <span class="red-text">*</span>
                            <br />

                            {!! Form::select( 'category', $existingCats->pluck('name', 'id'), $cardRow->category ?? null, ['class' => 'form-control form-control-lg', 'dusk' => 'card_category', 'required'] ) !!}
                        </div>

                        <!-- Difficulty rating of the card -->
                        <div class="form-group">
                            {!! Form::label('difficulty', 'Difficulty ( 1 - 5 )') !!}
                            <span class="red-text">*</span>
                            {!! Form::number('difficulty', $cardRow->difficulty ?? null, ['class' => 'form-control', 'min' => 1, 'max' => 5, 'dusk' => 'difficulty', 'placeholder' => '3']) !!}
                        </div>

                        <!-- Problem on the front of the card -->
                        <div class="form-group">
                            {!! Form::label('problem', 'Problem') !!}
                            <span class="red-text">*</span>
                             {!! Form::textarea('problem', $cardRow->problem ?? null, ['dusk' => 'card_problem','class' => 'form-control texteditor', 'placeholder' => 'Front of Card  ( Max 1000 Characters )', 'maxlength' => 1000]) !!}
                        </div>

                        <!-- Solution on the back of the card -->
                        <div class="form-group">
                            {!! Form::label('solution', 'Solution') !!}
                            <span class="red-text">*</span>
                             {!! Form::textarea('solution', $cardRow->solution ?? null, ['dusk' => 'card_solution','class' => 'form-control ', 'placeholder' => 'Back of Card ( Max 1000 Characters )', 'maxlength' => 1000,'id'=>'solution-textarea']) !!}
                            <div class="solution-preview-render"></div>
                        </div>

                        <!-- Tags related to the card -->
                        <div class="form-group">
                            {!! Form::label('tags', 'Tags') !!}
                            <select multiple id="tag-input" class="form-control" name="tags[]">
                                @if (isset($cardTags))
                                    @foreach($cardTags->pluck('tag') as $cardTag)
                                        <option value="{{ $cardTag }}">{{ $cardTag }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <span class="red-text"><em> ( * Denotes required fields )</em></span>

                        <div class="text-center">
                            {!! Form::submit(  ( isset($cardRow->id) ? "Update Card" : "Save Card" ) , ['class' => 'btn btn-outline', 'dusk' => 'save_card']) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
