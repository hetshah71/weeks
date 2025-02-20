<?php
//login the user if credentials matches
use Core\Authenticator;
use Core\Session;
use Http\Forms\LoginForm;

$form = LoginForm::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password']
]);
$signedIn = (new Authenticator)->attempt(
    $attributes['email'],
    $attributes['password']
);
if (!$signedIn) {
    $form->error(
        'email',
        'No matching account found for that email address and password.'
    )->throw();
}
redirect('/');
