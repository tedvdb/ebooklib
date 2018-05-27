<?php

namespace App\Http\Controllers;

use App\Ebook;
use App\SearchPath;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $data = [
            'searchpaths' => SearchPath::count(),
            'users' => User::count(),
            'ebooks' => Ebook::count(),
            'logs' => [],
        ];

        return view('admin.dashboard', $data);

    }

    public function reindex(Request $r) {
        $count = 0;
        if($r->has("index")) {
            $sp = SearchPath::find($r->get('index'));
            $ap->reindex();
        } else {
            foreach (SearchPath::all() as $index) {
                $index->reindex();
            }
        }
        return redirect("/")->with("successtatus","Indexing complete.");
    }
}
