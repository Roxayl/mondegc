<?php

namespace App\Jobs\Discord;

use App\Jobs\Contracts\NotifiesDiscord;
use App\Jobs\Traits\NotifiesDiscord as NotifiesDiscordTrait;
use App\Models\OcgcProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NotifyVotingProposal implements ShouldQueue, NotifiesDiscord
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotifiesDiscordTrait;

    private OcgcProposal $proposal;

    private string $webhookName = 'ocgc';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OcgcProposal $proposal)
    {
        $this->proposal = $proposal;
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
        return [
            'content' => "**VOTE EN COURS !**\r\n"
                . "Une proposition est en cours de vote à l'Assemblée générale !",
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
                        'url' => 'https://romukulot.fr/kaleera/images/7YPwC.png',
                    ],
                    'color' => hexdec('234067'),
                    'footer' => [
                        'text' => "Le Monde GC",
                        'icon_url' => "https://generation-city.com/monde/assets/ico/apple-touch-icon-72-precomposed.png",
                    ],
                    'fields' => [
                        [
                            "name" => "Identifiant",
                            "value" => $this->proposal->fullIdentifier(),
                            "inline" => true,
                        ],
                        [
                            "name" => "Créée par",
                            "value" => $this->proposal->pays ? $this->proposal->pays->ch_pay_nom : "Pays inconnu",
                            "inline" => true,
                        ],
                        [
                            "name" => "Date limite de vote",
                            "value" => $this->proposal->debate_end->format('d/m/Y à H:i'),
                            "inline" => false,
                        ],
                        [
                            "name" => "Modalités",
                            "value" => $this->proposal->responses()->implode(",\r\n"),
                            "inline" => false,
                        ],
                    ]
                ]
            ],
        ];
    }
}
