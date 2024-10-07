<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestão de Turmas</h1>

        <div v-if="loading" class="flex items-center justify-center h-64">
            <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 11-4.03 19.62l-.61-.2a1 1 0 00-.86.23l-1.29 1.29a1 1 0 01-1.41 0l-1.29-1.29a1 1 0 00-.86-.23l-.61.2A10 10 0 1112 2z" />
            </svg>
            <span class="ml-4 text-lg text-gray-600">Carregando turmas...</span>
        </div>

        <div v-else>
            <div class="flex items-center justify-between mb-4">
                <class-search @filter-classroom="filterClassroom"></class-search>

                <a
                    href="/classrooms/add"
                    class="ml-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Adicionar Turma
                </a>
            </div>

            <class-table :classrooms="paginatedClassrooms"></class-table>
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
import ClassSearch from './ClassSearch.vue';
import ClassTable from './ClassTable.vue';

export default {
    components: {
        ClassSearch,
        ClassTable,
    },
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
            classrooms: [],
            filteredClassrooms: [],
            currentPage: 1,
            perPage: 5,
            loading: true,
            classId: 0,
            isEditModeClass: false,
            showFormClass: false,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredClassrooms.length / this.perPage);
        },
        paginatedClassrooms() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            if (this.filteredClassrooms.length > 0) {
                return this.filteredClassrooms.slice(start, end);
            }
            return this.filteredClassrooms;
        }
    },
    mounted() {
        this.fetchClassrooms();
    },
    methods: {
        filterClassroom(searchQueryClass) {
            this.filteredClassrooms = this.classrooms.filter(classroom =>
                classroom.name.toLowerCase().includes(searchQueryClass.toLowerCase())
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
        async fetchClassrooms() {
            try {
                const response = await axios.get('/api/classrooms', {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                this.classrooms = response.data.data;
                this.filteredClassrooms = this.classrooms;
                this.loading = false;
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar turmas',
                    text: 'Não foi possível carregar os dados dos turmas. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
                console.error('Erro ao buscar turmas:', error);
            }
        },
    }
};
</script>
