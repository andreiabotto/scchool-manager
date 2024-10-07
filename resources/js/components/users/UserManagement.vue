<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestão de Usuários</h1>

        <div v-if="loading" class="flex items-center justify-center h-64">
            <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 11-4.03 19.62l-.61-.2a1 1 0 00-.86.23l-1.29 1.29a1 1 0 01-1.41 0l-1.29-1.29a1 1 0 00-.86-.23l-.61.2A10 10 0 1112 2z" />
            </svg>
            <span class="ml-4 text-lg text-gray-600">Carregando usuários...</span>
        </div>

        <div v-else>
            <div class="flex items-center justify-between mb-4">
                <user-search @filter-users="filterUsers"></user-search>

                <a
                    href="/users/add"
                    class="ml-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Adicionar Usuário
                </a>
            </div>

            <user-table :users="paginatedUsers"></user-table>
        </div>

        <div class="flex justify-between mt-4">
            <button
                @click="previousPage"
                :disabled="currentPage === 1"
                class="bg-blue-500 text-white px-4 py-2 rounded disabled:opacity-50">
                Anterior
            </button>

            <span class="text-gray-700">Página {{ currentPage }} de {{ totalPages }}</span>

            <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="bg-blue-500 text-white px-4 py-2 rounded disabled:opacity-50">
                Próxima
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import UserSearch from './UserSearch.vue';
import UserTable from './UserTable.vue';

export default {
    components: {
        UserSearch,
        UserTable,
    },
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
            users: [],
            filteredUsers: [],
            currentPage: 1,
            perPage: 5,
            loading: true,
            userId: 0,
            isEditMode: false,
            showForm: false,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredUsers.length / this.perPage);
        },
        paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            if (this.filteredUsers.length > 0) {
                return this.filteredUsers.slice(start, end);
            }
            return this.filteredUsers;
        }
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        filterUsers(searchQuery) {
            this.filteredUsers = this.users.filter(user =>
                user.name.toLowerCase().includes(searchQuery.toLowerCase())
            );
            this.currentPage = 1;
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        async fetchUsers() {
            try {
                const response = await axios.get('/api/users', {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                this.users = response.data.data;
                this.filteredUsers = this.users;
                this.loading = false;
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar usuários',
                    text: 'Não foi possível carregar os dados dos usuários. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
                console.error('Erro ao buscar usuários:', error);
            }
        },
    }
};
</script>
