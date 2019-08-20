@extends('Layout.pageUser')
@section('content')
    <div id='logo'>
        REJESTRACJA UŻYTKOWNIKA
    </div>
    <form action='{{url('/User/RegisterSubmit')}}' method='post'>
        <table class='table'>
            <tr>
                <td class='user_font'>
                    Nazwa użytkownika
                    
                </td>
                <td>
                    <input type='text' name='login' class='form-control' placeholder="Login" value='{{Input::old('login')}}'>
                </td>
                
            </tr>
            <tr>
                <td class='user_font'>
                    email użytkownika
                    
                </td>
                <td>
                    <input type='text' name='email' class='form-control' placeholder="email" value='{{Input::old('email')}}'>
                </td>
                
            </tr>
            <tr>
                <td class='user_font'>
                    hasło użytkownika
                    
                </td>
                <td>
                    <input type='password' name='password' class='form-control' placeholder="hasło">
                </td>
                
            </tr>
            <tr>
                <td class='user_font'>
                    Wpisz jeszcze raz hasło użytkownika
                    
                </td>
                <td>
                    <input type='password' name='password_confirm' class='form-control' placeholder="hasło">
                </td>
                
            </tr>
            <tr>
                <td class='user_font'>
                    Początek dnia
                    
                </td>
                <td>
                    <input type='number' name='start_day' class='form-control' value='0'>
                </td>
                
            </tr>
            <tr>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <td colspan='2' class='user_font'>
                    <input type='submit'value='Zarejestrój' class='btn btn-success'>
                </td>
                
            </tr>
        </table>
        
    </form>    
    @if (session('error'))
        <div class='error'>
        @foreach (Session('error') as $errors)
            {{$errors}}<br>
        @endforeach
        </div>
    @endif
@endsection
@section('title')
    Rejestracja użytkownika
@endsection