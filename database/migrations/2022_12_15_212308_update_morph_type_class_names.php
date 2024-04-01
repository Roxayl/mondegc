<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateMorphTypeClassNames extends Migration
{
    /**
     * Définit les tables et colonnes associées qui contiennent un nom de classe d'une relation
     * polymorphique à modifier.
     *
     * @var array|string[][]
     */
    private array $fields = [
        'chapter_entries' => ['roleplayable_type'],
        'chapter_resourceable' => ['resourceable_type'],
        'discord_notifications' => ['type'],
        'influence' => ['influencable_type'],
        'infrastructures' => ['infrastructurable_type'],
        'notifications' => ['type'],
        'resource_history' => ['resourceable_type'],
        'roleplay_organizers' => ['organizer_type'],
        'versions' => ['versionable_type'],
    ];

    /**
     * @var string
     */
    private string $oldPrefix = 'App\\';

    /**
     * @var string
     */
    private string $newPrefix = 'Roxayl\MondeGC\\';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $this->runClassNameReplacements($this->oldPrefix, $this->newPrefix);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            $this->runClassNameReplacements($this->newPrefix, $this->oldPrefix);
        });
    }

    /**
     * @param  string  $oldPrefix
     * @param  string  $newPrefix
     */
    private function runClassNameReplacements(string $oldPrefix, string $newPrefix): void
    {
        foreach ($this->fields as $table => $fields) {
            foreach ($fields as $field) {
                $classNames = DB::query()
                    ->select($field)->distinct()
                    ->from($table)
                    ->get()
                    ->pluck($field)
                    ->toArray();

                foreach ($classNames as $className) {
                    if (empty($className)) {
                        continue;
                    }
                    $updatedClassName = str_replace($oldPrefix, $newPrefix, $className);
                    $query = "UPDATE `$table` SET $field = :newValue WHERE $field = :oldValue";
                    $query = str_replace(':newValue', DB::getPdo()->quote($updatedClassName), $query);
                    $query = str_replace(':oldValue', DB::getPdo()->quote($className), $query);
                    DB::unprepared($query);
                }
            }
        }
    }
}
