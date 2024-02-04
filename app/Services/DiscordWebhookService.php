<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Services;

class DiscordWebhookService
{
    /**
     * @var string URL du webhook Discord.
     */
    private string $webhookUrl;

    /**
     * @param string $webhookUrl
     */
    public function __construct(string $webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Envoie un message à travers le webhook.
     *
     * @param array $payload
     */
    public function sendMessage(array $payload): void
    {
        $webhookurl = $this->webhookUrl;

        $payload = array_merge($this->defaultPayload(), $payload);

        $jsonData = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init($webhookurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch); // TODO: gérer la réponse et les erreurs éventuelles.
        curl_close($ch);
    }

    /**
     * @return array
     */
    public function defaultPayload(): array
    {
        return [
            'username' => config('discord.webhookName'),
            'tts' => false,
        ];
    }

    /**
     * Génère l'ensemble des paramètres qui peuvent être passés au webhook à titre d'exemple.
     *
     * @return array<string, string|array>
     */
    public function getExamplePayload(): array
    {
        $timestamp = date("c", strtotime("now"));

        return [
            // Message (formatable as Markdown : https://discordapp.com/developers/docs/reference#message-formatting)
            "content" => "Hello World!",

            // Username
            "username" => "Le Monde GC",

            // Avatar URL.
            // "avatar_url" => "",

            // Text-to-speech
            "tts" => false,

            // File upload
            // "file" => "",

            // Embeds Array
            "embeds" => [
                [
                    // Embed Title
                    "title" => "PHP - Send message to Discord (embeds) via Webhook",

                    // Embed Type
                    "type" => "rich",

                    // Embed Description
                    "description" => "Hello world!",

                    // URL of title link
                    "url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",

                    // Timestamp of embed must be formatted as ISO8601
                    "timestamp" => $timestamp,

                    // Embed left border color in HEX
                    "color" => hexdec("3366ff"),

                    // Footer
                    "footer" => [
                        "text" => "GitHub.com/Mo45",
                        "icon_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=375"
                    ],

                    // Image to send
                    "image" => [
                        "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=600"
                    ],

                    // Thumbnail
                    "thumbnail" => [
                        "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=400",
                    ],

                    // Author
                    "author" => [
                        "name" => "krasin.space",
                        "url" => "https://krasin.space/"
                    ],

                    // Additional Fields array
                    "fields" => [
                        // Field 1
                        [
                            "name" => "Field #1 Name",
                            "value" => "Field #1 Value",
                            "inline" => false
                        ],
                        // Field 2
                        [
                            "name" => "Field #2 Name",
                            "value" => "Field #2 Value",
                            "inline" => true
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }
}
