<template>
    <button type="button" @click="executeLogin" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
        Log in
    </button>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {};
    },
    computed: {},
    mounted() {},
    methods: {
        async executeLogin() {
            console.log("Executing login...");
            try {
                const response = await axios.post('/login', {
                    'email': document.getElementById("email").value,
                    'password': document.getElementById("password").value,
                    'token': document.getElementsByName("_token")[0].value
                });

                var users = response.data;
                localStorage.token = users.token;
                localStorage.user_id = users.id;

                console.log(users);

                window.location.href = "/";
            } catch (error) {
                console.error('Erro ao buscar usuários:', error);
            }
        },
    }
};
</script>
