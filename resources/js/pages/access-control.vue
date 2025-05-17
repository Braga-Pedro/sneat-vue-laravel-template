<script setup>
import { $api } from '@/utils/api';
import moduleAdmin from '@images/access-control/admin.png';
import moduleOperator from '@images/access-control/operator.png';

const modules = []

const dataModule = JSON.parse(localStorage.getItem('dataModule'))
if (dataModule[0]?.admin) {
  modules.push({ 'logo': moduleAdmin, 'module': 'admin', 'name': 'ADMINISTRATIVO' })
}
if (dataModule[0]?.operator) {
  modules.push({ 'logo': moduleOperator, 'module': 'operator', 'name': 'OPERADOR' })
}

const selectModule = async (module) => {
  try {
    await $api('/auth/select-module', {
      method: 'POST',
      body: {
        module: module,
      },

      onResponseError({ response_ }) {
        console.error(response_)
      },
    })

    localStorage.setItem('selectedModule', module)

    if (module === 'admin') {
      document.location.href = "/dashboard"
    }
    if (module === 'operator') {
      document.location.href = "/account-settings"
    }
  } catch (error) {
    console.error(error)
  }
}

</script>

<template>
  <p class="text-2xl mb-6 text-center mt-12">
    Selecione o Módulo para continuar
  </p>
  <VRow>
    <VCol v-for="module in modules" cols="12" sm="6" md="4" v-bind="props">
      <!-- 👉  Card -->
      <VCard flat border class="ml-4 mr-4">
        <!-- 👉 Plan logo -->
        <VCardText class="text-center">
          <VImg :src="module.logo" cover class="mx-auto mb-5" style="width: 300px; height: 300px;" />

          <!-- 👉 Plan name -->
          <h5 class="text-h5 mb-2">
            {{ module.name }}
          </h5>
        </VCardText>

        <!-- 👉 Plan actions -->
        <VCardActions>
          <VBtn block color="white" style="background-color: #124ea5;" @click="selectModule(module.module)">
            SELECIONAR
          </VBtn>
        </VCardActions>
      </VCard>
    </VCol>
  </VRow>
</template>
