@extends('layouts.app')

@section('content')
    <div class="container list">
        <div class="row clearfix">
            <div class="col-lg-3">
                <shoppingcart-component :cart-data="{{ json_encode($cartbooks) }}"></shoppingcart-component>
            </div>
            <div class="col-lg-9">
                <form method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for..." name="q" value="{{$q}}">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                    </div><!-- /input-group -->
                </form>
                {{ $ebooks->appends(['q' => $q])->links() }}
                <table class="table table-striped table-hover ">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Title</th>
                        <th>Creator</th>
                        <th>Filename</th>
                    </tr>
                    </thead>
                    <tbody>
                   @foreach ($ebooks as $ebook)
                    <tr>
                        <td>
                            <a v-on:click="addToCart({{$ebook->id}})" :bookid="{{$ebook->id}}" class="addtocart"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a>
                            <a href="{{route('download', ['id' => $ebook->id])}}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                        </td>
                        <td>{{$ebook['title']}}</td>
                        <td>{{$ebook['creator']}}</td>
                        <td>{{basename($ebook['path'])}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $ebooks->appends(['q' => $q])->links() }}
            </div>
        </div>
    </div>
    <script src="{{ asset('js/list.js') }}"></script>
@endsection