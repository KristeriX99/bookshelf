@extends('layouts.app')

@section('content')
    <book-list 
        :books='@json($books)' 
        :search='@json($search)'
    ></book-list>
@endsection