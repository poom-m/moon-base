<template>
  <div class="main-wrapper">
    <Sidebar />
    <Nuxt />
  </div>
</template>

<script>
export default {
  name: 'Default',
}
</script>

<style lang="scss" scoped>
.main-wrapper {
  background-color: #eee;
  min-height: 100vh;
}
</style>
