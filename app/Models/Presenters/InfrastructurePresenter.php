<?php

namespace App\Models\Presenters;

use Illuminate\Support\Str;

trait InfrastructurePresenter
{
    public static function getJudgementTitle($type): string
    {
        switch($type) {
            case 'pending':
                $title = 'Infrastructures en attente de jugement'; break;
            case 'rejected':
                $title = "Infrastructures rejetées"; break;
            case 'accepted':
                $title = "Infrastructures acceptées"; break;
            default:
                $title = "";
        }
        return $title;
    }

    public function getStatusData(): object
    {
        $map = [
            self::JUGEMENT_PENDING => [
                'text' => 'En attente de jugement',
                'color' => 'blue',
            ],
            self::JUGEMENT_ACCEPTED => [
                'text' => 'Acceptée',
                'color' => 'green',
            ],
            self::JUGEMENT_REJECTED => [
                'text' => 'Rejetée',
                'color' => 'red',
            ]
        ];
        return (object)$map[$this->ch_inf_statut];
    }

    public function wellDescription(): string
    {
        $str  = "<em><i class='icon-calendar'></i> {$this->ch_inf_date->diffForHumans()}</em> &#183; ";
        $str .= Str::limit(e($this->ch_inf_commentaire), 150);

        return $str;
    }
}
