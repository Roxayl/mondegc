<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Jobs\Discord;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Jobs\Contracts\NotifiesDiscord;
use Roxayl\MondeGC\Jobs\Traits\NotifiesDiscord as NotifiesDiscordTrait;
use Roxayl\MondeGC\Models\OcgcProposal;

class NotifyVotingProposal implements ShouldQueue, NotifiesDiscord
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotifiesDiscordTrait;

    private string $webhookName = 'ocgc';

    public function __construct(private readonly OcgcProposal $proposal)
    {
    }

    public function isUnique(): bool
    {
        return true;
    }

    public function getModelIdentifier(): Model
    {
        return $this->proposal;
    }

    public function generatePayload(): array
    {
        $content = "**VOTE EN COURS !**\r\n"
            . "Une proposition est en cours de vote à l'Assemblée générale !";

        if (! empty($roleId = config('discord.role.players'))) {
            $content .= "\r\n<@&$roleId>";
        }

        return [
            'content' => $content,
            'embeds' => [
                [
                    'title' => trim(Str::limit($this->proposal->question, 120)),
                    'type' => 'rich',
                    'url' => url('back/ocgc_proposal.php?id=' . $this->proposal->id),
                    'author' => [
                        'name' => "Assemblée générale de l'OCGC",
                        'url' => url('assemblee.php'),
                    ],
                    'timestamp' => $this->proposal->created->format('c'),
                    'thumbnail' => [
                        'url' => 'https://roxayl.fr/kaleera/images/7YPwC.png',
                    ],
                    'color' => hexdec('234067'),
                    'footer' => [
                        'text' => 'Le Monde GC',
                        'icon_url' => 'https://generation-city.com/monde/assets/ico/apple-touch-icon-72-precomposed.png',
                    ],
                    'fields' => [
                        [
                            'name' => 'Identifiant',
                            'value' => $this->proposal->fullIdentifier(),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Créée par',
                            'value' => $this->proposal->pays ? $this->proposal->pays->ch_pay_nom : 'Pays inconnu',
                            'inline' => true,
                        ],
                        [
                            'name' => 'Date limite de vote',
                            'value' => $this->proposal->debate_end->format('d/m/Y à H:i'),
                            'inline' => false,
                        ],
                        [
                            'name' => 'Modalités',
                            'value' => $this->proposal->responses()->implode(",\r\n"),
                            'inline' => false,
                        ],
                    ],
                ],
            ],
        ];
    }
}
