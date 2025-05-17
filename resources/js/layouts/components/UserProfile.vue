<script setup>
import { clearSessionAndRedirect } from '@/utils/logout';
import avatar1 from '@images/avatars/avatar-1.png';

const dataUser = JSON.parse(localStorage.getItem('dataUser'))
const selectedModule = localStorage.getItem('selectedModule')
const moduleName = ref('');

if (selectedModule === 'admin') {
  moduleName.value = 'Administrador'
} else if (selectedModule === 'operator') {
  moduleName.value = 'Operador'
}

</script>

<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    color="success"
    bordered
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ dataUser?.name }}
            </VListItemTitle>
            <VListItemSubtitle>{{ moduleName }}</VListItemSubtitle>
          </VListItem>
          <VDivider class="my-2" />

          <!-- 👉 My Profile -->
          <VListItem 
            link
            to="/account-settings"
          >
            <template #prepend>
              <VIcon
                class="me-2"
                icon="bx-user"
                size="22"
              />
            </template>

            <VListItemTitle>Meu Perfil</VListItemTitle>
          </VListItem>

          <!-- 👉 Module Switch -->
          <VListItem 
            link
            to="/access-control"
          >
            <template #prepend>
              <VIcon
                class="me-2"
                icon="bx-cog"
                size="22"
              />
            </template>

            <VListItemTitle>Trocar Módulo</VListItemTitle>
          </VListItem>
          

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem 
            @click="clearSessionAndRedirect()"
           >
            <template #prepend>
              <VIcon
                class="me-2"
                icon="bx-log-out"
                size="22"
              />
            </template>

            <VListItemTitle>Sair</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
