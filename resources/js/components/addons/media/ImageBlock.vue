<template>
  <div @click="$emit('choose',image)" :title="image.name" class="nml-item relative mb-2 cursor-pointer">
    <div class="icon rounded-lg shadow-md nml-icon-image"
         :style=" 'background-image: url(&quot;' + image.preview_url + '&quot;)' ">
      <div class="img-text">
        {{ image.name }} <br> ({{ image.size }})
      </div>
    </div>
    <div class="title truncate">{{ image.name }}</div>
  </div>
</template>

<script>
export default {
  props: ['image']
}
</script>

<style scoped>

</style>