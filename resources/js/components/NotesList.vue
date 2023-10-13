<template>
    <div>
        <router-link
            :to="{
                name: 'content',
                params: {
                    type: $route.params.type,
                    group: item.group_id,
                    elementId: item.id,
                },
            }"
            v-for="(item, i) in list"
            :key="i"
        ></router-link>
    </div>
</template>

<script>
export default {
    name: "NotesList",
    props: {
        list: Array,
    },
};
</script>
