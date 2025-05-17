<script setup>
const props = defineProps({
    userData: {
        type: null,
        required: true
    },
})

const isDialogVisible = ref(false)

const dataUser = ref({
    uuid: props.userData.uuid,
    user: {
        name: props.userData.name,
        email: props.userData.email,
        phone: props.userData.phone,
        address: props.userData.address,
    },
    userModule: {
        admin: props.userData.admin,
        agent: props.userData.agent,
    },
})

const emit = defineEmits('editUser')

const editUser = () => {
    emit('editUser', dataUser.value)
    isDialogVisible.value = !isDialogVisible.value
}

</script>

<template>
    <VDialog v-model="isDialogVisible" max-width="600">
        <!-- Dialog Activator -->
        <template #activator="{ props }">
            <IconBtn @click="isDialogVisible = !isDialogVisible">
                <VIcon icon="tabler-pencil" v-bind="props" />
            </IconBtn>
        </template>

        <!-- Dialog close btn -->
        <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

        <!-- Dialog Content -->
        <VCard title="Editar Usuário">
            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <AppTextField v-model="dataUser.user.name" label="Nome" placeholder="John" />
                    </VCol>

                    <VCol cols="12">
                        <AppTextField v-model="dataUser.user.email" label="E-mail" placeholder="johndoe@email.com" />
                    </VCol>

                    <VCol cols="12" sm="6">
                        <AppTextField v-model="dataUser.user.address" label="Enderenço"
                            placeholder="R. X, Bairro Y, Cidade A, País K" />
                    </VCol>

                    <VCol cols="12" sm="6">
                        <AppTextField v-model="dataUser.user.phone" label="Telefone" placeholder="10120164864" />
                    </VCol>

                    <VCol cols="12" class="demo-space-x justify-center">
                        <VCheckbox v-model="dataUser.userModule.admin" :label="'Administrador'" />

                        <VCheckbox v-model="dataUser.userModule.agent" :label="'Operador'" />
                    </VCol>

                </VRow>
            </VCardText>

            <VCardText class="d-flex justify-end flex-wrap gap-3">
                <VBtn variant="tonal" color="secondary" @click="isDialogVisible = !isDialogVisible">
                    Cancelar
                </VBtn>
                <VBtn @click="editUser">
                    Salvar
                </VBtn>
            </VCardText>
        </VCard>
    </VDialog>
</template>
