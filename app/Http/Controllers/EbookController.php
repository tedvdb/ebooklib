<?php

namespace App\Http\Controllers;

use App\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipStream\ZipStream;

class EbookController extends Controller
{
    public static $BOOKS_PER_PAGE = 25;

    public function list(Request $r) {
        $q = "";
        $ebooks = false;
        if($r->has("q")) {
            $q = $r->get("q");
            $ebooks = Ebook::search($q)->paginate(self::$BOOKS_PER_PAGE);
        } else {
            $ebooks = Ebook::paginate(self::$BOOKS_PER_PAGE);
        }

        $currentcartbooks = $this->getCartContent($r);

        $data = [
            'ebooks'=>$ebooks,
            'q'=>$q,
            'cartbooks'=>$currentcartbooks
        ];

        return view('list', $data);
    }

    public function download($id) {
        $book = Ebook::findOrFail($id);
        return response()->download($book->path);
    }

    public function addToCart(Request $request, $bookid) {
        if(!$request->session()->has('cart')) $request->session()->put('cart',[]);


        $ebook = Ebook::select('id','creator','title')->where(['id'=>$bookid])->first();
        if(!$ebook) die();
        $cart = $request->session()->get('cart');
        if(!in_array($bookid,$cart)) {
            $request->session()->push('cart',$bookid);
            return $ebook->toArray();
        }
        return;
    }

    public function removeFromCart(Request $request, $bookid) {
        if(!$request->session()->has('cart')) $request->session()->put('cart',[]);

        $cart = $request->session()->get('cart');
        foreach($cart as $key => $book) {
            if($book==$bookid) {
                unset($cart[$key]);
                break;
            }
        }
        $request->session()->put('cart',$cart);
        return;
    }

    private function getCartContent(Request $r) {
        if(!$r->session()->has('cart')) $r->session()->put('cart',[]);
        $currentcartbooks = [];
        $booksids = session('cart');
        foreach($booksids as $booksid) {
            $book = Ebook::select('id','creator','title')->where(['id'=>$booksid])->first();
            if($book) {
                $currentcartbooks[] = $book->toArray();
            }
        }
        return $currentcartbooks;
    }

    public function downloadCart(Request $request) {
        if(!$request->session()->has('cart') || count($request->session()->get('cart')) == 0) {
            return redirect('home')->with('successtatus','No books in cart.');
        }

        $zip = new ZipStream('ebooks'.time().'.zip');
        $cart = $request->session()->get('cart');
        foreach($cart as $key => $bookid) {
            $ebook = Ebook::find($bookid);
            $zip->addFileFromPath(basename($ebook->path), $ebook->path);
        }
        $zip->finish();
    }

    public function downloadCover($bookid) {
        if(Storage::exists('covers/'.$bookid)) {
            return Storage::download('covers/'.$bookid);
        }
        abort(404);
    }

    public function downloadCoverThumb($bookid) {
        if (Storage::exists('thumbcovers/' . $bookid)) {
            return Storage::download('thumbcovers/' . $bookid);
        }
        abort(404);
    }
}
