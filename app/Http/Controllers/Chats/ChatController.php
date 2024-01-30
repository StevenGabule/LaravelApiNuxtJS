<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Resources\{ChatResource, MessageResource};
use App\Repositories\Contracts\{IChat, IMessage};
use App\Repositories\Eloquent\Criteria\WithTrashed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
  protected $chats, $messages;

  public function __construct(IChat $chats, IMessage $messages)
  {
    $this->chats = $chats;
    $this->messages = $messages;
  }

  public function sendMessage(Request $request): MessageResource
  {
    $user = null;

    $this->validate($request, [
      'recipient' => ['required'],
      'body' => ['required'],
    ]);

    $recipient = $request->recipient;
    $user = auth()->user();
    $body = $request->body;

    $chat = $user->getChatWithUser($recipient);
    if (!$chat) {
      $chat = $this->chats->create([]);
      $this->chats->createParticipants($chat->id, [$user->id, $recipient]);
    }

    $message = $this->messages->create([
      'user_id' => $user->id,
      'chat_id' => $chat->id,
      'body' => $body,
      'last_read' => null
    ]);
    return new MessageResource($message);
  }

  public function getUserChats(): AnonymousResourceCollection
  {
    $chats = $this->chats->getUserChats(auth()->id());
    return ChatResource::collection($chats);
  }

  public function getChatMessages($id): AnonymousResourceCollection
  {
    $messages = $this->messages->withCriteria([
      new WithTrashed()
    ])->findWhere('chat_id', $id);
    return MessageResource::collection($messages);
  }

  public function markAsRead($id)
  {
    $chat = $this->chats->find($id);
    $chat->markAsReadForUser(auth()->id());
    return response()->json(['message' => 'successful']);
  }

  public function destroyMessage($id)
  {
    $message = $this->messages->find($id);
    $this->authorize('delete', $message);
    $message->delete();
  }
}
