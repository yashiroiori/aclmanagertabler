<template>
    <layout>
        <template slot="right_toolbar">
            <inertia-link href="route(`${userModelData.resource}.back`,user.id)" class="btn btn-sm btn-secondary d-none d-md-inline-block"><i class="fa fa-arrow-left"></i></inertia-link>
            <inertia-link href="route(`${userModelData.resource}.next`,user.id)" class="btn btn-sm btn-secondary d-none d-md-inline-block"><i class="fa fa-arrow-right"></i></inertia-link>
            <inertia-link :href="route(`${userModelData.resource}.edit`,user.id)" class="btn btn-sm btn-secondary d-none d-md-inline-block" v-if="user.can_edit"><i class="fa fa-edit"></i> Editar</inertia-link>
        </template>
        <b-row class="justify-content-md-center">
            <b-col cols="8">
                <div class="card">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-body">
                        <div class="row row-sm align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-lg" :style="`background-image: url(${user.avatar})`"></span>
                            </div>
                            <div class="col">
                                <h4 class="card-title m-0">{{user.name}}</h4>
                                <div class="text-muted">Actualizado: {{user.updated_ago}}</div>
                                <div class="text-muted">Registrado: {{user.created_ago}}</div>
                            </div>
                            <div class="col-auto">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs" data-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tabs-users" class="nav-link active" data-toggle="tab">Roles</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tabs-users">
                                <div class="list list-row list-hoverable">
                                    <template v-if="user.roles.length > 0">
                                        <div class="list-item" v-for="role in user.roles">
                                            <inertia-link :href="route('role.show',role.id)"><span class="avatar" :style="`background-image: url(${role.avatar})`"></span></inertia-link>
                                            <div class="text-truncate">
                                                <inertia-link :href="route('role.show',role.id)" class="text-body d-block">{{role.name}}</inertia-link>
                                                <small class="d-block text-muted text-truncate mt-n1">Permisos: {{role.permission_count}}</small>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="list-item">
                                            No se han asignado roles
                                        </div>
                                    </template>
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
        user: Object,
        userModelData: Object,
    },
}

</script>