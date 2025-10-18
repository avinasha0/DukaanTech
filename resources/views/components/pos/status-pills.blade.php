<div class="flex items-center gap-2">
    <div class="flex items-center gap-1">
        <div class="w-2 h-2 rounded-full" :class="shift ? 'bg-green-500' : 'bg-red-500'"></div>
        <span class="text-xs font-medium" :class="shift ? 'text-green-700' : 'text-red-700'">
            <span x-text="shift ? 'Shift Open' : 'Shift Closed'"></span>
        </span>
    </div>
    
    <div class="flex items-center gap-1">
        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
        <span class="text-xs font-medium text-blue-700">
            <span x-text="cartItemCount"></span> items
        </span>
    </div>
    
    <div class="flex items-center gap-1">
        <div class="w-2 h-2 rounded-full bg-purple-500"></div>
        <span class="text-xs font-medium text-purple-700">
            ₹<span x-text="cartTotal.toFixed(2)"></span>
        </span>
    </div>
</div>
