<div class="mb-4">
    <div class="flex space-x-2 overflow-x-auto pb-2">
        <button 
            @click="selectedCategory = null"
            :class="selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border'"
            class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors"
        >
            All
        </button>
        
        <template x-for="category in categories" :key="category.id">
            <button 
                @click="selectCategory(category)"
                :class="selectedCategory?.id === category.id ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border'"
                class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors"
                x-text="category.name"
            ></button>
        </template>
    </div>
</div>
