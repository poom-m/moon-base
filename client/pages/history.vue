<template>
  <b-container class="py-5">
    <BlockSection class="balance-block">
      <div class="balance-section">MOON left {{ moonBalance }} MOON</div>
    </BlockSection>

    <BlockSection>
      <b-table-lite hover :items="orders" class="mb-0" />
    </BlockSection>
  </b-container>
</template>

<script>
export default {
  data() {
    return {
      moonBalance: 1000,
      orders: [
        {
          date_time: '2021-04-01 10:00:00',
          ID: 'AAA',
          THBT: 100,
          MOON: 2,
          RATE: '1 MOON = 50 THBT | 0.02',
        },
        {
          date_time: '2021-04-01 10:00:00',
          ID: 'BBB',
          THBT: 100,
          MOON: 2,
          RATE: '1 MOON = 50 THBT | 0.02',
        },
        {
          date_time: '2021-04-01 10:00:00',
          ID: 'BBB',
          THBT: 100,
          MOON: 2,
          RATE: '1 MOON = 50 THBT | 0.02',
        },
      ],
    }
  },
}
</script>

<style lang="scss" scoped>
.balance-block {
  .balance-section {
    font-size: 24px;
    font-weight: 700;
  }
}
</style>
