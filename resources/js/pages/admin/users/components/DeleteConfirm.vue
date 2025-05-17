<script setup>
const props = defineProps({
    userData: {
        type: null,
        require: true
    }
})

const isConfirmDialogVisible = ref(false)

const emit = defineEmits('deleteUser')

const deleteUser = uuid => {
    emit('deleteUser', uuid)
    isConfirmDialogVisible.value = !isConfirmDialogVisible.value
}
</script>

<template>
    <IconBtn @click="isConfirmDialogVisible = !isConfirmDialogVisible">
        <VIcon icon="tabler-trash" />
        <!-- Modal confirm delete deal -->
        <VDialog v-model="isConfirmDialogVisible" persistent class="v-dialog-sm">
            <DialogCloseBtn @click="isConfirmDialogVisible = !isConfirmDialogVisible" />
            <VCard title="Apagar Usuário">
                <VCardText>Tem certeza que deseja apagar o registro de {{ props.userData.name }}?
                </VCardText>
                <VCardText class="d-flex justify-end gap-3 flex-wrap">
                    <VBtn color="error" @click="deleteUser(props.userData.uuid)">Apagar</VBtn>
                    <VBtn @click="isConfirmDialogVisible = !isConfirmDialogVisible">Cancelar</VBtn>
                </VCardText>
            </VCard>
        </VDialog>
    </IconBtn>
</template>
