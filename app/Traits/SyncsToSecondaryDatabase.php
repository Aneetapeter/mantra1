<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait SyncsToSecondaryDatabase
{
    /**
     * Boot the trait to listen for Eloquent model events.
     */
    public static function bootSyncsToSecondaryDatabase()
    {
        // Listen to when a model is saved (created or updated)
        static::saved(function ($model) {
            $table = $model->getTable();
            $attributes = $model->getAttributes();
            $keyName = $model->getKeyName();
            $keyValue = $model->getKey();

            // Check if record exists in PostgreSQL connection
            $exists = DB::connection('pgsql')
                ->table($table)
                ->where($keyName, $keyValue)
                ->exists();

            if ($exists) {
                // Update
                DB::connection('pgsql')
                    ->table($table)
                    ->where($keyName, $keyValue)
                    ->update($attributes);
            } else {
                // Insert
                DB::connection('pgsql')
                    ->table($table)
                    ->insert($attributes);
            }
        });

        // Listen to when a model is deleted
        static::deleted(function ($model) {
            $table = $model->getTable();
            $keyName = $model->getKeyName();
            $keyValue = $model->getKey();

            DB::connection('pgsql')
                ->table($table)
                ->where($keyName, $keyValue)
                ->delete();
        });
    }
}
