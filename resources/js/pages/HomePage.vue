<template>
    <div>
        <Hello />
    </div>
</template>

<script>
import HelloComponent from "../components/HelloComponent.vue";

export default {
    name: "Homepage",
    components: {
        Hello: HelloComponent,
    },
};
</script>
