<script setup>
import ActivateConfirm from '@/pages/admin/users/components/ActivateConfirm.vue';
import AddNewUserDrawer from '@/pages/admin/users/components/AddNewUserDrawer.vue';
import DeleteConfirm from '@/pages/admin/users/components/DeleteConfirm.vue';
import EditAdminDialog from '@/pages/admin/users/components/EditAdminDialog.vue';
import { $api } from '@/utils/api';

// 👉 Store
const searchQuery = ref('')

// Data table options
const sortBy = ref()
const orderBy = ref()

const users = ref([])
const page = ref(1);
const itemsPerPage = ref(15);
const totalUsers = ref(0)
const dataUsers = ref([])

const updateOptions = (options) => {
    sortBy.value = options.sortBy[0]?.key
    orderBy.value = options.sortBy[0]?.order
    page.value = options.page
    fetchUser()
}

// endpoint get users data
async function fetchUser() {
  try {
    const response = await $api('/admin/users/admin', {
      method: 'GET',
    //   params: {
    //     page: page.value,
    //     per_page: itemsPerPage.value,
    //     sort_by: sortBy.value,
    //     order_by: orderBy.value,
    //   },
    })

    dataUsers.value = response || []
    console.log('dataUsers', dataUsers.value)
  } catch (error) {
    console.error('Erro ao buscar usuários:', error)
  }
}

const searchUsers = async () => {
    const searchData = await $api('/admin/users/admin/find-by-argument', {
        method: 'POST',
        body: { argument: searchQuery.value },
    })

    dataUsers.value = searchData
}

watchEffect(() => {
    users.value = dataUsers.value.data || []
    itemsPerPage.value = dataUsers.value.per_page || 15
    page.value = dataUsers.value.current_page || 1
    totalUsers.value = dataUsers.value.total || 0
})

const isAddNewUserDrawerVisible = ref(false)

const addNewUser = async adminData => {
    await $api('/admin/users/admin', {
        method: 'POST',
        body: { user: adminData },
    })

    fetchUser()
}

const editUser = async userData => {
    await $api(`/admin/users/admin/${userData.uuid}`, {
        method: 'PUT',
        body: { user: userData.user, userModule: userData.userModule },
    })

    fetchUser()
}

const deleteUser = async uuid => {
    await $api(`/admin/users/admin/${uuid}`, { method: 'DELETE' })

    fetchUser()
}

const activateUser = async uuid => {
    await $api(`/admin/users/admin/activate/${uuid}`, { method: 'PUT' })

    fetchUser()
}

// Headers
const headers = [
    {
        title: 'Usuário',
        key: 'user',
        sortable: false,
    },
    {
        title: 'Telefone',
        key: 'phone',
        sortable: false,
    },
    {
        title: 'Módulos',
        key: 'user_module',
        sortable: false,
    },
    {
        title: 'Status',
        key: 'status',
        sortable: false,
    },
    {
        title: 'Actions',
        key: 'actions',
        sortable: false,
    },
]

// 👉 search filters
const roles = [
    {
        title: 'Admin',
        value: 'admin',
    },
    {
        title: 'Author',
        value: 'author',
    },
    {
        title: 'Editor',
        value: 'editor',
    },
    {
        title: 'Maintainer',
        value: 'maintainer',
    },
    {
        title: 'Subscriber',
        value: 'subscriber',
    },
]

const plans = [
    {
        title: 'Basic',
        value: 'basic',
    },
    {
        title: 'Company',
        value: 'company',
    },
    {
        title: 'Enterprise',
        value: 'enterprise',
    },
    {
        title: 'Team',
        value: 'team',
    },
]

const status = [
    {
        title: 'Active',
        value: 'active',
    },
    {
        title: 'Inactive',
        value: 'inactive',
    },
]

const resolveUserRoleVariant = role => {
    const roleLowerCase = role.toLowerCase()
    if (roleLowerCase === 'subscriber')
        return {
            color: 'success',
            icon: 'bx-user',
        }
    if (roleLowerCase === 'author')
        return {
            color: 'error',
            icon: 'bx-device-desktop',
        }
    if (roleLowerCase === 'maintainer')
        return {
            color: 'info',
            icon: 'bx-chart-pie',
        }
    if (roleLowerCase === 'editor')
        return {
            color: 'warning',
            icon: 'bx-edit',
        }
    if (roleLowerCase === 'admin')
        return {
            color: 'primary',
            icon: 'bx-crown',
        }

    return {
        color: 'primary',
        icon: 'bx-user',
    }
}

const resolveUserStatusVariant = stat => {
    const statLowerCase = stat.toLowerCase()
    if (statLowerCase === 'active')
        return 'success'
    if (statLowerCase === 'inactive')
        return 'error'

    return 'primary'
}

</script>

<template>
    <section>
        <VCard class="mb-6">
            <VCardItem class="pb-4">
                <!-- <div class="text-h4">
                    <VIcon :size="28" :icon="resolveUserRoleVariant('subscriber').icon"
                        :color="resolveUserRoleVariant('subscriber').color" />
                    Usuários
                </div> -->
                <div class="text-h4">
                    <VIcon :size="28" :icon="'bx-user'" :color="'primary'" />
                    Usuários
                </div>
            </VCardItem>

            <VDivider />

            <VCardText class="d-flex flex-wrap gap-4">

                <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                    <!-- 👉 Search  -->
                    <div style="inline-size: 35.625rem;">
                        <VTextField v-model="searchQuery" placeholder="Buscar Email do Usuário" />
                    </div>
                </div>
                <!-- 👉 button find user -->
                <VBtn prepend-icon="bx-search" @click="searchUsers">
                    Buscar
                </VBtn>
                <VSpacer />
                <!-- 👉 Add user button -->
                <VBtn prepend-icon="bx-plus" @click="isAddNewUserDrawerVisible = true">
                    Adicionar Usuário
                </VBtn>
            </VCardText>

            <VDivider />

            <!-- SECTION datatable -->
            <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="users" item-value="id"
                :items-length="totalUsers" :headers="headers" class="text-no-wrap" @update:options="updateOptions">
                <!-- User -->
                <template #item.user="{ item }">
                    <div class="d-flex align-center gap-x-4">
                        <div class="d-flex flex-column">
                            <h6 class="text-base font-weight-medium text-link">
                                {{ item.name }}
                            </h6>
                            <div class="text-sm">
                                {{ item.email }}
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Telefone -->
                <template #item.phone="{ item }">
                    <div class="text-body-1 text-high-emphasis text-capitalize">
                        {{ item.phone }}
                    </div>
                </template>

                <!-- 👉 Role -->
                <template #item.user_module="{ item }">
                    <div class="d-flex align-center gap-x-2">
                        <VIcon v-if="item.admin" :size="22"
                            :icon="resolveUserRoleVariant(item.admin ? 'admin' : 'void').icon"
                            :color="resolveUserRoleVariant(item.admin ? 'admin' : 'void').color" />

                        <VIcon v-if="item.agent" :size="22"
                            :icon="resolveUserRoleVariant(item.operator ? 'maintainer' : 'void').icon"
                            :color="resolveUserRoleVariant(item.operator ? 'maintainer' : 'void').color" />

                    </div>
                </template>

                <!-- Status -->
                <template #item.status="{ item }">
                    <VChip :color="resolveUserStatusVariant(item.deleted_at ? 'Inactive' : 'Active')" size="small" label
                        class="text-capitalize">
                        {{ item.deleted_at ? 'Suspenso' : 'Ativo' }}
                    </VChip>
                </template>

                <!-- Actions -->
                <template #item.actions="{ item }">

                    <ActivateConfirm v-if="item.deleted_at" :user-data="item" @activate-user="activateUser" />

                    <DeleteConfirm v-else :user-data="item" @delete-user="deleteUser" />

                    <!-- 👉 Edit User -->
                    <EditAdminDialog :user-data="item" @edit-user="editUser" />
                </template>

                <!-- pagination -->
                <template #bottom>
                    <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalUsers" />
                </template>
            </VDataTableServer>
            <!-- SECTION -->
        </VCard>
        <!-- 👉 Add New User -->
        <AddNewUserDrawer v-model:isDrawerOpen="isAddNewUserDrawerVisible" @user-data="addNewUser" />
    </section>
</template>
