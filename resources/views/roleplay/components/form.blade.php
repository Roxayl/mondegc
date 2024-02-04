
<div class="control-group">
    <label for="roleplay_name_field" class="control-label">
        Nom du roleplay<span class="label-required">*</span>
    </label>
    <input type="text" id="roleplay_name_field" class="form-control input-xxlarge"
           style="padding: 10px 2px;" required
           name="name" value="{{ old('name', $roleplay->name) }}"/>
</div>

<div class="control-group">
    <label for="roleplay_banner_field" class="control-label">URL de la bannière</label>
    <input type="text" id="roleplay_banner_field" class="form-control input-xxlarge"
           style="padding: 10px 2px;"
           name="banner" value="{{ old('banner', $roleplay->banner) }}"/>
</div>

<div class="control-group">
    <label for="roleplay_description_field" class="control-label">
        Description<span class="label-required">*</span>
    </label>
    <textarea name="description" id="roleplay_description_field"
              style="width: 85%;" rows="6" required
        >{{ old('description', $roleplay->description) }}</textarea>
</div>
