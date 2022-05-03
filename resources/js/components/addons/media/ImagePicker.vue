<template>
    <modal class="select-text" @modal-close="handleClose">
        <card class="w-choose-existing-media overflow-hidden">
            <h4 class="text-90 font-normal text-2xl flex-no-shrink px-8 pt-6">
                Select Media
            </h4>
            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button @click="handleClose" type="button" dusk="cancel-general-button"  class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">Cancel</button>
                </div>
            </div>
        </card>
    </modal>
</template>

<script>
export default {
    methods:{
        handleClose(){
            this.$emit('close')
        }
    }
}
</script>

<style scoped>

</style>
