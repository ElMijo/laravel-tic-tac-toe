@extends('layouts.app')
@section('content')
<div id="app">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Sing In</h5>
                    <form method="POST" action="{{ route('login') }}" class="needs-validation {{$errors->isNotEmpty() ? 'was-validated' : ''}}" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input id="email" type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="user@tictactoe.com" required>
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" value=""  class="form-control" placeholder="password" required>
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
