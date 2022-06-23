<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use App\Models\Journal;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function index()
    {
        return ["data" => Authors::all()];
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:1',
            'last_name' => 'required|string|min:3'
        ]);

        $author = new Authors([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name ?: ''
        ]);
        $author->save();
        return ["data" => $author];
    }

    public function show(Authors $author)
    {
        return ["data" => $author];
    }

    public function update(Request $request, Authors $author)
    {
        $request->validate([
            'first_name' => 'required|string|min:1',
            'last_name' => 'required|string|min:3'
        ]);

        $author->first_name =  $request->first_name;
        $author->last_name = $request->last_name;
        $author->middle_name = $request->middle_name;

        $author->save();

        return ["data" => $author];

    }

    public function destroy(Authors $author)
    {
        if($author->delete()) return response(null, 204);
    }


    public function getJournals(Authors $author) {
        $journals = Journal::where('authors', 'LIKE', '%' . $author->id . '%')->get();
        return ["data" => $journals];
    }
}
