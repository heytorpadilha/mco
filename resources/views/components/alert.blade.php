@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: "Pronto!",
                text: "{{ session('success') }}",
                icon: "success"
            });
        })
    </script>
    {{-- <div class="alert-success">

        {{ session('success') }}
    </div> --}}
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: "Erro!",
                text: "{{ session('error') }}",
                icon: "error"
            });
        })
    </script>

    {{-- <div class="alert-error">

        {{ session('error') }}
    </div> --}}
@endif

@if ($errors->any())
    @php
        $message = '';
        foreach ($errors->all() as $error){
            $message .= $error.'<br>'; 
        }
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: "Erro!",
                html: "{!! $message !!}",
                icon: "error"
            });
        })
    </script>
    {{-- <div class="alert-error">
        @foreach ($errors->all() as $error)
            {{ $error }} <br>
        @endforeach
    </div> --}}

@endif
