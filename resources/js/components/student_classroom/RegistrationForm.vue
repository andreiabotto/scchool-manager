<template>
    <div class="p-4">
        <h2 class="text-2xl font-bold mb-4" v-if="isEditModeReg">Editar Matricula</h2>
        <h2 class="text-2xl font-bold mb-4" v-else>Cadastrar Matricula</h2>

        <form>
            <!-- Aluno -->
            <div class="mb-4">
                <label for="student_id" class="block text-gray-700">Aluno</label>
                <select v-model="registrations.student_id"
                        id="student_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.student_id }"
                >
                    <option v-for="student in students" :key="student.id" :value="student.id" :selected="student.id === registrations.student_id">{{student.name}}</option>
                </select>
                <span v-if="errors.student_id" class="text-red-500 text-sm">{{ errors.student_id }}</span>
            </div>

            <!-- Turma -->
            <div class="mb-4">
                <label for="classroom_id" class="block text-gray-700">Cursos</label>
                <select v-model="registrations.classroom_id"
                        id="classroom_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.classroom_id }"
                >
                    <option v-for="classroom in classrooms" :key="classroom.id" :value="classroom.id" :selected="classroom.id === registrations.classroom_id">{{classroom.name}}</option>
                </select>
                <span v-if="errors.classroom_id" class="text-red-500 text-sm">{{ errors.classroom_id }}</span>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-between">
                <button
                    type="button"
                    @click="submitFormReg"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                >
                    {{ isEditModeReg ? 'Salvar Alterações' : 'Cadastrar matricula' }}
                </button>
                <button
                    type="button"
                    @click="resetFormReg"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                >
                    Resetar Formulário
                </button>
                <a
                    href="/registrations"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                >
                    Voltar para lista
                </a>
            </div>
        </form>
    </div>
</template>

<script>
import Swal from "sweetalert2";
import axios from "axios";

function getIdFromUrl(url) {
    const regex = /\/registrations\/edit\/(\d+)/;
    const match = url.match(regex)

    if (match && match[1]) {
        return match[1];
    } else {
        return null;
    }
}

export default {
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
            regId: getIdFromUrl(window.location.href),
            isEditModeReg: (this.regId) ? true : false,
            registrations: {
                student_id: '',
                classroom_id: '',
            },
            errors: {},
            students: [],
            classrooms: [],

        };
    },
    mounted() {
        this.isEditModeReg = (this.regId) ? true : false;
        this.fetchClassrooms();
        this.fetchStudents();
        if (this.isEditModeReg) {
            this.loadReg();
        }
    },
    methods: {
        async submitFormReg() {

            if (this.isEditModeReg) {
                try {
                    var data = {
                        "student_id": this.registrations.student_id,
                        "classroom_id": this.registrations.classroom_id
                    };

                    const response = await axios.put(`/api/registrations/${this.regId}`,
                        data,
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var result = response.data.data;
                    if (result) {
                        Swal.fire("Matricula editado com sucesso!", "", "success");
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao editar Matricula, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao editar matricula, tente novamente", "", "error");
                }
            } else {
                try {
                    const response = await axios.post(`/api/matricula`,
                        {
                            "student_id": this.registrations.student_id,
                            "classroom_id": this.registrations.classroom_id
                        },
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var message = response.data.data;
                    if (message) {
                        Swal.fire("Matricula criado com sucesso!", "", "success");
                        this.resetFormReg();
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao criar Matricula, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao criar Matricula, tente novamente", "", "error");
                }
            }
        },
        resetFormReg() {
            this.registrations = {
                student_id: '',
                classroom_id: ''
            };
            this.errors = {}
        },
        async loadReg() {
            try {
                const response = await axios.get('/api/registrations/' + this.regId, {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                var data = response.data.data;
                this.registrations = {
                    student_id: data.student_id,
                    classroom_id: data.classroom_id,
                };
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar matricula',
                    text: 'Não foi possível carregar os dados do matricula. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
                console.error('Erro ao buscar matricula:', error);
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
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar alunos',
                    text: 'Não foi possível carregar os dados dos alunos. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
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
