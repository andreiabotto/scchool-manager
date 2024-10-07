<template xmlns="http://www.w3.org/1999/html">
    <div class="p-4">
        <h2 class="text-2xl font-bold mb-4" v-if="isEditModeClass">Editar Turma</h2>
        <h2 class="text-2xl font-bold mb-4" v-else>Cadastrar Turma</h2>

        <form>
            <!-- Nome -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nome</label>
                <input
                    type="text"
                    v-model="classroom.name"
                    id="name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.name }"
                    required
                />
                <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
            </div>

            <!-- description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Descrição</label>
                <textarea
                    v-model="classroom.description"
                    id="classroom"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.classroom }"
                    required
                >
                </textarea>
                <span v-if="errors.classroom" class="text-red-500 text-sm">{{ errors.classroom }}</span>
            </div>

            <!-- Tipo -->
            <div class="mb-4">
                <label for="is_admin" class="block text-gray-700">Tipo</label>
                <select v-model="classroom.type"
                        id="type"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.type }"
                >
                    <option :value="1" :selected="classroom.type === 1">Curso</option>
                    <option :value="2" :selected="classroom.type === 2">Treinamento</option>
                    <option :value="3" :selected="classroom.type === 3">Aula de graduação</option>
                </select>
                <span v-if="errors.type" class="text-red-500 text-sm">{{ errors.type }}</span>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-between">
                <button
                    type="button"
                    @click="submitFormClass"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                >
                    {{ isEditModeClass ? 'Salvar Alterações' : 'Cadastrar Turma' }}
                </button>
                <button
                    type="button"
                    @click="resetFormClass"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                >
                    Resetar Formulário
                </button>
                <a
                    href="/classrooms"
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
    const regex = /\/classrooms\/edit\/(\d+)/;
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
            classId: getIdFromUrl(window.location.href),
            isEditModeClass: (this.classId) ? true : false,
            classroom: {
                "name": '',
                "description": '',
                "type": ''
            },
            errors: {}
        };
    },
    mounted() {
        this.isEditModeClass = (this.classId) ? true : false;
        console.log(this.isEditModeClass);
        if (this.isEditModeClass) {
            this.loadClassroom();
        }
    },
    methods: {
        async submitFormClass() {

            if (this.isEditModeClass) {
                try {
                    var data = {
                        "name": this.classroom.name,
                        "description": this.classroom.description,
                        "type": this.classroom.type,
                    };

                    const response = await axios.put(`/api/classrooms/${this.classId}`,
                        data,
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var result = response.data.data;
                    if (result) {
                        Swal.fire("Turma editado com sucesso!", "", "success");
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao editar turma, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao editar turma, tente novamente", "", "error");
                }
            } else {
                try {
                    const response = await axios.post(`/api/classrooms`,
                        {
                            "name": this.classroom.name,
                            "description": this.classroom.description,
                            "type": this.classroom.type,
                        },
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var message = response.data.data;
                    if (message) {
                        Swal.fire("Turma criado com sucesso!", "", "success");
                        this.resetForm();
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao criar turma, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao criar turma, tente novamente", "", "error");
                }
            }
        },
        resetFormClass() {
            this.classroom = {
                "name": '',
                "description": '',
                "type": ''
            };
            this.errors = {};
        },
        async loadClassroom() {;
            try {
                const response = await axios.get('/api/classrooms/' + this.classId, {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                var data = response.data.data;
                this.classroom = {
                    "name": data.name,
                    "description": data.description,
                    "type": data.type,
                };
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar turma',
                    text: 'Não foi possível carregar os dados do turma. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
                console.error('Erro ao buscar turma:', error);
            }
        },
    }
};
</script>

