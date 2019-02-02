@extends('Layout.pageUser')
@section('content')
    <div id='logo'>
        LOGOWANIE UŻYTKOWNIKA
    </div>
    <form action='{{url('/User/Login_action')}}' method='post'>
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
                    hasło użytkownika
                    
                </td>
                <td>
                    <input type='password' name='password' class='form-control' placeholder="hasło">
                </td>
                
            </tr>

            <tr>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <td colspan='2' class='user_font'>
                    <input type='submit'value='Zaloguj' class='btn btn-success'>
                </td>
                
            </tr>
        </table>
        
    </form>    
    @if (session('error'))
        <div class='error'>

            {{session('error')}}<br>

        </div>
    @endif
@endsection
@section('title')
    Logowanie użytkownika
@endsection