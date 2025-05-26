@extends('twill::layouts.form')

@php

@endphp

@section('contentFields')
    @formField('input', [
        'type' => 'textarea',
        'rows' => 6,
        'name' => 'protected',
        'label' => 'Protected',
        'required' => true,
        'disabled' => \A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt::hasDotEnv(),
    ])

    @formField('input', [
        'type' => 'textarea',
        'rows' => 6,
        'name' => 'unprotected',
        'label' => 'Unprotected',
        'required' => true,
        'disabled' => \A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt::hasDotEnv(),
    ])
@stop
