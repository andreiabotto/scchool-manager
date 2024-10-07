<template>
    <div class="overflow-y-auto" style="height: 450px;">
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">ID</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Nome</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">E-mail</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">E-mail verificado em</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">É admin?</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b">
                <td class="py-2 px-4">{{ user.id }}</td>
                <td class="py-2 px-4">{{ user.name }}</td>
                <td class="py-2 px-4">{{ user.email }}</td>
                <td class="py-2 px-4">{{ user.email_verified_at }}</td>
                <td class="py-2 px-4">{{ user.is_admin }}</td>
                <td class="py-2 px-4">
                    <a
                        :href="'/users/edit/' + user.id"
                        class="bg-yellow-500 text-white px-4 py-2 mr-2 rounded">
                        Editar
                    </a>
                    <button
                        @click="deleteUser(user.id)"
                        class="bg-red-500 text-white px-4 py-2 rounded">
                        Excluir
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from "axios";
import Swal from 'sweetalert2';

export default {
    props: {
        users: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
        }
    },
    methods: {
        deleteUser(userId) {
            Swal.fire({
                title: "Tem certeza que deseja excluir o usuário?",
                showCancelButton: true,
                confirmButtonText: "Deletar",
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    try {
                        console.log({
                            "headers": {
                                "Authorization": `Bearer ${this.authToken}`
                            }
                        });
                        const response = await axios.delete('/api/users/' + userId, {
                            headers: {
                                Authorization: `Bearer ${this.authToken}`
                            }
                        });
                        var message = response.data.message;
                        if(message === "User deleted successfully") {
                            Swal.fire("Usuário excluído com sucesso!", "", "success");
                            window.location.reload();
                        }
                        else {
                            Swal.fire("Erro ao excluir o usuário!", "", "error");
                        }
                    } catch (error) {
                        Swal.fire("Erro ao excluir o usuário!", "", "error");
                        console.error('Erro ao excluir o usuário:', error);
                    }
                }
            });
        }
    }
};
</script>
