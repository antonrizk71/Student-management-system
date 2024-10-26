<!DOCTYPE html>
<html>

<head>
    <title>Hello Mail</title>
</head>
<style>
    .container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 5em;
    }
</style>

<body>
<div class="container">
    <div>
        <h1>Hello {{$name}}!</h1>
        <p>Welcome To Benha University</p>
        <p>Thank you for joining us!</p>
        <span>Regards,</span>
        <h4>{{ config('app.name') }}</h4>
    </div>

    <img src="{{ asset('assets/Benha_University_Logo.png') }}" alt="Benha University Logo">
</div>

</body>

</html>
