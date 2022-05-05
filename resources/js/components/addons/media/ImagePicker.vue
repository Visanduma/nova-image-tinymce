<template>
  <modal class="select-text" @modal-close="handleClose">
    <loading-card :loading="initialLoading">
      <card class="w-choose-existing-media overflow-auto h-100">
        <h4 class="text-90 font-normal text-2xl flex-no-shrink px-8 pt-6">
          Select Media
        </h4>


        <div class="bg-30 px-8 py-4 mt-6 flex">
          <form @submit.prevent="searchImages">
            <div class="relative h-9 flex-no-shrink">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                   class="fill-current absolute search-icon-center ml-3 text-70">
                <path fill-rule="nonzero"
                      d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
              </svg>
              <input v-model="searchQuery" @keyup="searchWhenTyping" placeholder="Search" type="search"
                     class="appearance-none form-search w-search pl-search shadow">
            </div>
          </form>

          <div v-if="field.upload" class="ml-auto">

              <span class="form-file mr-4">
                <input @change="handleFileUpload" ref="file" dusk="photo" type="file" id="file-teams-photo" name="name" accept="image/*" class="form-file-input select-none">
                <label
                  for="file-teams-photo"
                  class="form-file-btn btn btn-default btn-primary select-none"><span>Upload</span></label>
              </span>
          </div>

        </div>

<!--        gallary -->
        <loading-view :loading="imagesIsLoading">
          <div class="p-4">
          <div class="flex flex-wrap nml-display-gallery">

            <image-block-mt @choose="selectImage(image)" v-for="(image,k) in images" :key="k" :image="image"/>
            <p class="p-5" v-empty="images">Nothing here !</p>

          </div>
        </div>
        </loading-view>

<!--        end gallary-->

        <div class="bg-30 px-6 py-3 flex">
          <button :disabled="page.url == null || page.active"
                  @click="getMedia(page.url)"
                  v-for="(page,k) in pagination"
                  :key="k" type="button"
                  :class=" page.active ? 'btn text-80 font-normal h-9 px-3 mr-3 btn-link pagination-active' : 'btn text-80 font-normal h-9 px-3 mr-3 btn-link ' "
                  v-html="page.label"></button>

          <div class="ml-auto">
            <button @click="refreshMedia()" type="button" dusk="cancel-general-button"
                    class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">Refresh
            </button>
            <button @click="handleClose" type="button" dusk="cancel-general-button"
                    class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">Cancel
            </button>
          </div>
        </div>
      </card>
    </loading-card>
  </modal>
</template>

<script>


export default {
 props:['field'],
  data() {
    return {
      images: [],
      selectedImages: [],
      initialLoading: true,
      imagesIsLoading:false,
      searchQuery: "",
      imageToUpload: null,
      pagination: []
    }
  },
  mounted() {
    this.getMedia()
  },
  methods: {
    handleClose() {
      this.$emit('close')
    },

    getMedia(url = "/nova-vendor/nova-media-tinymce/get-images") {
      this.imagesIsLoading = true
      Nova.request().get(url)
          .then(res => {
            this.images = res.data.data
            this.initialLoading = false
            this.imagesIsLoading = false

            this.pagination = res.data.links
          })
          .catch(e=>{
            this.$toasted.show("Unable to load images !",{type: 'error'})
          })
    },

    selectImage(img) {
      this.selectedImages.push(img)
      this.$emit('choose', this.selectedImages)
      this.$emit('close')
    },

    refreshMedia() {
      this.getMedia()
      this.searchQuery = ""
    },

    searchWhenTyping() {
      _.debounce(function (e) {
        this.searchImages()
      }, 2000)
    },

    searchImages() {
      this.imagesIsLoading = true

      Nova.request().get('/nova-vendor/nova-media-tinymce/search-images/' + this.searchQuery)
          .then(res => {
            this.images = res.data
            this.imagesIsLoading = false

            this.pagination = []
          })
          .catch(e=>{
            this.$toasted.show("Unable to search images !",{type: 'error'})
          })
    },

    handleFileUpload(){
      this.imagesIsLoading = true
        this.imageToUpload = this.$refs.file.files[0]

      let formData = new FormData()
      formData.append('image',this.imageToUpload)
      Nova.request().post('/nova-vendor/nova-media-tinymce/upload-image',formData,{
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(res=>{
        this.images.unshift(res.data)
        this.$toasted.show("Sucesfuly uploaded !",{type: 'success'})
        this.imagesIsLoading = false


      })
      .catch(e=>{
        this.$toasted.show("Unable to upload image !",{type: 'error'})
        this.imagesIsLoading = false

      })
    }

  }
}
</script>
