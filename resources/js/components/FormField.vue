<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <editor
                v-model="value"
                :api-key="apiKey"
                cloud-channel="5"
                :init="editorConfig"
                :plugins="editorPlugins"
                :toolbar="editorToolbar"
                :class="errorClasses"
                :placeholder="field.placeholder"
                :id="field.id"
                :name="field.name"
                />
        </template>
      <media-picker-nova-tinymce5-editor :field="field" @choose="handleImageChoose" v-if="showMediaPicker" @close="handleMediaPicker(false)"/>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import Editor from '@tinymce/tinymce-vue'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        editor: Editor
    },

    data() {
            return {
                    editorConfigInit: this.field.options.init,
                    editorPlugins: this.field.options.plugins,
                    editorToolbar: this.field.options.toolbar,
                    apiKey: this.field.options.apiKey,
                    showMediaPicker:false
                }
        },

    computed: {

        editorConfig: function() {

            let editorConfig = this.editorConfigInit
            let self = this

            if(this.field.imageGallary == true){


              editorConfig['setup'] =  function(editor){

                self.$on('i-have-images',function(images){
                  editor.insertContent('<img src="'+ images[0].image_url +'">');
                })

                editor.ui.registry.addButton('image-gallery', {
                  text: 'Image Gallary',
                  onAction: function (_) {
                    self.handleMediaPicker(true)
                  }
                });
              }

            }



            return editorConfig
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        handleMediaPicker(val){
          this.showMediaPicker = val
        },

      handleImageChoose(imgs){
          console.log(imgs)
          this.$emit('i-have-images',imgs)
      }
    },
}
</script>
