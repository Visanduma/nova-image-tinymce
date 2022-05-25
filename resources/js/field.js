Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-image-tinymce-editor', require('./components/IndexField'))
  Vue.component('detail-nova-image-tinymce-editor', require('./components/DetailField'))
  Vue.component('form-nova-image-tinymce-editor', require('./components/FormField'))
  Vue.component('media-picker-nova-tinymce5-editor', require('./components/addons/media/ImagePicker'))
  Vue.component('image-block-mt', require('./components/addons/media/ImageBlock'))
  Vue.config.devtools = true
})
