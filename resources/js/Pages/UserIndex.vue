<template>
    <layout>
        <resource-index :items="users" :modelData="$page.userModelData" :filters="filters" />
    </layout>
</template>

<script>

export default {
    props: {
        users: Object,
        filters: Object,
    },
}

</script>