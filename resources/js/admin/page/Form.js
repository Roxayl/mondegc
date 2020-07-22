import AppForm from '../app-components/Form/AppForm';

Vue.component('page-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                title:  '' ,
                url:  '' ,
                content:  '' ,
                seo_description:  '' ,
                seo_keywords:  '' ,
                published_at:  '' ,
                cover_image:  '' ,
                
            }
        }
    }

});