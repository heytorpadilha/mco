<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    //view de cadastro do usuário
    public function create()
    {
        //Carregar a VIEW
        return view("users.create");
    }
    //cadastrar novo usuário
    public function store(UserRequest $request)
    {
        //dd($request->request);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return redirect()->
                route('user.show', ['user' => $user])->
                with('success', 'Usuário cadastrado com sucesso!');

        } catch (Exception $e) {
            /*  redireciona o usuário para mesma pagina e envia mensagem de erro  */
            return back()->withInput()->with('error', 'Usuário não Cadastrado!');
        }
    }
    // listagem de usuários
    public function index()
    {

        $users = User::orderByDesc('id')->paginate(2);
        return view('users.index', ['users' => $users]);
    }
    //view de alterar dados do usuário
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }
    //alterar dados do usuário
    public function update(UserRequest $request, User $user)
    {
        try {
            // editar informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            return redirect()->
                route('user.show', [$user->id])->
                with('success', 'Usuário editado com sucesso!');

        } catch (Exception $e) {
            /*  redireciona o usuário para mesma pagina e envia mensagem de erro  */
            return back()->withInput()->with('error', 'Usuário não editado!');
        }
    }
    //view de editar password
    public function editPassword(User $user)
    {
        return view('users.editPassword', ['user' => $user]);
    }
    //alterar senha
    public function updatePassword(UserRequest $request, User $user)
    {
        try {
            $user->update([
                'password' => $request->password,
            ]);
            return redirect()->
                route('user.show', [$user->id])->
                with('success', 'Senha do usuário alterada com sucesso!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', '');
        }
    }
    //visualizar usuário
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }
    //Excluir usuário do banco de dados
    public function destroy(User $user)
    {
        try {
            //excluir o registro do banco de dados
            $user->delete();
            return redirect()
                    ->route('user.index')
                    ->with('success','Usuário excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('user.index')
                ->with('error', 'Usuário não excluído!');
        }
    }
    //gerar pdf do usuário
    public function generatePdf(User $user){
        // montando o pdf e suas configurações, 
        //dentro do loadView('html do pdf', [ objeto com os dados do banco])->setPaper('estilo da folha','orientação')
        $pdf = Pdf::loadView('users.generate-pdf', ['user'=> $user])
                ->setPaper('a4', 'portrait');
        //força o download do arquivo.        
        return $pdf->download('view_usar.pdf');
    }

}
