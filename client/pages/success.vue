<template>
  <b-container class="py-5">
    <b-row class="justify-content-center">
      <b-col cols="6">
        <BlockSection class="success-block">
          <h1 class="mb-3 font-weight-bold">Success</h1>
          <p>
            You bought
            <span data-atd="success-moon-label">{{ order.amount_crypto }}</span>
            {{ order.crypto.name }}
          </p>
          <p class="mb-4">
            with
            <span data-atd="success-thbt-label">{{ order.amount_thbt }}</span>
            THBT
          </p>
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
  name: 'Success',
  layout: 'blank',
  computed: {
    order() {
      return this.$store.state.order.detail
    },
  },
}
</script>

<style lang="scss" scoped>
.success-block {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
