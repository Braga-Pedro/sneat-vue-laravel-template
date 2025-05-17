<script setup>
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue';
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue';
</script>

<template>
  <VerticalNavSectionTitle
    :item="{
      heading: 'Menu do Administrador',
    }"
  />
  <VerticalNavLink
    :item="{
      title: 'Usuários',
      icon: 'bx-user',
      to: '/admin/users/list',
    }"
  />
</template>
