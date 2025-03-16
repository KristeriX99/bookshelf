@extends('layouts.app')

@section('content')
    <book-form 
        :authors='@json($authors)' 
        :book='@json($book ?? null)'
    ></book-form>
@endsection