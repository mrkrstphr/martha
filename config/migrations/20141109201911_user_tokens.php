<?php

use Phinx\Migration\AbstractMigration;

class UserTokens extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $tokens = $this->table('user_tokens')
            ->addColumn('user_id', 'integer')
            ->addColumn('auth_service', 'string', ['limit' => 100])
            ->addColumn('access_token', 'string')
            ->addForeignKey('user_id', 'users', 'id');
        $tokens->create();

        $this->execute(
            'INSERT INTO user_tokens(user_id, auth_service, access_token) ' .
            'SELECT id, auth_service, access_token FROM users ' .
            'WHERE auth_service IS NOT NULL and access_token IS NOT NULL ' .
            'ORDER BY id'
        );

        $this->table('users')
            ->removeColumn('auth_service')
            ->removeColumn('access_token')
            ->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('users')
            ->addColumn('auth_service', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('access_token', 'string', ['null' => true])
            ->update();

        $this->execute(
            'UPDATE users SET (auth_service, access_token) = ' .
            '(SELECT auth_service, access_token FROM user_tokens ' .
            'WHERE user_id = users.id LIMIT 1)'
        );

        $this->dropTable('user_tokens');
    }
}
