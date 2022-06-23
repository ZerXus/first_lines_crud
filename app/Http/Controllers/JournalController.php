<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        return ["data" => Journal::all()->order_by('release_date', 'desc')];
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:1',
            'authors' => 'required|string',
            'release_date' => 'required|string|min:3'
        ]);

        $authors = explode(',', $request->authors);
        foreach ($authors as $id) {
            abort_if(!Authors::find($id), 400, 'Authors must exist!');
        }
        $authors = implode(',', $authors);

        $journal = new Journal([
            'title' => $request->title,
            'authors' => $authors,
            'release_date' => $request->release_date,
            'short_description' => $request->short_description ?: ''
        ]);
        $journal->save();
        return ["data" => $journal];
    }

    public function show(Journal $journal)
    {
        return ["data" => $journal];
    }

    public function update(Request $request, Journal $journal)
    {
        $request->validate([
            'title' => 'required|string|min:1',
            'authors' => 'required|string',
            'release_date' => 'required|string|min:3'
        ]);

        $journal->title = $request->title;
        $journal->authors = $request->authors;
        $journal->release_date = $request->release_date;

        $journal->save();

        return ["data" => $journal];
    }

    public function destroy(Journal $journal)
    {
        if ($journal->delete()) {
            return response(null, 204);
        }
    }
}
