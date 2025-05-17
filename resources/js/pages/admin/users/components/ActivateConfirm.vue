<script setup>
const props = defineProps({
    userData: {
        type: null,
        require: true
    }
})

const isConfirmDialogVisible = ref(false)

const emit = defineEmits('activateUser')

const activateUser = uuid => {
    emit('activateUser', uuid)
    isConfirmDialogVisible.value = !isConfirmDialogVisible.value
}
</script>

<template>
    <IconBtn @click="isConfirmDialogVisible = !isConfirmDialogVisible">
        <VIcon icon="tabler-lock-open" />
        <!-- Modal confirm delete deal -->
        <VDialog v-model="isConfirmDialogVisible" persistent class="v-dialog-sm">
            <DialogCloseBtn @click="isConfirmDialogVisible = !isConfirmDialogVisible" />
            <VCard title="Ativar Usuário">
                <VCardText>Tem certeza que deseja ativar {{ props.userData.name }}?
                </VCardText>
                <VCardText class="d-flex justify-end gap-3 flex-wrap">
                    <VBtn color="success" @click="activateUser(props.userData.uuid)">Ativar</VBtn>
                    <VBtn @click="isConfirmDialogVisible = !isConfirmDialogVisible">Cancelar</VBtn>
                </VCardText>
            </VCard>
        </VDialog>
    </IconBtn>
</template>
