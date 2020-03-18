<template>
    <layout>
        <b-row class="justify-content-md-center">
            <b-col cols="6">
                <b-form class="card" @submit.prevent="onSubmit">
                    <div class="card-header"><h4 class="card-title">{{roleModelData.titles.edit}}</h4></div>
                    <div class="card-body pt-2">
                        <b-form-group label="Nombre" label-for="input-default">
                            <b-form-input v-model="form.name" :state="$page.errors.name == undefined ? null : false"></b-form-input>
                            <b-form-invalid-feedback>{{$page.errors.name != undefined ? $page.errors.name[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Asignar Usuarios" label-for="input-default" class="pt-2">
                            <b-form-tags v-model="form.users" no-outer-focus class="mb-2">
                                <template v-slot="{ tags, disabled, addTag, removeTag }">
                                    <ul v-if="tags.length > 0" class="list-inline d-inline-block mb-2">
                                        <li v-for="tag in tags" :key="tag" class="list-inline-item">
                                            <b-form-tag
                                                    @remove="removeTag(tag)"
                                                    :title="tag"
                                                    :disabled="disabled"
                                                    variant="info">
                                                {{ getUserNameById(tag) }}
                                            </b-form-tag>
                                        </li>
                                    </ul>
                                    <b-dropdown size="sm" variant="outline-secondary" block menu-class="w-100">
                                        <template v-slot:button-content>
                                            <i class="fa fa-user"></i> Asignar usuarios
                                        </template>
                                        <b-dropdown-form @submit.stop.prevent="() => {}">
                                            <b-form-group
                                                    label-for="tag-search-input"
                                                    label="Buscar"
                                                    label-cols-md="auto"
                                                    class="mb-0"
                                                    label-size="sm"
                                                    :description="searchDesc"
                                                    :disabled="disabled">
                                                <b-form-input
                                                    v-model="search"
                                                    id="tag-search-input"
                                                    type="search"
                                                    size="sm"
                                                    autocomplete="off">
                                                </b-form-input>
                                            </b-form-group>
                                        </b-dropdown-form>
                                        <b-dropdown-divider></b-dropdown-divider>
                                        <b-dropdown-item-button
                                                v-for="option in availableOptions"
                                                :key="option.id"
                                                @click="addOption({ option, addTag })">
                                            {{ option.text }}
                                        </b-dropdown-item-button>
                                        <b-dropdown-text v-if="availableOptions.length === 0">
                                            No hay más usuarios por asignar
                                        </b-dropdown-text>
                                    </b-dropdown>
                                </template>
                            </b-form-tags>
                        </b-form-group>
                        <b-form-group label="Asignar Permisos" label-for="input-default" class="pt-2">
                            <treeselect v-model="form.permission" placeholder="Asignar permisos..." :multiple="true" :options="modules" valueConsistsOf="LEAF_PRIORITY" />
                        </b-form-group>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <inertia-link :href="route('role.index')" class="btn btn-sm btn-link">Cancelar</inertia-link>
                            <button type="submit" class="btn btn-sm btn-primary ml-auto" :disabled="loading" :class="loading ? 'btn-loading' : ''"><i class="fa fa-send mr-1"></i> Enviar</button>
                        </div>
                    </div>
                </b-form>
            </b-col>
        </b-row>
    </layout>
</template>

<script>

export default {
    props: {
        roleModelData: Object,
        role: Object,
        modules: Array,
        users: Array,
    },
    data(){
        return {
            form: {
                _method: 'PUT',
            },
            loading: false,
            search: '',
            selectedUsers: [],
        };
    },
    mounted(){
        this.form = this.role
        this.form._method = 'PUT'
    },
    computed: {
        criteria() {
            // Compute the search criteria
            return this.search.trim().toLowerCase()
        },
        availableOptions() {
            const criteria = this.criteria
            // Filter out already selected options
            const options = this.users.filter(opt => {
                if(this.form.users != undefined){
                    return this.form.users.indexOf(opt.id) === -1
                }
                return true
            })
            if (criteria) {
                // Show only options that match criteria
                return options.filter(opt => opt.name.toLowerCase().indexOf(criteria) > -1);
            }
            // Show all options available
            return options
        },
        searchDesc() {
            if (this.criteria && this.availableOptions.length === 0) {
                return 'There are no tags matching your search criteria'
            }
            return ''
        },
    },
    methods: {
        onSubmit(){
            this.loading = true
            this.$inertia.post(route('role.update',this.form.id), this.form)
                .then(response => {
                    this.loading = false
                })
        },
        onOptionClick({ option, addTag }) {
            addTag(option)
            this.search = ''
        },
        formUpdated(data){
            this.selectedUsers = data.users != undefined ? data.users : []
        },
        addOption({ option, addTag }) {
            addTag(option.id)
            this.search = ''
        },
        getUserNameById(id){
            let user = this.users.find(user => {
                if(user.id == id){
                    return user
                }
            })
            return user.text
        },
    },
}

</script>