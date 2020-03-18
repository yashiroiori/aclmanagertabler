<template>
    <layout>
        <template slot="right_toolbar">
            <!--
            <inertia-link :href="route(`${roleModelData.resource}.back`,role.id)" class="btn btn-sm btn-secondary d-none d-md-inline-block"><i class="fa fa-arrow-left"></i></inertia-link>
            <inertia-link :href="route(`${roleModelData.resource}.next`,role.id)" class="btn btn-sm btn-secondary d-none d-md-inline-block"><i class="fa fa-arrow-right"></i></inertia-link>
            -->
            <inertia-link :href="route(`${roleModelData.resource}.edit`,role.id)" class="btn btn-sm btn-secondary d-none d-md-inline-block" v-if="role.can_edit"><i class="fa fa-edit"></i> Editar</inertia-link>
        </template>
        <b-row class="justify-content-md-center">
            <b-col cols="8">
                <div class="card">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-body">
                        <div class="row row-sm align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-lg" :style="`background-image: url(${role.avatar})`"></span>
                            </div>
                            <div class="col">
                                <h4 class="card-title m-0">{{role.name}}</h4>
                                <div class="text-muted">Actualizado: {{role.updated_ago}}</div>
                                <div class="text-muted">Registrado: {{role.created_ago}}</div>
                            </div>
                            <div class="col-auto">
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs" data-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tabs-users" class="nav-link active" data-toggle="tab">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabs-permissions" class="nav-link" data-toggle="tab">Permisos</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tabs-users">
                                <div class="list list-row list-hoverable">
                                    <template v-if="role.users.length > 0">
                                        <div class="list-item" v-for="user in role.users">
                                            <inertia-link :href="route('user.show',user.id)"><span class="avatar" :style="`background-image: url(${user.avatar})`"></span></inertia-link>
                                            <div class="text-truncate">
                                                <inertia-link :href="route('user.show',user.id)" class="text-body d-block">{{user.full_name}}</inertia-link>
                                                <small class="d-block text-muted text-truncate mt-n1">{{user.email}}</small>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="list-item">
                                            No se han asignado usuarios
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-permissions">
                                <div class="card mb-0 border-0" v-for="module in modules">
                                    <div class="card-status-bottom bg-primary"></div>
                                    <div class="card-header p-1">
                                        <h3 class="card-title">{{module.label}}</h3>
                                    </div>
                                    <div class="table-responsive mb-0">
                                        <table class="table table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Módulo</th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Navegar"><i class="fa fa-th-list"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Leer"><i class="fa fa-eye"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Agregar"><i class="fa fa-plus"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Editar"><i class="fa fa-edit"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Eliminar"><i class="fa fa-trash"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Restaurar"><i class="fa fa-undo"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Importar"><i class="fa fa-upload"></i></th>
                                                    <th class="text-center" style="width: 60px;" v-b-tooltip.hover title="Exportar"><i class="fa fa-download"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="children in module.children">
                                                    <td>{{children.label}}</td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('browse-'+children.slug) ? `<i class='fa fa-check'></i>` : `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('read-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('add-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('edit-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('delete-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('restore-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('import-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                    <td class="text-center" style="width: 60px;" v-html="role.permission.includes('export-'+children.slug) ? `<i class='fa fa-check'></i>`: `<i class='fa fa-times'></i>`"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </b-col>
        </b-row>
    </layout>
</template>

<script>

export default {
    props: {
        role: Object,
        modules: Array,
        roleModelData: Object,
    },
}

</script>