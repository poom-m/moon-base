<template>
  <b-container class="py-5">
    <b-row class="justify-content-center">
      <b-col cols="6">
        <BlockSection class="error-block">
          <h1 class="mb-3 font-weight-bold">Error</h1>
          <p class="mb-4">{{ message }}</p>
          <b-button to="/" pill variant="primary" data-atd="back-btn">
            Back to buy
          </b-button>
        </BlockSection>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  name: 'Error',
  layout: 'blank',
  computed: {
    message() {
      return this.$store.state.error.message
    },
  },
}
</script>

<style lang="scss" scoped>
.error-block {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
