<template>
    <TransitionRoot appear :show="show">
      <Dialog as="template">
        <!-- Overlay -->
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" @click="cancel" />
        </TransitionChild>
  
        <!-- Modal Dialog -->
        <TransitionChild
          as="template"
          enter="transform transition ease-in-out duration-500"
          enter-from="-translate-x-full"
          enter-to="translate-x-0"
          leave="transform transition ease-in-out duration-500"
          leave-from="translate-x-0"
          leave-to="-translate-x-full"
        >
          <DialogPanel class="fixed inset-y-0 right-0 flex justify-center items-center p-4 z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
              <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
              <p class="text-sm mb-4">{{ message }}</p>
              <div class="flex justify-end space-x-4">
                <Button @click="cancel">Cancel</Button>
                <Button @click="confirmDelete" variant="danger">Delete</Button>
              </div>
            </div>
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </template>
  
  <script>
  import { defineComponent, ref } from 'vue';
  
  export default defineComponent({
    props: {
      show: Boolean,
      message: String,
    },
    emits: ['confirm', 'cancel'],
    methods: {
      confirmDelete() {
        this.$emit('confirm');
        this.cancel();
      },
      cancel() {
        this.$emit('cancel');
      }
    }
  });
  </script>
  
  <style>
  /* Add your modal styles here */
  </style>
  