export const state = () => ({
  detail: {
    amount_crypto: 0,
    amount_thbt: 0,
    crypto: {
      name: null,
    },
  },
})

export const mutations = {
  update(state, order) {
    state.detail = order
  },
}
