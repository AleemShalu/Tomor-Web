<x-authentication-layout>
    <div id="main">
        <div class="fof">
            <h1>Error 404</h1>
            <div class="p-3">
                <div class="mb-6 text-2xl">Hmm...this page doesn’t exist. Try searching for something else!</div>
                <a href="{{ route('dashboard') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">Back To Dashboard</a>
            </div>
        </div>
    </div>

</x-authentication-layout>


<style>
    *{
        transition: all 0.6s;
    }

    html {
        height: 100%;
    }

    body{
        font-family: 'Lato', sans-serif;
        color: #888;
        margin: 0;
    }

    #main{
        display: table;
        width: 100%;
        height: 100vh;
        text-align: center;
    }

    .fof{
        display: table-cell;
        vertical-align: middle;
    }

    .fof h1{
        font-size: 70px;
        display: inline-block;
        padding-right: 12px;
        animation: type .5s alternate infinite;
    }

    @keyframes type{
        from{box-shadow: inset -3px 0px 0px #888;}
        to{box-shadow: inset -3px 0px 0px transparent;}
    }
</style>