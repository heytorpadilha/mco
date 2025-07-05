@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="content-title">
            <h1 class="page-title">Alterar senha do usuário</h1>
            <a href="{{ route('user.index') }}" class="btn-info">Listar</a>
        </div>
        <x-alert />
        <form action="{{ route('user.updatePassword' , ['user' => $user->id]) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="form-label">Nova senha: </label>
                <input type="text" name="password" id="password" class="form-input"
                    placeholder="Senha com no minimo 6 caracteres" value="{{ old('password') }}" >
            </div>
            <button type="submit" class="btn-warning">Salvar</button>

        </form>
    </div>
@endsection