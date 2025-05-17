<script setup>
import { homePages } from '@/utils/homePages';
import misc404 from '@images/pages/404.png';

const selectedModule = localStorage.getItem('selectedModule')
const homePageToRedirect = ref('')

if (selectedModule === 'admin') {
  homePageToRedirect.value = homePages.admin
} else if (selectedModule === 'operator') {
  homePageToRedirect.value = homePages.operator
}
</script>

<template>
  <div class="misc-wrapper">
    <ErrorHeader
      status-code="404"
      title="Página não encontrada ⚠️"
      description="Não conseguimos achar a página que você está buscando."
    />

    <VBtn
      :to="homePageToRedirect"
      class="mb-6"
    >
      Voltar para o início
    </VBtn>

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg
        :src="misc404"
        alt="Página não encontrada"
        :max-width="500"
        class="mx-auto"
      />
    </div>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
