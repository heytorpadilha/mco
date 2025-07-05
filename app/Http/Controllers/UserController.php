<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function create(){
        //Carregar a VIEW
        return view("users.create");
    }

    public function store(UserRequest $request){
        //dd($request->request);
        try{
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return redirect()->
            route('user.create')->
            with('success', 'Usuário cadastrado com sucesso!'); 
        
        }catch( Exception $e){
            /*  redireciona o usuário para mesma pagina e envia mensagem de erro  */
            return back()->withInput()->with('error', 'Usuário não Cadastrado!');
        }
    }

    public function index(){

        $users = User::orderByDesc('id')->paginate(2);
        return view('users.index', ['users' => $users]);
    }

    public function edit(User $user){
        return view('users.edit', ['user'=> $user]);
    }

   public function update(UserRequest $request, User $user){
        try{
            // editar informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email'=> $request->email,
            ]);
             return redirect()->
            route('user.edit', [$user->id])->
            with('success', 'Usuário editado com sucesso!');            
            
        }catch(Exception $e){
            /*  redireciona o usuário para mesma pagina e envia mensagem de erro  */
            return back()->withInput()->with('error', 'Usuário não editado!');
        }
   }

   public function editPassword(User $user){
        return view('users.editPassword', ['user'=> $user]);
   }

   public function updatePassword(UserRequest $request, User $user){
    try{
        $user->update([
            'password'=> $request->password,
        ]);
        return redirect()->
        route('user.editPassword', [$user->id])->
        with('success', 'Senha do usuário alterada com sucesso!'); 
    }catch( Exception $e){
        return back()->withInput()->with('error', '');
    }
   }
}
