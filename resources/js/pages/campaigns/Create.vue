<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const isOpen = defineModel<boolean>('isOpen');
isOpen.value = true;

</script>
<template>
    <Dialog class="p-3" :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="w-[calc(100%-1.5rem)] max-w-none">
            <DialogHeader class="flex items-center justify-center">
                <div
                    class="border-border bg-card mb-3 w-full border p-2 shadow-sm"
                >
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus imperdiet ac neque ac porttitor. Sed sit amet odio maximus, euismod lacus ac, aliquet quam. Donec bibendum dolor a magna aliquet, at maximus massa mattis. Nulla placerat, enim vitae condimentum ultrices, arcu ante tristique lectus, quis vehicula est turpis ut mi. Integer vel arcu augue. Donec imperdiet, tortor vitae porta facilisis, velit turpis suscipit nulla, non venenatis eros lorem vitae sem. Proin porta tempor magna varius viverra. Sed eget vestibulum mi. Curabitur quis tellus et dui tristique tempor. Duis imperdiet sem ac nunc finibus pretium. Phasellus tristique rutrum arcu, sed ultricies velit rhoncus suscipit.
                </div>
            </DialogHeader>
        </DialogContent>
    </Dialog>            
</template>