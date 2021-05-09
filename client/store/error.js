export const state = () => ({
  detail: null,
})

export const mutations = {
  update(state, error) {
    state.detail = error
  },
}
