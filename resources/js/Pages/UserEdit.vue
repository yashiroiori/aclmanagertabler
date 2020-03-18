<template>
    <layout>
        <b-row class="justify-content-md-center">
            <b-col cols="6">
                <b-form class="card" @submit.prevent="onSubmit">
                    <div class="card-header"><h4 class="card-title">{{userModelData.titles.edit}}</h4></div>
                    <div class="card-body pt-2">
                        <b-form-group label="Nombre" label-for="input-default">
                            <b-form-input v-model="form.name" :state="$page.errors.name == undefined ? null : false"></b-form-input>
                            <b-form-invalid-feedback>{{$page.errors.name != undefined ? $page.errors.name[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Apellido" label-for="input-default">
                            <b-form-input v-model="form.last_name" :state="$page.errors.last_name == undefined ? null : false"></b-form-input>
                            <b-form-invalid-feedback>{{$page.errors.last_name != undefined ? $page.errors.last_name[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Correo Electrónico" label-for="input-default">
                            <b-form-input type="email" v-model="form.email" :state="$page.errors.email == undefined ? null : false"></b-form-input>
                            <b-form-invalid-feedback>{{$page.errors.email != undefined ? $page.errors.email[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Teléfono" label-for="input-default">
                            <b-form-input v-model="form.phone" :state="$page.errors.phone == undefined ? null : false"></b-form-input>
                            <b-form-invalid-feedback>{{$page.errors.phone != undefined ? $page.errors.phone[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Celular" label-for="input-default">
                            <b-form-input v-model="form.cell_phone" :state="$page.errors.cell_phone == undefined ? null : false"></b-form-input>
                            <b-form-invalid-feedback>{{$page.errors.cell_phone != undefined ? $page.errors.cell_phone[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Asignar Roles" label-for="input-default">
                            <b-form-tags v-model="form.roles" no-outer-focus>
                                <template v-slot="{ tags, disabled, addTag, removeTag }">
                                    <ul v-if="tags.length > 0" class="list-inline d-inline-block mb-2">
                                        <li v-for="tag in tags" :key="tag" class="list-inline-item">
                                            <b-form-tag
                                                    @remove="removeTag(tag)"
                                                    :title="tag"
                                                    :disabled="disabled"
                                                    variant="info">
                                                {{ getRoleNameById(tag) }}
                                            </b-form-tag>
                                        </li>
                                    </ul>
                                    <b-dropdown size="sm" variant="outline-secondary" block menu-class="w-100">
                                        <template v-slot:button-content>
                                            <i class="fa fa-lock"></i> Asignar Roles
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
                                        <div  style="max-height: 200px; overflow:auto;">
                                            <b-dropdown-item-button
                                                    v-for="option in availableOptions"
                                                    :key="option.id"
                                                    @click="addOption({ option, addTag })">
                                                {{ option.text }}
                                            </b-dropdown-item-button>
                                            <b-dropdown-text v-if="availableOptions.length === 0">
                                                No hay más roles por asignar
                                            </b-dropdown-text>
                                        </div>
                                    </b-dropdown>
                                </template>
                            </b-form-tags>
                            <b-form-invalid-feedback>{{$page.errors.roles != undefined ? $page.errors.roles[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="" label-for="input-default" class="pt-2">
                            <b-form-checkbox v-model="form.admin" plain>Super Administrador</b-form-checkbox>
                            <b-form-invalid-feedback>{{$page.errors.admin != undefined ? $page.errors.admin[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="" label-for="input-default">
                            <b-form-checkbox v-model="form.send_recover_email" plain>Enviar correo para reseteo de contraseña</b-form-checkbox>
                            <b-form-invalid-feedback>{{$page.errors.admin != undefined ? $page.errors.admin[0] : ''}}</b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <inertia-link :href="route(`${userModelData.resource}.index`)" class="btn btn-sm  btn-link">Cancelar</inertia-link>
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
        userModelData: Object,
        user: Object,
        modules: Array,
        roles: Array,
    },
    data(){
        return {
            form: {
                
            },
            loading: false,
            search: '',
        };
    },
    computed: {
        criteria() {
            // Compute the search criteria
            return this.search.trim().toLowerCase()
        },
        availableOptions() {
            const criteria = this.criteria
            // Filter out already selected options
            const options = this.roles.filter(opt => {
                if(this.form.roles != undefined){
                    return this.form.roles.indexOf(opt.text) === -1
                }
                return true
            })
            if (criteria) {
                // Show only options that match criteria
                return options.filter(opt => opt.text.toLowerCase().indexOf(criteria) > -1);
            }
            // Show all options available
            return options
        },
        searchDesc() {
            if (this.criteria && this.availableOptions.length === 0) {
                return 'No se encontraron resultados'
            }
            return ''
        }
    },
    mounted(){
        this.form = this.user
        this.form._method = 'PUT'
    },
    methods: {
        onSubmit(){
            this.loading = true
            this.$inertia.post(route('user.update',this.form.id), this.form)
                .then(response => {
                    this.loading = false
                })
        },
        formUpdated(data){
            this.selectedRoles = data.roles != undefined ? data.roles : []
        },
        addOption({ option, addTag }) {
            addTag(option.id)
            this.search = ''
        },
        getRoleNameById(id){
            let role = this.roles.find(role => {
                if(role.id == id){
                    return role
                }
            })
            return role.text
        },
    },
}

</script>