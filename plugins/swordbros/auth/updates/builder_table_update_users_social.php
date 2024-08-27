<?php namespace Capsule\Auth\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateUsersSocialProviders extends Migration
{
    const TABLE_NAME = 'users';
    const GOOGLE_FIELD_NAME = 'google_id';
    const FACEBOOK_FIELD_NAME = 'facebook_id';

    public function up()
    {
        if (!Schema::hasColumn(self::TABLE_NAME, self::GOOGLE_FIELD_NAME)) {
            Schema::table(self::TABLE_NAME, function($table)
            {
                $table->string(self::GOOGLE_FIELD_NAME, 191)->nullable();
                $table->string(self::FACEBOOK_FIELD_NAME, 191)->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn(self::TABLE_NAME, self::GOOGLE_FIELD_NAME)) {
            Schema::table(self::TABLE_NAME, function($table)
            {
                $table->dropColumn(self::GOOGLE_FIELD_NAME);
                $table->dropColumn(self::FACEBOOK_FIELD_NAME);
            });
        }
    }
}
