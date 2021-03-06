<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CommentsController extends Controller
{
   public function postComment(Request $request, AppMailer $mailer)
    {
        var_dump($request->input('ticket_id'));
        $this->validate($request, [
            'comment'   => 'required'
        ]);
            var_dump($request->input('ticket_id'));
            $comment = Comment::create([
            'ticket_id' => $request->input('ticket_id'),
            'user_id'    => Auth::user()->id,
            'comment'    => $request->input('comment'),
            ]);

            // send mail if the user commenting is not the ticket owner
            if ($comment->ticket->user->id !== Auth::user()->id) {
            $mailer->sendTicketComments($comment->ticket->user, Auth::user(), $comment->ticket, $comment);
        }

        return redirect()->back()->with("status", "Your comment has be submitted.");
    }
}