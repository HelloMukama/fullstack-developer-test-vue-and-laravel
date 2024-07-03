<template>
    <div>
      <label :class="['switch', { checked: isChecked }]" @click="toggle">
        <input type="checkbox" v-model="isChecked" />
        <span class="slider round"></span>
      </label>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Switch',
    props: {
      modelValue: {
        type: Boolean,
        default: false,
      },
    },
    computed: {
      isChecked: {
        get() {
          return this.modelValue;
        },
        set(value) {
          this.$emit('update:modelValue', value);
        },
      },
    },
    methods: {
      toggle() {
        this.isChecked = !this.isChecked;
      },
    },
  };
  </script>
  
  <style scoped>
  .switch {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 30px;
  }
  
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 30px;
  }
  
  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
  }
  
  input:checked + .slider {
    background-color: #4caf50;
  }
  
  input:checked + .slider:before {
    transform: translateX(25px);
  }
  
  input:focus + .slider {
    box-shadow: 0 0 1px #4caf50;
  }
  </style>
  