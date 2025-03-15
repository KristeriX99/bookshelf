@extends('layouts.app')

@section('content')
    <book-form :authors='@json($authors)'></book-form>
@endsection