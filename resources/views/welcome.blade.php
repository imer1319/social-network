@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 bg-light shadow-sm mb-3">
                    <status-form></status-form>
                </div>
                <div>
                    <status-list></status-list>
                </div>
            </div>
        </div>
    </div>
@endsection
