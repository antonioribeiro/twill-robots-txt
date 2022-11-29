@extends('twill::layouts.form')

@php
    use A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt;
@endphp

@section('contentFields')
    @formField('input', [
        'type' => 'textarea',
        'rows' => 6,
        'name' => 'protected',
        'label' => 'Protected',
        'required' => true,
        'disabled' => TwillRobotsTxt::hasDotEnv(),
    ])

    @formField('input', [
        'type' => 'textarea',
        'rows' => 6,
        'name' => 'unprotected',
        'label' => 'Unprotected',
        'required' => true,
        'disabled' => TwillRobotsTxt::hasDotEnv(),
    ])
@stop
