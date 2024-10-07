<template>
    <div class="p-4">
        <h2 class="text-2xl font-bold mb-4" v-if="isEditMode">Editar Usuário</h2>
        <h2 class="text-2xl font-bold mb-4" v-else>Cadastrar Usuário</h2>

        <form>
            <!-- Nome -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nome</label>
                <input
                    type="text"
                    v-model="user.name"
                    id="name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.name }"
                    required
                />
                <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input
                    type="email"
                    v-model="user.email"
                    id="email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.email }"
                    required
                />
                <span v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</span>
            </div>

            <!-- Senha (somente em modo de cadastro ou redefinição de senha) -->
            <div class="mb-4" v-if="!isEditMode || user.password">
                <label for="password" class="block text-gray-700">Senha</label>
                <input
                    type="password"
                    v-model="user.password"
                    id="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.password }"
                    :required="!isEditMode"
                />
                <span v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</span>
            </div>

            <!-- Confirmação de Senha -->
            <div class="mb-4" v-if="!isEditMode || user.password">
                <label for="password_confirmation" class="block text-gray-700">Confirmar Senha</label>
                <input
                    type="password"
                    v-model="user.password_confirmation"
                    id="password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.password_confirmation }"
                    :required="!isEditMode"
                />
                <span v-if="errors.password_confirmation" class="text-red-500 text-sm">{{ errors.password_confirmation }}</span>
            </div>

            <!-- É Administrador -->
            <div class="mb-4">
                <label for="is_admin" class="block text-gray-700">Administrador</label>
                <select v-model="user.is_admin"
                        id="is_admin"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.is_admin }"
                >
                    <option :value="1" :selected="user.is_admin === 1">Ativo</option>
                    <option :value="0" :selected="user.is_admin === 0">Inativo</option>
                </select>
                <span v-if="errors.is_admin" class="text-red-500 text-sm">{{ errors.is_admin }}</span>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-between">
                <button
                    type="button"
                    @click="submitForm"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                >
                    {{ isEditMode ? 'Salvar Alterações' : 'Cadastrar Usuário' }}
                </button>
                <button
                    type="button"
                    @click="resetForm"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                >
                    Resetar Formulário
                </button>
                <a
                    href="/users"
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
    const regex = /\/users\/edit\/(\d+)/;
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
            userId: getIdFromUrl(window.location.href),
            isEditMode: (this.userId) ? true : false,
            user: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                is_admin: false,
            },
            errors: {},
        };
    },
    mounted() {
        this.isEditMode = (this.userId) ? true : false;
        if (this.isEditMode) {
            this.loadUser();
        }
    },
    methods: {
        async submitForm() {

            if (this.isEditMode) {
                try {
                    var data = {
                        "name": this.user.name,
                        "email": this.user.email,
                        "is_admin": this.user.is_admin,
                    };

                    const response = await axios.put(`/api/users/${this.userId}`,
                        data,
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var result = response.data.data;
                    if (result) {
                        Swal.fire("Usuário editado com sucesso!", "", "success");
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao editar usuário, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao editar usuário, tente novamente", "", "error");
                }
            } else {
                try {
                    const response = await axios.post(`/api/users`,
                        {
                            "name": this.user.name,
                            "email": this.user.email,
                            "password": this.user.password,
                            "password_confirmation": this.user.password_confirmation,
                            "is_admin": this.user.is_admin,
                        },
                        {
                            headers: {Authorization: `Bearer ${this.authToken}`}
                        }
                    );

                    var message = response.data.data;
                    if (message) {
                        Swal.fire("Usuário criado com sucesso!", "", "success");
                        this.resetForm();
                    } else if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        Swal.fire("Erro ao criar usuário, tente novamente", "", "error");
                    }
                } catch (error) {
                    if (error.response.status === 422) {
                        if (error.response.data) {
                            this.errors = error.response.data.errors;
                        }
                    }

                    Swal.fire("Erro ao criar usuário, tente novamente", "", "error");
                }
            }
        },
        resetForm() {
            this.user = {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                is_admin: false,
            };
            this.errors = {}
        },
        async loadUser() {
            try {
                const response = await axios.get('/api/users/' + this.userId, {
                    headers: {
                        Authorization: `Bearer ${this.authToken}`
                    }
                });

                var data = response.data.data;
                this.user = {
                    name: data.name,
                    email: data.email,
                    password: '',
                    password_confirmation: '',
                    is_admin: data.is_admin,
                };
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao carregar usuário',
                    text: 'Não foi possível carregar os dados do usuário. Por favor, tente novamente mais tarde.',
                    confirmButtonText: 'OK'
                });
                console.error('Erro ao buscar usuário:', error);
            }
        },
    }
};
</script>
