<template>
  <b-container class="py-5">
    <b-row class="justify-content-center">
      <b-col cols="6">
        <BlockSection class="price-block">
          <template v-if="$fetchState.pending">
            <b-skeleton class="price-loading" />
            <b-skeleton class="balance-loading" />
          </template>

          <h2 v-else-if="$fetchState.error" class="error-text">
            Error loading crypto
          </h2>

          <template v-else>
            <h1 class="price-text">
              {{ crypto.name }} =
              <span data-atd="moon-price-label">{{ crypto.price }}</span> THBT
            </h1>

            <p class="balance-text">
              You have
              <span data-atd="balance-label">{{ user.balance_thbt }}</span> THBT
            </p>
          </template>
        </BlockSection>

        <BlockSection v-if="!$fetchState.error" class="form-block">
          <template v-if="$fetchState.pending">
            <b-skeleton class="label-loading" />
            <b-skeleton class="input-loading" />
            <b-skeleton class="label-loading" />
            <b-skeleton class="input-loading" />
            <b-skeleton class="label-loading" />
            <b-skeleton class="input-loading" />
            <b-skeleton class="label-loading" />
            <b-skeleton class="input-loading" />
          </template>

          <template v-else>
            <form>
              <b-form-group
                label="Amount to buy (THBT)"
                label-for="amount_thbt"
              >
                <b-form-input
                  id="amount_thbt"
                  v-model="amount_thbt"
                  type="number"
                  min="0"
                  :max="user.balance_thbt"
                  :disabled="amountThbtDisabled"
                  data-atd="thbt-input"
                  @focus="focus = 'thbt'"
                  @blur="focus = null"
                />
              </b-form-group>

              <b-form-group
                :label="`Amount ${crypto.name}`"
                label-for="amount_crypto"
              >
                <b-form-input
                  id="amount_crypto"
                  v-model="amount_crypto"
                  type="number"
                  min="0"
                  :max="crypto.balance"
                  :disabled="amountCryptoDisabled"
                  data-atd="moon-input"
                  @focus="focus = 'crypto'"
                  @blur="focus = null"
                />
              </b-form-group>

              <b-form-group
                label="Slippage Tolerance (%)"
                label-for="slippageInput"
              >
                <b-form-input
                  id="slippageInput"
                  v-model="slippage"
                  type="number"
                  min="0"
                  max="100"
                  data-atd="slippage-input"
                />
              </b-form-group>

              <b-button
                class="buy-btn"
                pill
                variant="primary"
                data-atd="buy-btn"
                :disabled="submitDisabled"
                @click="buy"
              >
                <b-spinner v-if="loading" small />
                <span>Buy</span>
              </b-button>
            </form>
          </template>
        </BlockSection>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import { v4 as uuidv4 } from 'uuid'

export default {
  name: 'Index',

  data() {
    return {
      crypto: null,
      amount_thbt: 0,
      amount_crypto: 0,
      slippage: 0,
      focus: null,
      loading: false,
      amountThbtDisabled: false,
      amountCryptoDisabled: false,
    }
  },

  async fetch() {
    this.crypto = (await this.$axios.$get('/api/cryptos/1'))?.data
  },

  computed: {
    user() {
      return this.$store.state.user.detail
    },

    submitDisabled() {
      return this.loading || this.amount_thbt === 0 || this.amount_crypto === 0
    },
  },

  watch: {
    async amount_thbt(value) {
      // Only execute when user focus on THBT field
      if (this.focus !== 'thbt') return

      // Not allow < 0 or > balance
      if (value < 0) {
        this.$nextTick(() => {
          this.amount_thbt = 0
        })
      } else if (value > this.user.balance_thbt) {
        this.$nextTick(() => {
          this.amount_thbt = this.user.balance_thbt
        })
      }

      // Convert THBT to crypto
      try {
        this.amountCryptoDisabled = true
        const response = await this.$axios({
          method: 'get',
          url: '/api/thbt-to-crypto',
          params: {
            crypto_id: 1,
            amount_thbt: value,
          },
          progress: false,
        })

        // Update crypto field
        this.amount_crypto = response.data.amount_crypto
        this.amountCryptoDisabled = false
      } catch (error) {
        if (error.response.data.message)
          this.$store.commit('error/update', error.response.data)
        this.$router.push('/error')
      }
    },

    async amount_crypto(value) {
      // Only execute when user focus on crypto field
      if (this.focus !== 'crypto') return

      // Not allow < 0 or > balance
      if (value < 0) {
        this.$nextTick(() => {
          this.amount_crypto = 0
        })
      } else if (value > this.crypto.balance) {
        this.$nextTick(() => {
          this.amount_crypto = this.crypto.balance
        })
      }

      // Convert crypto to THBT
      try {
        this.amountThbtDisabled = true
        const response = await this.$axios({
          method: 'get',
          url: '/api/crypto-to-thbt',
          params: {
            crypto_id: 1,
            amount_crypto: value,
          },
          progress: false,
        })

        // Update THBT field
        this.amount_thbt = response.data.amount_thbt
        this.amountThbtDisabled = false
      } catch (error) {
        if (error.response.data.message)
          this.$store.commit('error/update', error.response.data)
        this.$router.push('/error')
      }
    },
  },

  mounted() {
    if (!this.user.id) this.$store.commit('user/updateId', uuidv4())
  },

  methods: {
    async buy() {
      this.loading = true

      try {
        // Buy request
        const response = await this.$axios.post('/api/orders', {
          crypto_id: 1,
          user_id: this.user.id,
          amount_thbt: this.amount_thbt,
          amount_crypto: this.amount_crypto,
          slippage: this.slippage / 100,
          balance_thbt: this.user.balance_thbt,
        })

        // Update real amount used to purchase
        this.amount_thbt = response.data.amount_thbt
        this.amount_crypto = response.data.amount_crypto

        this.$store.commit(
          'user/updateBalance',
          this.user.balance_thbt - this.amount_thbt
        )

        this.$store.commit('order/update', {
          amount_crypto: this.amount_crypto,
          amount_thbt: this.amount_thbt,
          crypto: this.crypto,
        })

        this.$router.push('/success')
      } catch (error) {
        if (error.response.data.message)
          this.$store.commit('error/update', error.response.data)
        this.$router.push('/error')
      }

      this.loading = false
    },
  },
}
</script>

<style lang="scss" scoped>
.price-block {
  display: flex;
  flex-direction: column;
  align-items: center;

  .price-loading {
    width: 75%;
    height: 40px;
  }

  .balance-loading {
    width: 50%;
    height: 28px;
  }

  .error-text {
    margin-bottom: 0;
    font-size: 20px;
  }

  .price-text,
  .balance-text {
    font-weight: 700;
    margin-bottom: 0;
    color: $primary;
    text-align: center;
  }

  .price-text {
    font-size: 40px;
  }

  .balance-text {
    font-size: 28px;
  }
}

.form-block {
  .label-loading {
    width: 35%;
    height: 20px;
    margin-bottom: 7px;
  }

  .input-loading {
    height: 30px;

    &:not(:last-child) {
      margin-bottom: 15px;
    }
  }
}
</style>
