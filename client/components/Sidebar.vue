<template>
  <div class="sidebar">
    <div class="nav-block">
      <NuxtLink to="/" class="nav-button">
        <b-icon-cash-stack />
        <span class="nav-text">Buy</span>
      </NuxtLink>
      <NuxtLink to="/history" class="nav-button">
        <b-icon-book />
        <span class="nav-text">History</span>
      </NuxtLink>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.sidebar {
  position: fixed;
  width: 50px;
  height: 100vh;
  background: #fff;
  transition: 0.25s ease;
  z-index: 1;

  &:hover {
    width: 250px;

    .nav-block .nav-button .nav-text {
      opacity: 1;
    }
  }

  .nav-block {
    display: flex;
    flex-direction: column;

    .nav-button {
      font-size: 18px;
      display: block;
      font-weight: bold;
      height: 48px;
      color: $primary;
      padding: 0 16px;
      display: flex;
      align-items: center;
      position: relative;

      &:hover {
        background-color: #eff4f5;
      }

      &.nuxt-link-exact-active::before {
        content: '';
        width: 5px;
        height: 100%;
        display: block;
        position: absolute;
        left: 0;
        background-color: #7cc659;
      }

      .nav-text {
        opacity: 0;
        margin-left: 10px;
        font-size: 16px;
        transition: 0.25s ease;
      }
    }
  }
}
</style>
