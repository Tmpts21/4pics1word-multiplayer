@extends('layouts.app')

@section('content')
    {{-- 
        bg-colors
        style = 'background-color:#243447'> 
        style = 'background-color:#141d26'
    --}}

    <div class="row justify-content-center">
        <div class="col-md-12">
                   <div id="app">

                        <div class="row">
                           
                            <game-component  :user = '{{json_encode(Auth::User())}}'></game-component >
                            <chat-component class = 'chats' :user = '{{ json_encode(Auth::User())}}'></chat-component>

                        </div>
                       <div>
                       </div>
                       

                   </div>
        </div>
    </div>

@endsection
