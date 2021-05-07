<template>
  <div class="block-section">
    <slot></slot>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.block-section {
  border-radius: 20px;
  padding: 30px;
  background-color: #fff;

  &:not(:last-child) {
    margin-bottom: 30px;
  }
}
</style>
