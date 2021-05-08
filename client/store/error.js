export const state = () => ({
  message: null,
})

export const mutations = {
  update(state, message) {
    state.message = message
  },
}
