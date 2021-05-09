<template>
  <b-container class="py-5">
    <b-row class="justify-content-center">
      <b-col cols="6">
        <BlockSection class="error-block">
          <h1 class="mb-3 font-weight-bold">Error</h1>

          <p class="error-message">{{ error.message }}</p>

          <div
            v-for="(messages, key) in error.errors"
            :key="key"
            class="validation-error-block"
          >
            <p
              v-for="(message, index) in messages"
              :key="index"
              class="validation-error"
            >
              {{ message }}
            </p>
          </div>

          <b-button
            class="mt-4"
            to="/"
            pill
            variant="primary"
            data-atd="back-btn"
          >
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
    error() {
      return this.$store.state.error.detail
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

  .validation-error-block {
    .validation-error {
      margin-bottom: 0;
    }
  }
}
</style>
