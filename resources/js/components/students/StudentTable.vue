<template>
    <div class="overflow-y-auto" style="height: 450px;">
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">ID</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Nome</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Data de nascimento</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Usuário</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Ações</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.id" class="border-b">
                    <td class="py-2 px-4">{{ student.id }}</td>
                    <td class="py-2 px-4">{{ student.name }}</td>
                    <td class="py-2 px-4">{{ student.date_of_birth }}</td>
                    <td class="py-2 px-4">{{ student.user_id }}</td>
                    <td class="py-2 px-4">
                        <a
                            :href="'/students/edit/' + student.id"
                            class="bg-yellow-500 text-white px-4 py-2 mr-2 rounded">
                            Editar
                        </a>
                        <button
                            @click="deleteStudent(student.id)"
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
        students: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            authToken: localStorage.getItem('token') || '',
        }
    },
    mounted() {
    },
    methods: {
        deleteStudent(studentId) {
            Swal.fire({
                title: "Tem certeza que deseja excluir o aluno?",
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
                        const response = await axios.delete('/api/students/' + studentId, {
                            headers: {
                                Authorization: `Bearer ${this.authToken}`
                            }
                        });
                        var message = response.data.message;
                        if(message === "Student deleted successfully") {
                            Swal.fire("Aluno excluído com sucesso!", "", "success");
                            window.location.reload();
                        }
                        else {
                            Swal.fire("Erro ao excluir o aluno!", "", "error");
                        }
                    } catch (error) {
                        Swal.fire("Erro ao excluir o aluno!", "", "error");
                        console.error('Erro ao excluir o aluno:', error);
                    }
                }
            });
        }
    }
};
</script>

