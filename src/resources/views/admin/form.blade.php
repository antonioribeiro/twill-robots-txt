@extends('twill::layouts.form')

@php
    use A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt;
@endphp

@section('contentFields')
    @formField('input', [
    'type' => TwillRobotsTxt::config('inputs.username.type'),
    'name' => 'username',
    'label' => 'Username',
    'required' => true,
    'disabled' => TwillRobotsTxt::hasDotEnv(),
    ])

    @formField('input', [
    'type' => TwillRobotsTxt::config('inputs.password.type'),
    'name' => 'password',
    'label' => 'Password',
    'required' => true,
    'disabled' => TwillRobotsTxt::hasDotEnv(),
    'password' => true,
    ])

    @formField('checkbox', [
    'name' => 'allow_laravel_login',

    'label' => 'Allow Laravel users to login',

    'disabled' => TwillRobotsTxt::hasDotEnv(),
    ])

    @formField('checkbox', [
    'name' => 'allow_twill_login',

    'label' => "Allow Twill users to login",

    'disabled' => TwillRobotsTxt::hasDotEnv(),
    ])
@stop
