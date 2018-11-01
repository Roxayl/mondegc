tinymce.init({    
selector: "textarea.wysiwyg",
convert_urls: false,
remove_script_host: false,
language : 'fr_FR',
    plugins: [
         "advlist autolink link image lists charmap preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code media nonbreaking",
         "save table contextmenu directionality emoticons paste textcolor"
   ],
   content_css: "css/content.css",
   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview media fullpage | forecolor backcolor emoticons", 
   style_formats: [
        {title: 'texte en gras', inline: 'strong'},
        {title: 'texte en italique', inline: 'em'},
        {title: 'titre large', block: 'h1'},
        {title: 'titre', block: 'h3'},
        {title: 'sous-titre', selector: 'h4'},
        {title: 'tableau', selector: 'table', classes: 'table'}
    ]

 });