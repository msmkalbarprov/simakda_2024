@extends('template.app')

@section('content')
<style type="text/css">
    @import url('https: //fonts.googleapis.com/css2?family=Poppins:wght@900&display=swap');
    .ull{
        display: flex;

    }
    .lil{
        list-style: none;
    }
    .divl{
        height: 380px;
        width: 1180px;
        display: flex;
        justify-content: center;
        font-size: 46px;
        cursor: pointer;
        margin: 0 4px;
        border-radius: 20px;
        color: yellow;
        text-shadow: 0 0 15px yellow, 0 0 25px yellow;
        animation: animate 1.5s linear infinite;
    }
    @keyframes animate{
        0%{
            filter: hue-rotate(0deg);
        }
        100%{
            filter: hue-rotate(360deg);
        }
    }
</style>

<ul class="ul">
    <li class="lil center">
        <div class="divl">TIDAK MEMILIKI AKSES</div>
    </li>
</ul>
@endsection
