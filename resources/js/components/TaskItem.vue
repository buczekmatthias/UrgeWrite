<template>
    <div class="group flex items-center gap-4">
        <label
            class="flex gap-3 border border-solid border-slate-600 rounded-md p-3 cursor-pointer w-full"
            :class="isDone ? 'line-through bg-slate-600 bg-opacity-20' : ''"
            @change="setIsDone"
        >
            <input type="checkbox" :checked="isDone" class="hidden" />
            <p class="">{{ task.content }}</p>
        </label>
        <IconDelete @click="deleteTask" class="hidden group-hover:block" />
    </div>
</template>

<script>
import IconDelete from "./IconDelete.vue";

export default {
    name: "TaskItem",
    props: {
        task: Object,
        isDone: Boolean,
    },
    components: {
        IconDelete,
    },
    emits: {
        deleteTask: null,
        setIsDone: null,
    },
    watch: {
        isDone: function (newVal, oldVal) {},
        task: function (newVal, oldVal) {},
    },
    methods: {
        deleteTask() {
            this.$emit("deleteTask", this.task.id);
        },
        setIsDone() {
            this.$emit("setIsDone", this.task.id, !this.isDone);
        },
    },
};
</script>
