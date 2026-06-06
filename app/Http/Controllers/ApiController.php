<?php

namespace App\Http\Controllers;

use App\Models\MessageQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', description: 'API документация', title: 'Laravel API')]
#[OA\Server(url: '/', description: 'Local server')]
class ApiController extends Controller
{
    #[OA\Post(
        path: '/api/message',
        description: 'Принимает channel и body, записывает в таблицу messages_queue',
        summary: 'Сохранить сообщение в очередь',
        requestBody: new OA\RequestBody(
            description: 'Данные сообщения',
            required: true,
            content: new OA\JsonContent(
                required: ['channel', 'body'],
                properties: [
                    new OA\Property(property: 'channel', type: 'string', example: 'email'),
                    new OA\Property(property: 'body',    type: 'string', example: 'Hello World'),
                ]
            )
        ),
        tags: ['Message'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Сообщение сохранено',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id',         type: 'integer', example: 1),
                        new OA\Property(property: 'channel',    type: 'string',  example: 'email'),
                        new OA\Property(property: 'body',       type: 'string',  example: 'Hello World'),
                        new OA\Property(property: 'created_at', type: 'string',  format: 'date-time', example: '2026-06-06 12:00:00'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The channel field is required.'),
                        new OA\Property(property: 'errors',  type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'channel' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $record = MessageQueue::create($validated);

        $record->refresh();

        return response()->json([
            'id'         => $record->id,
            'channel'    => $record->channel,
            'body'       => $record->body,
            'created_at' => $record->created_at,
        ], 201);
    }
}
