<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use App\Services\Operations;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Laravel\Prompts\Note;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class MainController extends Controller
{
    public function index()
    {

        $id = session('user.id');
        $notes = User::find($id)
            ->notes()
            ->whereNull('deleted_at')
            ->get()
            ->toArray();

        return view('home', compact('notes'));
    }

    public function newNote()
    {
        return view('new_note');
    }

    public function  newNoteSubmit(Request $request)
    {
        $request->validate([
            'text_title' => 'required|min:3|max:200',
            'text_note' => 'required|min:3|max:3000'
        ], [
            'text_title.required' => 'O titulo é obrigatório',
            'text_title.min' => 'A titulo deve ter pelo menos :min caracteres',
            'text_title.max' => 'A titulo deve ter pelo menos :max caracteres',
            'text_note.required' => 'O note é obrigatório',
            'text_note.min' => 'A note deve ter pelo menos :min caracteres',
            'text_note.max' => 'A note deve ter pelo menos :max caracteres',
        ]);

        $id = session('user.id');

        $note = new Notes();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function editNote($id)
    {
        $id = Crypt::decrypt($id);
        $note = Notes::find($id);
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(Request $request)
    {
        $request->validate([
            'text_title' => 'required|min:3|max:200',
            'text_note' => 'required|min:3|max:3000'
        ], [
            'text_title.required' => 'O titulo é obrigatório',
            'text_title.min' => 'A titulo deve ter pelo menos :min caracteres',
            'text_title.max' => 'A titulo deve ter pelo menos :max caracteres',
            'text_note.required' => 'O note é obrigatório',
            'text_note.min' => 'A note deve ter pelo menos :min caracteres',
            'text_note.max' => 'A note deve ter pelo menos :max caracteres',
        ]);

        if ($request->note_id == null) {
            return redirect()->to('home');
        }

        $id = Operations::decryptId($request->note_id);
        $note = Notes::find($id);
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);
        $note = Notes::find($id);
        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirm($id) {
        $id = Operations::decryptId($id);
        $note = Notes::find($id);
        //$note->delete();
        //$note->deleted_at = date('Y-m-d H:i:s');
        //$note->save();
        $note->delete();
        return redirect()->route('home');
    }
}
