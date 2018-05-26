@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row clearfix">
            <h1>Dashboard</h1>
            <div class="col-lg-12">

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-lg-3">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true">
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">{{$searchpaths}}</div>
                                        <div>Ebook locations</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="glyphicon glyphicon-book" aria-hidden="true">
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">{{$ebooks}}</div>
                                        <div>Ebooks</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="glyphicon glyphicon-user" aria-hidden="true">
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">{{$users}}</div>
                                        <div>Users</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true">
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">{{count($logs)}}</div>
                                        <div>Errors</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
