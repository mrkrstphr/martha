<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class UserEmails
 */
class UserEmails extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $emails = $this->table('user_emails')
            ->addColumn('email', 'string')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id');
        $emails->create();

        $this->execute(
            'INSERT INTO user_emails(user_id, email) SELECT id, email FROM users ORDER BY id'
        );

        $this->table('users')
            ->removeColumn('email')
            ->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('users')
            ->addColumn('email', 'string', ['null' => true])
            ->update();

        $this->execute(
            'UPDATE users SET email = (SELECT email FROM user_emails WHERE user_id = users.id LIMIT 1)'
        );

        $this->table('users')
            ->changeColumn('email', 'string', ['null' => false]);

        $this->dropTable('user_emails');
    }
}
