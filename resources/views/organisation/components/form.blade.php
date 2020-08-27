
    @csrf

    <div id="sprytextfield1" class="control-group">
        <label class="control-label" for="name">Nom de l'organisation <a href="#" rel="clickover" title="Nom de l'organisation" data-content="60 caractères maximum."><i class="icon-info-sign"></i></a></label>
        <div class="controls">
            <input class="input-xlarge" name="name" type="text" id="name" value="{{old('name', $organisation->name)}}" maxlength="90">
            <span class="textfieldMaxCharsMsg">60 caractères max.</span>
            <span class="textfieldMinCharsMsg">2 caractères minimum.</span>
            <span class="textfieldRequiredMsg">Ce champ est obligatoire.</span>
        </div>
    </div>

    <div id="sprytextfield2" class="control-group">
        <label class="control-label" for="flag">URL du drapeau <a href="#" rel="clickover" title="URL du drapeau" data-content="190 caractères maximum."><i class="icon-info-sign"></i></a></label>
        <div class="controls">
            <input class="input-xxlarge" name="flag" type="text" id="flag" value="{{old('flag', $organisation->flag)}}" maxlength="190">
            <span class="textfieldMaxCharsMsg">190 caractères max.</span>
            <span class="textfieldInvalidFormatMsg">Vous devez saisir un URL.</span>
            <span class="textfieldRequiredMsg">Ce champ est obligatoire.</span>
        </div>
    </div>

    <div id="sprytextfield3" class="control-group">
        <label class="control-label" for="logo">URL du logo <a href="#" rel="clickover" title="URL du logo" data-content="190 caractères maximum. Facultatif."><i class="icon-info-sign"></i></a></label>
        <div class="controls">
            <input class="input-xxlarge" name="logo" type="text" id="logo" value="{{old('logo', $organisation->logo)}}" maxlength="190">
            <span class="textfieldMaxCharsMsg">190 caractères max.</span>
            <span class="textfieldInvalidFormatMsg">Vous devez saisir un URL.</span>
            <span class="textfieldRequiredMsg">Ce champ est obligatoire.</span>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="allow_temperance">
            <input class="checkbox" name="allow_temperance" type="checkbox" id="allow_temperance" value="1" {{ old('allow_temperance', $organisation->allow_temperance) ? 'checked' : '' }} maxlength="190">
            Calculer les données économiques <a href="#" rel="clickover" title="Calculer les données économiques" data-content="Cette case vous permet de définir que vous souhaitez que des statistiques économiques soient générés."><i class="icon-info-sign"></i></a></label>
        <div class="controls"></div>
    </div>

    <div id="sprytextarea1" class="control-group">
        <label class="control-label" for="text">Présentation</label>
        <div class="controls">
          <textarea name="text" id="text" class="wysiwyg" rows="15">{{old('text', $organisation->text)}}</textarea>
          <br>
          <span class="textareaMaxCharsMsg">6000 caractères maximum.</span>
          <span class="textareaMinCharsMsg">2 caractères minimum.</span>
        </div>
    </div>
