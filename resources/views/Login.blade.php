<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @component('Style')        
    @endcomponent
</head>
<body style="padding:10px 10% 0 10%;">
    <form action="{{ route('SubmitLogin') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>
        <div class="form-group">
            <label for="pw">Password</label>
            <input type="text" class="form-control" id="pw" name="pw">
        </div>

        <button class="btn btn-success">Login</button>
    </form>
</body>
</html>