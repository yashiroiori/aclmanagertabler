<template>
    <layout>
        <resource-index :items="roles" :modelData="$page.roleModelData" :filters="filters" />
    </layout>
</template>

<script>

export default {
    props: {
        roles: Object,
        filters: Object,
    },
}

</script>