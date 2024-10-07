<template>
    <div class="overflow-y-auto" style="height: 450px;">
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">ID</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Nome</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Descriçãol</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Tipo</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="classroom in classrooms" :key="classroom.id" class="border-b">
                <td class="py-2 px-4">{{ classroom.id }}</td>
                <td class="py-2 px-4">{{ classroom.name }}</td>
                <td class="py-2 px-4">{{ classroom.description }}</td>
                <td class="py-2 px-4">{{ classroom.type }}</td>
                <td class="py-2 px-4">
                    <a
                        :href="'/classrooms/edit/' + classroom.id"
                        class="bg-yellow-500 text-white px-4 py-2 mr-2 rounded">
                        Editar
                    </a>
                    <button
                        @click="deleteClassroom(classroom.id)"
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
        classrooms: {
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
        deleteClassroom(classId) {
            Swal.fire({
                title: "Tem certeza que deseja excluir o turma?",
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
                        const response = await axios.delete('/api/classrooms/' + classId, {
                            headers: {
                                Authorization: `Bearer ${this.authToken}`
                            }
                        });
                        var message = response.data.message;
                        if(message === "Classroom deleted successfully") {
                            Swal.fire("Turma excluído com sucesso!", "", "success");
                            window.location.reload();
                        }
                        else {
                            Swal.fire("Erro ao excluir o turma!", "", "error");
                        }
                    } catch (error) {
                        Swal.fire("Erro ao excluir o turma!", "", "error");
                        console.error('Erro ao excluir o turma:', error);
                    }
                }
            });
        }
    }
};
</script>
