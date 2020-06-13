<div class="form-group row align-items-center" :class="{'has-danger': errors.has('title'), 'has-success': this.fields.title && this.fields.title.valid }">
    <label for="title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page.columns.title') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.title" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('title'), 'form-control-success': this.fields.title && this.fields.title.valid}" id="title" name="title" placeholder="{{ trans('admin.page.columns.title') }}">
        <div v-if="errors.has('title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('title') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url'), 'has-success': this.fields.url && this.fields.url.valid }">
    <label for="url" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page.columns.url') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.url" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('url'), 'form-control-success': this.fields.url && this.fields.url.valid}" id="url" name="url" placeholder="{{ trans('admin.page.columns.url') }}">
        <div v-if="errors.has('url')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('content'), 'has-success': this.fields.content && this.fields.content.valid }">
    <label for="content" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page.columns.content') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <wysiwyg v-model="form.content" v-validate="''" id="content" name="content" :config="mediaWysiwygConfig"></wysiwyg>
        </div>
        <div v-if="errors.has('content')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('content') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('seo_description'), 'has-success': this.fields.seo_description && this.fields.seo_description.valid }">
    <label for="seo_description" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page.columns.seo_description') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.seo_description" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('seo_description'), 'form-control-success': this.fields.seo_description && this.fields.seo_description.valid}" id="seo_description" name="seo_description" placeholder="{{ trans('admin.page.columns.seo_description') }}">
        <div v-if="errors.has('seo_description')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('seo_description') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('seo_keywords'), 'has-success': this.fields.seo_keywords && this.fields.seo_keywords.valid }">
    <label for="seo_keywords" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page.columns.seo_keywords') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.seo_keywords" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('seo_keywords'), 'form-control-success': this.fields.seo_keywords && this.fields.seo_keywords.valid}" id="seo_keywords" name="seo_keywords" placeholder="{{ trans('admin.page.columns.seo_keywords') }}">
        <div v-if="errors.has('seo_keywords')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('seo_keywords') }}</div>
    </div>
</div>


<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cover_image'), 'has-success': this.fields.cover_image && this.fields.cover_image.valid }">
    <label for="cover_image" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.page.columns.cover_image') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cover_image" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cover_image'), 'form-control-success': this.fields.cover_image && this.fields.cover_image.valid}" id="cover_image" name="cover_image" placeholder="{{ trans('admin.page.columns.cover_image') }}">
        <div v-if="errors.has('cover_image')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cover_image') }}</div>
    </div>
</div>


