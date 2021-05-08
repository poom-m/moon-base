export const state = () => ({
  detail: {
    id: null,
    balance_thbt: 100,
  },
})

export const mutations = {
  update(state, user) {
    state.detail = user
  },

  updateId(state, id) {
    state.detail.id = id
  },

  updateBalance(state, balance) {
    state.detail.balance_thbt = balance
  },
}
