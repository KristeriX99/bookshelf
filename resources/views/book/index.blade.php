@extends('layouts.app')

@section('content')
    <book-list :books='@json($books)'></book-list>
@endsection