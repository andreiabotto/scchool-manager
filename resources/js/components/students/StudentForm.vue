<template>
    <div class="p-4">
        <h2 class="text-2xl font-bold mb-4" v-if="isEditModeStudent">Editar Aluno</h2>
        <h2 class="text-2xl font-bold mb-4" v-else>Cadastrar Aluno</h2>

        <form>
            <!-- Nome -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nome</label>
                <input
                    type="text"
                    v-model="student.name"
                    id="name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.name }"
                    required
                />
                <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
            </div>

            <!-- Data de nascimento -->
            <div class="mb-4">
                <label for="date_of_birth" class="text-gray-700 font-medium">Data de nascimento:</label>

                <input
                    type="date"
                    id="date_of_birth"
                    v-model="student.date_of_birth"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.date_of_birth }"
                    required
                />
                <span v-if="errors.date_of_birth" class="text-red-500 text-sm">{{ errors.date_of_birth }}</span>
            </div>

            <!-- Usuario -->
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700">Usuário</label>
                <select v-model="student.user_id"
                        id="user_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.user_id }"
                >
                    <option v-for="user in users" :key="user.id" :value="user.id" :selected="user.id === student.user_id">{{user.name}}</option>
                </select>
                <span v-if="errors.user_id" class="text-red-500 text-sm">{{ errors.user_id }}</span>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-between">
                <button
                    type="button"
                    @click="submitFormStudent"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                >
                    {{ isEditModeStudent ? 'Salvar Alterações' : 'Cadastrar Aluno' }}
                </button>
                <button
                    type="button"
                    @click="resetFormStudent"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                >
                    Resetar Formulário
                </button>
                <a
                    href="/students"
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
    const regex = /\/students\/edit\/(\d+)/;
    const match = url.match(regex)

    if (match && match[1]) {
        return match[1];
    } else {
        return null;
    }
}

function formatDate(dateString) {
    // Divide a string em duas partes: data e hora
    const [datePart] = dateString.split(' '); // Ignorando a parte da hora

    // Substitui as barras por hífens e reordena os componentes da data
    const [day, month, year] = datePart.split('\/');

    // Retorna no formato desejado: "YYYY-MM-DD"
    return `${year}-${month}-${day}`;
}

export default {
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
            studentId: getIdFromUrl(window.location.href),
            isEditModeStudent: (this.studentId) ? true : false,
            student: {
                "name": '',
                "date_of_birth": '',
                "user_id": ''
            },
            errors: {},
            users: {},
        };
    },
    mounted() {
        this.isEditModeStudent = (this.studentId) ? true : false;
        this.fetchUsers();
        if (this.isEditModeStudent) {
            this.loadStudent();
        }
    },
    methods: {
        async submitFormStudent() {

            if (this.isEditModeStudent) {
                try {
                    var data = {
                        "name": this.student.name,
                        "date_of_birth": this.student.date_of_birth,
                        "user_id": this.student.user_id,
                    };

                    const response = await axios.put(`/api/students/${this.studentId}`,
                        data,
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var result = response.data.data;
                    if (result) {
                        Swal.fire("Aluno editado com sucesso!", "", "success");
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao editar aluno, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao editar aluno, tente novamente", "", "error");
                }
            } else {
                try {
                    const response = await axios.post(`/api/students`,
                        {
                            "name": this.student.name,
                            "date_of_birth": this.student.date_of_birth,
                            "user_id": this.student.user_id,
                        },
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var message = response.data.data;
                    if (message) {
                        Swal.fire("Aluno criado com sucesso!", "", "success");
                        this.resetForm();
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao criar aluno, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao criar aluno, tente novamente", "", "error");
                }
            }
        },
        resetFormStudent() {
            this.student = {
                "name": '',
                "date_of_birth": '',
                "user_id": ''
            };
            this.errors = {};
        },
        async loadStudent() {
            try {
                const response = await axios.get('/api/students/' + this.studentId, {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                var data = response.data.data;
                this.student = {
                    "name": data.name,
                    "date_of_birth": formatDate(data.date_of_birth),
                    "user_id": data.user_id,
                };
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar aluno',
                    text: 'Não foi possível carregar os dados do aluno. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
                console.error('Erro ao buscar aluno:', error);
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
