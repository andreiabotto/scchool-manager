<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestão de Alunos</h1>

        <div v-if="loading" class="flex items-center justify-center h-64">
            <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 11-4.03 19.62l-.61-.2a1 1 0 00-.86.23l-1.29 1.29a1 1 0 01-1.41 0l-1.29-1.29a1 1 0 00-.86-.23l-.61.2A10 10 0 1112 2z" />
            </svg>
            <span class="ml-4 text-lg text-gray-600">Carregando alunos...</span>
        </div>

        <div v-else>
            <div class="flex items-center justify-between mb-4">
                <student-search @filter-students="filterStudents"></student-search>

                <a
                    href="/students/add"
                    class="ml-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Adicionar Alunos
                </a>
            </div>

            <student-table :students="paginatedStudents"></student-table>
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
import StudentSearch from './StudentSearch.vue';
import StudentTable from './StudentTable.vue';

export default {
    components: {
        StudentSearch,
        StudentTable,
    },
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
            students: [],
            filteredStudents: [],
            currentPage: 1,
            perPage: 5,
            loading: true,
            studentId: 0,
            isEditMode: false,
            showForm: false,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.filteredStudents.length / this.perPage);
        },
        paginatedStudents() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            if (this.filteredStudents.length > 0) {
                return this.filteredStudents.slice(start, end);
            }
            return this.filteredStudents;
        }
    },
    mounted() {
        this.fetchStudents();
    },
    methods: {
        filterStudents(searchQueryStudent) {
            this.filteredStudents = this.students.filter(student =>
                student.name.toLowerCase().includes(searchQueryStudent.toLowerCase())
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
        async fetchStudents() {
            try {
                const response = await axios.get('/api/students', {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                this.students = response.data.data;
                this.filteredStudents = this.students;
                this.loading = false;
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar alunos',
                    text: 'Não foi possível carregar os dados dos alunos. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
            }
        },
    }
};
</script>
