<?php

namespace App\Http\Controllers;

use App\Mail\UserPdfMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        // recuperando registros do banco de dados
        // $users = User::orderByDesc('id')->paginate(2);
        $users = User::query();
        $this->filterIndex($users, $request);
        $users = $users->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();
        return view('users.index', [
            'users' => $users,
            'name' => $request->name,
            'email' => $request->email,
            'start_date_registration' => $request->start_date_registration,
            'end_date_registration' => $request->end_date_registration,
        ]);
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
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('user.index')
                ->with('error', 'Usuário não excluído!');
        }
    }
    //gerar pdf do usuário
    public function generatePdf(User $user)
    {

        try {

            // montando o pdf e suas configurações, 
            //dentro do loadView('html do pdf', [ objeto com os dados do banco])->setPaper('estilo da folha','orientação')
            $pdf = Pdf::loadView('users.generate-pdf', ['user' => $user])
                ->setPaper('a4', 'portrait');

            //força o download do arquivo.        
            // return $pdf->download('view_usar.pdf');
            // Definir caminho temporario para salvar pdf antes de enviar o email.
            $pdfPath = storage_path('app/public/view_user_' . $user->id . '.pdf');

            //Salvar o pdf localmente
            $pdf->save($pdfPath);

            //enviar o email com o pdf anexado
            Mail::to($user->email)
                ->send(new UserPdfMail($pdfPath, $user));

            //remover arquivo temporário
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            // redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('user.show', ['user' => $user->id])
                ->with('success', 'E-mail enviado com sucesso!');

        } catch (Exception $e) {

            return redirect()->route('user.show', ['user' => $user->id])
                ->with('error', 'E-mail não enviado!');
        }

    }

    public function generatePdfUsers(Request $request)
    {
        try {
            $users = User::query();
            $this->filterIndex($users, $request);
            $users = $users->orderByDesc('name')
                ->get();
            //Somar o total de registros 
            $totalRecord = $users->count('id');
            // verificar se a quantidade de resitros ultrapassa a o limite para gerar o PDF
            $numberRecordsAllowed = 50;
            if ($totalRecord > $numberRecordsAllowed) {

                return redirect()->route('user.index', [
                    'name' => $request->name,
                    'email' => $request->email,
                    'start_date_registration' => $request->start_date_registration,
                    'end_date_registration' => $request->end_date_registration,
                ])->with(
                        'error',
                        'Limite de registros foi ultrapassado para gerar PDF. ' .
                        'O limite é de ' . $numberRecordsAllowed
                    );
            }

            $pdf = Pdf::loadView(
                'users.generate-pdf-users',
                ['users' => $users]
            )->setPaper('a4', 'portrait');

            // Fazer o download do arquivo
            return $pdf->download('lista_usuarios.pdf');

        } catch (Exception $e) {
            //redirecionar o usuario para pagina de listagem com msg de pdf não gertado
            return redirect()->route('user.index')->with('error', 'PDF não gerado!');
        }
    }

    /**
     * Função reutilizável para aplicar filtros à consulta de usuarios
     */
    protected function filterIndex($query, Request $request)
    {
        $query->when(
            $request->filled('name'),
            fn($query) =>
            $query->whereLike('name', '%' . $request->name . '%')
        );
        $query->when(
            $request->filled('email'),
            fn($query) =>
            $query->whereLike('email', '%' . $request->email . '%')
        );

        $query->when(
            $request->filled('start_date_registration'),
            fn($query) =>
            $query->where(
                'created_at',
                '>=',
                Carbon::parse($request->start_date_registration)
            )
        );
        $query->when(
            $request->filled('end_date_registration'),
            fn($query) =>
            $query->where(
                'created_at',
                '<=',
                Carbon::parse($request->end_date_registration)
            )
        );
    }

}
