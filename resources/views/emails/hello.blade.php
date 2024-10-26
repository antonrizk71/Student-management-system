<!DOCTYPE html>
<html>

<head>
    <title>Hello Mail</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px;">
<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5em;">
    <div style="max-width: 50%;">
        <h1>Hello {{$name}}!</h1>
        <p>Welcome to Benha University</p>
        <p>Thank you for joining us!</p>
        <span>Regards,</span>
        <h4>{{ config('app.name') }}</h4>
    </div>
    <div style="max-width: 30%;">
        <img src="{{ $message->embed(public_path().'/assets/Benha_University_Logo.png') }}" alt="Benha University Logo" style="max-width: 100%; height: auto;">
    </div>
</div>
</body>

</html>
