<script setup>
import { emailValidator, requiredValidator } from '@/@core/utils/validators'
import { $api } from '@/utils/api'
import logo from '@images/logo.svg?raw'
import authV1BottomShape from '@images/svg/auth-v1-bottom-shape.svg?url'
import authV1TopShape from '@images/svg/auth-v1-top-shape.svg?url'

const errors = ref('')
const route = useRoute()
const router = useRouter()
const formRefValidation = ref()
const credentials = ref({
  email: '',
  password: '',
  remember: false,
})

const isPasswordVisible = ref(false)

const login = async () => {
  try {
    const response = await $api('/auth/login', {
      method: 'POST',
      body: {
        email: credentials.value.email,
        password: credentials.value.password,
      },
      onResponseError({ response }) {
        if (response.status === 401) {
          errors.value = "Credenciais incorretas. Por favor, tente novamente.";
        } else if (response.status === 500) {
          errors.value = "Error interno do servidor. Tente novamente mais tarde.";
        } else {
          errors.value = "Ocorreu um error desconhecido.";
        }
        console.error(errors.value)
      },
    })

    localStorage.setItem('accessToken', response.accessToken)
    localStorage.setItem('dataUser', JSON.stringify(response.dataUser))
    localStorage.setItem('dataModule', JSON.stringify(response.dataModule))
    localStorage.setItem('selectedModule', response.selectedModule)

    await nextTick(() => {
      if (response?.selectedModule) {
        router.replace(route.query.to ? String(route.query.to) : '/')
      } else if (response?.dataUser) {
        router.push('/access-control')
      }
      else {
        router.push('/login')
      }
    })
  } catch (err) {
    console.error(err)
  }
}

const onSubmit = () => {
  formRefValidation.value?.validate().then(({ valid: isValid }) => {
    if (isValid)
      login()
  })
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <div class="position-relative my-sm-16">
      <!-- 👉 Top shape -->
      <VImg
        :src="authV1TopShape"
        class="text-primary auth-v1-top-shape d-none d-sm-block"
      />

      <!-- 👉 Bottom shape -->
      <VImg
        :src="authV1BottomShape"
        class="text-primary auth-v1-bottom-shape d-none d-sm-block"
      />

      <!-- 👉 Auth Card -->
      <VCard
        class="auth-card"
        max-width="460"
        :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'"
      >
        <VCardItem class="justify-center">
          <RouterLink
            to="/"
            class="app-logo"
          >
            <!-- eslint-disable vue/no-v-html -->
            <div
              class="d-flex"
              v-html="logo"
            />
            <h1 class="app-logo-title">
              sneat
            </h1>
          </RouterLink>
        </VCardItem>

        <VCardText>
          <h4 class="text-h4 mb-1">
            Bem vindo ao Sneat! 👋🏻
          </h4>
          <p class="mb-0">
            Por favor, faça login para continuar
          </p>
        </VCardText>

        <VCardText>
          <VForm ref="formRefValidation" @submit.prevent="onSubmit">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="credentials.email"
                  autofocus
                  label="Email"
                  type="email"
                  placeholder="johndoe@email.com"
                  :rules="[emailValidator]"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <VTextField
                  v-model="credentials.password"
                  label="Senha"
                  placeholder="············"
                  :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  autocomplete="password"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <!-- remember me checkbox -->
                <div class="d-flex align-center justify-space-between flex-wrap my-6">
                  <VCheckbox
                    v-model="credentials.remember"
                    label="Lembrar-me"
                  />

                  <!-- <a
                    class="text-primary"
                    href="javascript:void(0)"
                  >
                    Forgot Password?
                  </a> -->
                </div>

                <!-- login button -->
                <VBtn block type="submit">
                  Entrar
                </VBtn>
              </VCol>

              <!-- create account -->
              <!-- <VCol
                cols="12"
                class="text-body-1 text-center"
              >
                <span class="d-inline-block">
                  New on our platform?
                </span>
                <RouterLink
                  class="text-primary ms-1 d-inline-block text-body-1"
                  to="/register"
                >
                  Create an account
                </RouterLink>
              </VCol>

              <VCol
                cols="12"
                class="d-flex align-center"
              >
                <VDivider />
                <span class="mx-4 text-high-emphasis">or</span>
                <VDivider />
              </VCol> -->

              <!-- auth providers -->
              <!-- <VCol
                cols="12"
                class="text-center"
              >
                <AuthProvider />
              </VCol> -->
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </div>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";
</style>
