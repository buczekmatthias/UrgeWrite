<template>
    <div class="flex">
        <div class="max-xl:box w-full !p-0">
            <div
                class="px-4 py-5 cursor-pointer flex justify-between items-center"
                ref="header"
                @click="handleExpand()"
            >
                <p class="font-semibold text-2xl">Note groups</p>
                <p class="text-sm text-link">
                    {{ isExpanded ? "Hide" : "Expand" }}
                </p>
            </div>
            <div class="max-h-0 overflow-hidden flex flex-col gap-2">
                <router-link
                    class="sub-link"
                    :to="{
                        name: 'content',
                        params: { type: $route.params.type, group: item.id },
                    }"
                    v-for="(item, i) in list"
                    :key="i"
                    >{{ item.name }}</router-link
                >
            </div>
        </div>
        <div class=""></div>
    </div>
</template>

<script>
export default {
    name: "NoteGroupsList",
    props: {
        list: Array,
    },
    data() {
        return {
            isExpanded: false,
        };
    },
    methods: {
        handleExpand() {
            this.isExpanded = !this.isExpanded;
            this.$refs.header.parentElement.classList.toggle("expand");
        },
    },
};
</script>
