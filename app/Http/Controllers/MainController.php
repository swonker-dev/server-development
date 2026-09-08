<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $news = $this->getNews();

        return view('home', ['news' => $news]);
    }

    public function galery(int $id): View
    {
        $item = collect($this->getNews())->firstWhere('id', $id);

        abort_if($item === null, 404);

        return view('galery', ['item' => $item]);
    }

    private function getNews(): array
    {
        $path = public_path('data/news.json');

        if (!File::exists($path)) {
            return [];
        }

        $data = json_decode(File::get($path), true);

        return is_array($data) ? $data : [];
    }
}
