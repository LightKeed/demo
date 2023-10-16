<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Comment\CommentUpdateRequest;
use App\Models\Comment;

final class CommentService extends BaseService
{
    public function index()
    {
        $comments = Comment::with(['element', 'parent'])
            ->filter(Request::only('name', 'phone', 'email', 'date', 'status', 'archive'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Comment/Index', [
            'filters' => Request::all('name', 'phone', 'email', 'date', 'status', 'archive'),
            'comments' => $comments
        ]);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Comment/Edit', [
            'comment' => $this->getCommentByID($id)
        ]);
    }

    public function update(CommentUpdateRequest $request, int $id)
    {
        $comment = $this->getCommentByID($id);

        $comment->update($request->validated());

        $this->log->add('update', 'comment', $comment->id, "Name: $comment->name | Comment: $comment->text");

        return redirect()->back()->with([
            'success' => 'Комментарий успешно обновлен.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $comment = $this->getCommentByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Комментарий не найден.']);
        }

        $comment->restore();

        $this->log->add('restore', 'comment', $comment->id, "Name: $comment->name | Comment: $comment->text");

        return redirect()->back()->with([
            'success' => 'Комментарий успешно восстановлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $comment = $this->getCommentByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Комментарий не найден.']);
        }

        $trashed = $comment->trashed();
        $trashed ? $comment->forceDelete() : $comment->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'comment', "Name: $comment->name | Comment: $comment->text");

        if($trashed) {
            return to_route('Admin.CommentIndex')->with([
                'success' => 'Комментарий успешно удален.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Комментарий успешно удален.'
            ]);
        }
    }

    private function getCommentByID(int $id)
    {
        return Comment::withTrashed()
            ->with(['element', 'parent'])
            ->where('id', '=', $id)
            ->firstOrFail();
    }
}
