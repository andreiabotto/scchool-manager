<template>
    <div class="overflow-y-auto" style="height: 450px;">
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">ID</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Aluno</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Curso</th>
                <th class="py-2 px-4 bg-gray-200 text-gray-600 text-left">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="registration in registrations" :key="registration.id" class="border-b">
                <td class="py-2 px-4">{{ registration.id }}</td>
                <td class="py-2 px-4">{{ registration.student_id }}</td>
                <td class="py-2 px-4">{{ registration.classroom_id }}</td>
                <td class="py-2 px-4">
                    <a
                        :href="'/registrations/edit/' + registration.id"
                        class="bg-yellow-500 text-white px-4 py-2 mr-2 rounded">
                        Editar
                    </a>
                    <button
                        @click="deleteReg(registration.id)"
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
        registrations: {
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
        deleteReg(regId) {
            Swal.fire({
                title: "Tem certeza que deseja excluir a matricula?",
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
                        const response = await axios.delete('/api/registrations/' + regId, {
                            headers: {
                                Authorization: `Bearer ${this.authToken}`
                            }
                        });
                        var message = response.data.message;
                        if(message === "Registration deleted successfully") {
                            Swal.fire("Matricula excluído com sucesso!", "", "success");
                            window.location.reload();
                        }
                        else {
                            Swal.fire("Erro ao excluir o matricula!", "", "error");
                        }
                    } catch (error) {
                        Swal.fire("Erro ao excluir o matricula!", "", "error");
                        console.error('Erro ao excluir o matricula:', error);
                    }
                }
            });
        }
    }
};
</script>
