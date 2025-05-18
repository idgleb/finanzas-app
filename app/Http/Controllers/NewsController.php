<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get(); // O el nombre real del modelo y orden que quieras
        return view('news.index', compact('news'));
    }

}
