
<form method="POST" action="{{ route('chapter.store', ['roleplay' => $roleplay]) }}">
    @csrf

    <div class="form-group">
        <label for="chapter_name_field">Nom</label>
        <input type="text" id="chapter_name_field" class="form-control span9"
               name="name" value="{{ old('name', $chapter->name) }}"/>
    </div>

    <div class="form-group">
        <label for="chapter_summary_field">Résumé</label>
        <textarea id="chapter_summary_field" class="form-control span9"
               name="summary">{{ old('summary', $chapter->summary) }}</textarea>
    </div>

    <div class="form-group">
        <label for="chapter_content_field">Texte</label>
        <textarea id="chapter_content_field" class="wysiwyg form-control span9"
                  name="content">{{ old('content', $chapter->content) }}</textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            Sauvegarder
        </button>
    </div>

</form>

<script>
    (function() {
        initTinymce();
    })();
</script>
