<?php namespace Lio\Forum\Replies;

use Lio\Comments\CommentRepository;
use Lio\Forum\SectionCountManager;

/**
* This class can call the following methods on the observer object:
*
* replyDeleted($thread)
*/
class ReplyDeleter
{
    protected $comments;
    protected $countManager;

    public function __construct(CommentRepository $comments, SectionCountManager $countManager)
    {
        $this->comments = $comments;
        $this->countManager = $countManager;
    }

    public function delete(ReplyDeleterListener $observer, $reply)
    {
        $thread = $reply->thread;
        $reply->delete();

        $thread->updateReplyCount();
        $this->countManager->cacheSections();

        return $observer->replyDeleted($thread);
    }
}