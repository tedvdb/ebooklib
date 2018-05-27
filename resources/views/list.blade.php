@extends('layouts.app')

@section('content')
    <div class="container list" xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="row clearfix">
            <div class="col-lg-3">
                <shoppingcart-component></shoppingcart-component>

                <a href="{{ route('downloadcart') }}" class="pull-right btn btn-primary">
                    <span class="glyphicon glyphicon-download" aria-hidden="true"></span>
                    Download all
                </a>
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
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                   @foreach ($ebooks as $ebook)
                    <tr>
                        <td>
                            @if($ebook->hasCover())
                                <img src="{{route('thumb',['id'=>$ebook->id])}}">
                            @endif
                        </td>
                        <td>{{$ebook['title']}}</td>
                        <td>{{$ebook['creator']}}</td>
                        <td>{{basename($ebook['path'])}}</td>
                        <td>
                            <a v-on:click="addToCart({{$ebook->id}})" class="addtocart"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a>
                            <a href="{{route('download', ['id' => $ebook->id])}}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $ebooks->appends(['q' => $q])->links() }}
            </div>
        </div>
    </div>
    <script type="text/javascript">
        window.currentShoppingCart = {!! json_encode($cartbooks) !!};
    </script>
    <script src="{{ asset('js/list.js') }}"></script>
@endsection