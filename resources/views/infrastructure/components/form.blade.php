
<div id="infra_add_form_container"
   style="@empty($infrastructureOfficielle) display: none; @endempty">

  <h4>Informations générales</h4>

  <!-- Nom de l'infra -->
  <div id="sprytextfield_nom_infra" class="control-group">
    <label class="control-label" for="nom_infra">Nom de l'infrastructure <a href="#" rel="clickover" title="Nom de l'infrastructure" data-content="Un joli nom pour votre infrastructure ! Ce champ est obligatoire."><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <input class="span12" type="text" id="nom_infra" name="nom_infra" value="{{ old('nom_infra', $infrastructure->nom_infra) }}">
      <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caractères minimum.</span></div>
  </div>

  <!-- Description -->
  <div class="control-group" id="sprytextarea1">
    <label class="control-label" for="ch_inf_commentaire">Description <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Mettez &eacute;ventuellement une description rapide de votre infrastructure pour aider les juges &agrave; accepter votre demande. 400 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <textarea name="ch_inf_commentaire" id="ch_inf_commentaire" class="span12" rows="6">{{ old('ch_inf_commentaire', $infrastructure->ch_inf_commentaire) }}</textarea>
      <span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textareaMaxCharsMsg">400 caract&egrave;res maximum.</span></div>
  </div>

  <!-- Image -->
  <div id="sprytextfield" class="control-group">
    <label class="control-label" for="ch_inf_lien_image">Image de votre infrastructure <a href="#" rel="clickover" title="Image de l'infrastructure" data-content="Copiez un lien vers une image qui prouve la construction de l'infrastructure dans l'un des jeux accept&eacute; par le site du Monde GC. Il vous appartient de veiller &agrave; ce que l'image montre clairement cette infrastructure avec les crit&egrave;res requis. Le moindre doute signifiera un refus. Ce champ est obligatoire."><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <input class="span12" type="text" id="ch_inf_lien_image" name="ch_inf_lien_image" value="{{ old('ch_inf_lien_image', $infrastructure->ch_inf_lien_image) }}">
      <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
  </div>

  <!-- Image2 -->
  <div id="sprytextfield2" class="control-group">
    <label class="control-label" for="ch_inf_lien_image2">Image de votre infrastructure n°2 <a href="#" rel="clickover" title="Image de l'infrastructure" data-content="Image suppl&eacute;mentaire. Ce champ est optionnel."><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <input class="span12" type="text" id="ch_inf_lien_image2" name="ch_inf_lien_image2" value="{{ old('ch_inf_lien_image2', $infrastructure->ch_inf_lien_image2) }}">
      <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
  </div>

  <!-- Image3 -->
  <div id="sprytextfield3" class="control-group">
    <label class="control-label" for="ch_inf_lien_image3">Image de votre infrastructure n°3 <a href="#" rel="clickover" title="Image de l'infrastructure" data-content="Image suppl&eacute;mentaire. Ce champ est optionnel."><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <input class="span12" type="text" id="ch_inf_lien_image3" name="ch_inf_lien_image3" value="{{ old('ch_inf_lien_image3', $infrastructure->ch_inf_lien_image3) }}">
      <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
  </div>

    <h4>Lier l'infrastructure aux autres services GC</h4>

   <!-- Lien forum -->
  <div class="control-group" id="sprytextfield6">
    <label class="control-label" for="ch_inf_lien_forum">
        <span class="external-link-icon"
         style="background-image:url('http://www.generation-city.com/forum/new/favicon.png');"></span>
        Lien sur le forum
        <a href="#" rel="clickover" title="Lien vers la page de pr&eacute;sentation" data-content="L'infrastructure doit obligatoirement appartenir &agrave; une ville pr&eacute;sent&eacute;e sur le forum. Mettez le lien vers la page du sujet o&ugrave; est present&eacute;e votre infrastructure"><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <input name="ch_inf_lien_forum" id="ch_inf_lien_forum" class="span12" type="text" value="{{ old('ch_inf_lien_forum', $infrastructure->ch_inf_lien_forum) }}">
      <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
  </div>

   <!-- Lien wiki -->
  <div class="control-group" id="sprytextfield_lien_wiki">
    <label class="control-label" for="lien_wiki">
        <span class="external-link-icon"
         style="background-image:url('https://roxayl.fr/kaleera/images/h4FQp.png');"></span>
        Lien sur le wiki
        <a href="#" rel="clickover" title="Lien vers le Wiki GC" data-content="Si nécessaire, précisez un lien vers le wiki."><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <input name="lien_wiki" id="lien_wiki" class="span12" type="text" value="{{ old('lien_wiki', $infrastructure->lien_wiki) }}">
      <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
  </div>

  <button type="submit" class="btn btn-primary">Enregistrer</button>

</div>
