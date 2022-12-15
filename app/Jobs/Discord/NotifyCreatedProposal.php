<?php

namespace Roxayl\MondeGC\Jobs\Discord;

use Roxayl\MondeGC\Jobs\Contracts\NotifiesDiscord;
use Roxayl\MondeGC\Jobs\Traits\NotifiesDiscord as NotifiesDiscordTrait;
use Roxayl\MondeGC\Models\OcgcProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NotifyCreatedProposal implements ShouldQueue, NotifiesDiscord
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
            'content' => "**NOUVELLE PROPOSITION !**\r\n"
                . "Une nouvelle proposition a été créée à l'Assemblée générale et a été acceptée par l'OCGC. "
                . "Vous êtes invité à en débattre sur le forum.",
            'embeds' => [
                [
                    'title' => trim(Str::limit($this->proposal->question, 120)),
                    'type' => 'rich',
                    'url' => url('back/ocgc_proposal.php?id=' . $this->proposal->id),
                    'author' => [
                        'name' => "Assemblée générale de l'OCGC",
                        'url' => url('assemblee.php'),
                    ],
                    'timestamp' => date("c", strtotime("now")),
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
                            "name" => "Période de vote",
                            "value" => "Du " . $this->proposal->debate_start->format('d/m/Y') . " au "
                                . $this->proposal->debate_end->format('d/m/Y'),
                            "inline" => false,
                        ],
                    ]
                ]
            ],
        ];
    }
}
