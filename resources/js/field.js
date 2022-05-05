Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-tinymce5-editor', require('./components/IndexField'))
  Vue.component('detail-nova-tinymce5-editor', require('./components/DetailField'))
  Vue.component('form-nova-tinymce5-editor', require('./components/FormField'))
  Vue.component('media-picker-nova-tinymce5-editor', require('./components/addons/media/ImagePicker'))
  Vue.component('image-block-mt', require('./components/addons/media/ImageBlock'))
  Vue.config.devtools = true
})
