<template>
    <div class="relative w-full max-w-xs">
        <input
            type="text"
            v-model="searchQueryClass"
            @input="onSearchClass"
            placeholder="Buscar pelo nome..."
            class="w-full p-2 pl-10 border border-gray-300 rounded"
        />
        <!-- Ícone de lupa -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.58-5.4A7.43 7.43 0 1115 3.61a7.43 7.43 0 013.65 9.33z" />
            </svg>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchQueryClass: '',
        };
    },
    methods: {
        onSearchClass() {
            this.$emit('filter-classroom', this.searchQueryClass);
        }
    }
};
</script>

