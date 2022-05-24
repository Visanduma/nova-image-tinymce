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

          <div  class="ml-auto flex">

            <button v-show="selectedImages.length" @click="deleteSelected()" type="button" class="btn-icon text-70 hover:text-primary mr-3 has-tooltip">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
              <span>Delete ({{ selectedImages.length }})</span>
            </button>

            <span v-if="field.upload" class="form-file mr-4">
                <input @change="handleFileUpload" ref="file" dusk="photo" multiple type="file" id="file-teams-photo" name="name" accept="image/*" class="form-file-input select-none">
                <label
                    for="file-teams-photo"
                    class="form-file-btn btn btn-default btn-primary select-none"><span>Upload</span></label>
              </span>

          </div>

        </div>

<!--        gallery -->
        <loading-view :loading="imagesIsLoading">
          <div class="p-4">
          <div class="gallery">

            <image-block-mt @choose="selectImage(image)" v-for="(image,k) in images" :key="k" :image="image"/>

            <p class="p-5 text-center" v-if="!images.length">Nothing here !</p>

          </div>
        </div>
        </loading-view>


<!--        end gallery-->

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
            <button v-show="selectedImages.length" @click="insertSelected()" type="button" class="btn btn-default btn-primary">Insert ({{ selectedImages.length }})</button>

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
      imagesToUpload: null,
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
      let has = _.indexOf(this.selectedImages, img)
      
      if(has > -1){
        this.selectedImages.splice(has,1)
      }else{
        this.selectedImages.push(img)
      }
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
      this.imagesToUpload = this.$refs.file.files

      let formData = new FormData()

      for (let i = 0; i < this.imagesToUpload.length; i++) {
        formData.append(`images[]`, this.imagesToUpload[i]);
      }


      Nova.request().post('/nova-vendor/nova-media-tinymce/upload-image',formData,{
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(res=>{
        res.data.forEach(itm => {
          this.images.unshift(itm)
        })
        this.$toasted.show("Successfully uploaded !",{type: 'success'})
        this.imagesIsLoading = false


      })
      .catch(e=>{
        this.$toasted.show("Unable to upload image !",{type: 'error'})
        this.imagesIsLoading = false

      })
    },

    deleteSelected(){

      if(!window.confirm("Are you sure ?")){
        return
      }

      this.imagesIsLoading = true

      Nova.request().post('/nova-vendor/nova-media-tinymce/delete-images/',{
        images: this.selectedImages.map((itm) => itm.id)
      })
          .then(res => {
            this.imagesIsLoading = false
            this.selectedImages = []
            this.refreshMedia()
            this.$toasted.show("Successfully deleted !",{type: 'success'})

          })
          .catch(e=>{
            this.$toasted.show("Unable to delete images !",{type: 'error'})
          })
    },

    insertSelected(){
      this.$emit('choose', this.selectedImages)
      this.$emit('close')
    }

  }
}
</script>
