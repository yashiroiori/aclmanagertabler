<?php

namespace Yashiroiori\AclManagerTabler\Traits;

use DB;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Yashiroiori\AclManagerTabler\Http\Requests\User\UserStoreRequest;
use Yashiroiori\AclManagerTabler\Http\Requests\User\UserUpdateRequest;

trait UserTrait
{
    use AuthorizesRequests;

    public function __construct()
    {
        Inertia::share([
            'userModelData' => User::modelData(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', User::class);

        return Inertia::render('Package/yashiroiori/aclmanagertabler/UserIndex',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'users' => User::withTrashed()->usingSearchString($request->get('search'))->where('id','<>',auth()->user()->id)->paginate($request->get('per_page') ?? 15)->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $roles = Role::active()->get()->map(function ($role) {
            return [
                'id' => $role->name,
                'text' => $role->name,
            ];
        })->toArray();
        return Inertia::render('Package/yashiroiori/aclmanagertabler/UserCreate',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = new User($request->except('roles'));
            $user->password = '/(-.-)/';
            $user->save();
            $user->syncRoles($request->get('roles'));
            return Redirect::route('user.show',$user->id)->with('success', 'Usuario: '.$user->name.' registrado');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->load('roles');
        return Inertia::render('Package/yashiroiori/aclmanagertabler/UserShow',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', User::class);
        $roles = Role::active()->get()->map(function ($role) {
            return [
                'id' => $role->name,
                'text' => $role->name,
            ];
        })->toArray();
        $user->roles = $user->roles()->select('id','name')->get()->map(function ($role) {
            return $role->name;
        })->toArray();
        return Inertia::render('Package/yashiroiori/aclmanagertabler/UserEdit',[
            'breadcrumbs' => Breadcrumbs::generate(),
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        return DB::transaction(function () use ($request, $user) {
            $user->fill($request->except('roles'));
            $user->save();
            $user->syncRoles($request->get('roles'));
            return Redirect::route('user.show',$user->id)->with('success', 'Usuario: '.$user->name.' actualizado');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return DB::transaction(function () use ($user) {
            if(!$user->trashed()){
                $user->delete();
                return Redirect::back()->with('success', 'Usuario: '.$user->name.' eliminado');
            } else {
                $user->forceDelete();
                return Redirect::route('user.index')->with('success', 'Usuario: '.$user->name.' eliminado permanentemente');
            }
        });
    }

    /**
     * Restore the specified resource from storage.
     * @param string $role
     * @return Response
     */
    public function restore(User $user)
    {
        $this->authorize('restore', $user);
        return DB::transaction(function () use ($user) {
            if($user->restore()){
                return Redirect::back()->with('success', 'Usuario: '.$user->name.' restaurado');
            }
            return Redirect::back()->with('error', 'Ocurrio un erro al tratar de restaurar el rol: '.$user->name);
        });
    }

    /**
     * Archive the specified resource from storage.
     * @param string $role
     * @return Response
     */
    public function archive(User $user)
    {
        $this->authorize('update', $user);
        return DB::transaction(function () use ($user) {
            $user->update(['archived' => !$user->archived]);
            return Redirect::back()->with('success', 'Usuario: '.$user->name.' '.($user->archived == 1 ? 'archivado' : 'activo'));
        });
    }

    public function batchAction(Request $request)
    {
        $actionText = __('admin.status.'.$request->get('action'));
        User::unsetEventDispatcher();
        foreach($request->get('items') AS $item){
            $user = User::withTrashed()->find($item);
            if($user){
                switch($request->get('action')){
                    case 'active':
                        $this->authorize('update', $user);
                        $user->update(['archived' => false]);
                    break;
                    case 'archive':
                        $this->authorize('update', $user);
                        $user->update(['archived' => true]);
                    break;
                    case 'restore':
                        $this->authorize('restore', $user);
                        $user->restore();
                    break;
                    case 'delete':
                        $this->authorize('delete', $user);
                        $user->delete();
                    break;
                    case 'force-delete':
                        $this->authorize('force-delete', $user);
                        $user->forceDelete();
                    break;
                }
            }
        }
        return Redirect::back()->with('success', 'Registros: '.$actionText);
    }
}