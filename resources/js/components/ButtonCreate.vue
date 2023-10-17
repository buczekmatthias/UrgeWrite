<template>
    <button
        class="flex items-center justify-center gap-3 bg-emerald-600 bg-opacity-20 rounded-md p-4 hover:bg-opacity-30"
    >
        <IconCreate />
        <span class="text-lg font-semibold text-emerald-600">
            {{ text }}
        </span>
    </button>
</template>

<script>
import IconCreate from "./IconCreate.vue";

export default {
    name: "ButtonCreate",
    props: {
        text: String,
    },
    components: {
        IconCreate,
    },
};
</script>
