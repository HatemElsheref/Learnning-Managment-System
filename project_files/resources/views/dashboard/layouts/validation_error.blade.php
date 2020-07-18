@if($errors->any())
    <div class="alert alert-danger alert-highlighted" role="alert">
        @foreach($errors->all() as $error)
            <span><i class="mdi mdi-alert-circle-outline"></i> {{$error}}</span>  <br>
        @endforeach
    </div><br>
@endif

