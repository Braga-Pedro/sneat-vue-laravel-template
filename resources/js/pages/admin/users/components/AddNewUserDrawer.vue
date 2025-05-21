<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'userData',
])


const isFormValid = ref(false)
const refForm = ref()

const userData = ref({
  name: '',
  email: '',
  password: '',
  address: '',
  phone: '',
  admin: false,
  operator: false,
})

// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
  })
}

const onSubmit = () => {
  emit('userData', { ...userData.value })
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}

</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content" :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate">
    <!-- 👉 Title -->
    <AppDrawerHeaderSection title="Adicionar Usuário" @cancel="closeNavigationDrawer" />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Username -->
              <VCol cols="12">
                <VTextField v-model="userData.name" label="Nome" placeholder="Johndoe" />
              </VCol>

              <!-- 👉 Email -->
              <VCol cols="12">
                <VTextField v-model="userData.email" label="E-mail" placeholder="johndoe@email.com" />
              </VCol>

              <!-- 👉 password -->
              <VCol cols="12">
                <VTextField v-model="userData.password" type="password" label="Senha" />
              </VCol>

              <div class="demo-space-x ml-3">
                <VCheckbox v-model="userData.admin" :label="'Administrador'" />

                <VCheckbox v-model="userData.operator" :label="'Operador'" />
              </div>

              <!-- 👉 Address -->
              <VCol cols="12" md="6">
                <VTextField v-model="userData.address" label="Endereço"
                  placeholder="número, rua, bairro, cidade, país" />
              </VCol>

              <!-- 👉 Phone -->
              <VCol cols="12" md="6">
                <VTextField v-model="userData.phone" label="Telefone" placeholder="+1-541-754-3010" />
              </VCol>

              <!-- 👉 Submit and Cancel -->
              <VCol cols="12" class="mt-2">
                <VBtn type="submit" class="me-3">
                  Salvar
                </VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">
                  Cancelar
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
