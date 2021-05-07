<template>
  <b-container class="py-5">
    <b-row class="justify-content-center">
      <b-col cols="6">
        <BlockSection class="price-block">
          <h1 class="price-section">
            MOON = <span data-atd="moon-price-label">50</span> THBT
          </h1>
          <h3 class="balance-section">
            You have <span data-atd="balance-label">100</span> THBT
          </h3>
        </BlockSection>

        <BlockSection>
          <form>
            <b-form-group label="Amount to buy (THBT)" label-for="thbtInput">
              <b-form-input
                id="thbtInput"
                v-model="thbtInput"
                data-atd="thbt-input"
              />
            </b-form-group>

            <b-form-group label="Amount MOON" label-for="moonInput">
              <b-form-input
                id="moonInput"
                v-model="moonInput"
                data-atd="moon-input"
              />
            </b-form-group>

            <b-form-group
              label="Slippage Tolerance (%)"
              label-for="slippageInput"
            >
              <b-form-input
                id="slippageInput"
                v-model="slippageInput"
                data-atd="slippage-input"
              />
            </b-form-group>

            <b-button
              type="submit"
              size="lg"
              pill
              variant="primary"
              data-atd="buy-btn"
            >
              Buy
            </b-button>
          </form>
        </BlockSection>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  data() {
    return {
      thbtInput: 0,
      moonInput: 0,
      slippageInput: 0,
    }
  },
}
</script>

<style lang="scss" scoped>
.price-block {
  text-align: center;
  color: $primary;

  .price-section,
  .balance-section {
    font-weight: 700;
  }
}
</style>
