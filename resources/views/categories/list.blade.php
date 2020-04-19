@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Existing Categories</div>

                <div class="card-body">
                    @include('alerts.status')

                    @if ( count($existingCats) > 0 )                    
                        <table class="table">
                            <thead class="thead-dark">
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Cards</th> 
                                <th scope="col">Created</th>  
                                <th scope="col">Actions</th>                                                                                    
                            </thead>

                            <tbody>
                                @foreach( $existingCats as $existingCat )
                                    <tr>
                                        <td>{{ $existingCat->id }}</td>
                                        <td>{{ $existingCat->name }}</td>
                                        <td>{{ count($existingCat->flashCards) }}</td>
                                        <td>{{ $existingCat->created_at }}</td>     
                                        <td>
                                            <a href="/card/category/{{ $existingCat->id }}">
                                                <button type="button" class="btn btn-info">View Cards</button>
                                            </a>

                                            <a href="/card/categories/delete/{{ $existingCat->id }}">
                                                <button type="button" class="btn btn-danger">Delete</button>
                                            </a>
                                        </td>                                                                                           
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else 
                        <p>There are no stored card categories to display.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection