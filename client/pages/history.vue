<template>
  <b-container class="py-5">
    <BlockSection class="balance-block">
      <b-skeleton v-if="$fetchState.pending" class="balance-loading" />

      <h2 v-else-if="$fetchState.error" class="error-text">
        Error loading crypto
      </h2>

      <div v-else class="balance-section">
        {{ crypto.name }} left {{ crypto.balance }} {{ crypto.name }}
      </div>
    </BlockSection>

    <BlockSection v-if="!$fetchState.error">
      <b-skeleton-table v-if="$fetchState.pending" :rows="5" :columns="5" />

      <b-table-lite
        v-else-if="orders.length"
        hover
        :items="orders"
        class="mb-0"
      />

      <h2 v-else class="error-text">No history yet</h2>
    </BlockSection>
  </b-container>
</template>

<script>
export default {
  data() {
    return {
      crypto: null,
      orders: [],
    }
  },

  async fetch() {
    this.crypto = (await this.$axios.$get('/api/cryptos/1'))?.data

    const orderResponse = (await this.$axios.$get('/api/orders'))?.data

    this.orders = orderResponse.map((order) => {
      return {
        date_time: order.date_time,
        ID: order.user_id,
        THBT: order.amount_thbt,
        MOON: order.amount_crypto,
        RATE: `${order.amount_crypto} ${order.crypto} = ${
          order.amount_thbt
        } THBT | ${order.amount_crypto / order.amount_thbt}`,
      }
    })
  },
}
</script>

<style lang="scss" scoped>
.balance-block {
  .balance-loading {
    width: 260px;
    height: 24px;
  }
  .balance-section {
    font-size: 24px;
    font-weight: 700;
  }
}

.error-text {
  margin-bottom: 0;
  font-size: 20px;
}
</style>
