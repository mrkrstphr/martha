<?php

use Phinx\Db\Table;
use Phinx\Migration\AbstractMigration;

/**
 * Class BaseSchema
 */
class BaseSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change()
    {
        $users = $this->createUsersTable();

        $projects = $this->createProjectsTable($users);
        $builds = $this->createBuildsTable($projects);
        $plugins = $this->createPluginsTable();

        $this->createStepsTable($builds);
        $this->createArtifactsTable($builds);
        $this->createAlertsTable($builds, $plugins);
        $this->createBuildExceptionsTable($builds, $plugins);
        $this->createBuildStatisticsTable($builds, $plugins);

        $this->createLogsTable();
    }

    /**
     * @param Table $users
     * @return Table
     */
    protected function createProjectsTable(Table $users)
    {
        $projects = $this->table('projects')
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('scm', 'string', ['limit' => 50])
            ->addColumn('uri', 'string')
            ->addColumn('is_private', 'boolean', ['default' => false])
            ->addColumn('created', 'timestamp')
            ->addColumn('created_by_id', 'integer')
            ->addForeignKey('created_by_id', $users, 'id');
        $projects->create();

        return $projects;
    }

    /**
     * @return Table
     */
    protected function createPluginsTable()
    {
        $plugins = $this->table('plugins');
        $plugins->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('key', 'string', ['limit' => 100])
            ->addColumn('author', 'string', ['limit' => 100])
            ->addColumn('version', 'string', ['limit' => 20])
            ->addColumn('enabled', 'boolean', ['null' => true])
            ->addColumn('last_enabled', 'timestamp', ['null' => true])
            ->addColumn('updateable_version', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('updateable_version_notes', 'text', ['null' => true])
            ->addColumn('created', 'timestamp')
            ->create();

        return $plugins;
    }

    /**
     * @param Table $projects
     * @return Table
     */
    public function createBuildsTable(Table $projects)
    {
        $builds = $this->table('builds');
        $builds->addColumn('project_id', 'integer')
            ->addColumn('parent_id', 'integer', ['null' => true])
            ->addColumn('method', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('revision_number', 'string', ['null' => true])
            ->addColumn('branch', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('fork', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('fork_uri', 'string', ['null' => true])
            ->addColumn('message', 'text', ['null' => true])
            ->addColumn('author', 'string', ['null' => true])
            ->addColumn('metadata', 'text', ['null' => true])
            ->addColumn('status', 'string', ['limit' => 40])
            ->addColumn('created', 'timestamp')
            ->addForeignKey('project_id', $projects, 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('parent_id', $builds, 'id');
        $builds->create();

        return $builds;
    }

    /**
     * @param Table $builds
     * @return Table
     */
    public function createArtifactsTable(Table $builds)
    {
        $artifacts = $this->table('artifacts')
            ->addColumn('build_id', 'integer')
            ->addColumn('helper', 'string')
            ->addColumn('file', 'string')
            ->addForeignKey('build_id', $builds, 'id', ['delete' => 'CASCADE']);
        $artifacts->create();

        return $artifacts;
    }

    /**
     * @param Table $builds
     * @return Table
     */
    public function createStepsTable(Table $builds)
    {
        $steps = $this->table('steps')
            ->addColumn('build_id', 'integer')
            ->addColumn('command', 'text')
            ->addColumn('return_status', 'integer', ['null' => true])
            ->addColumn('stop_on_failure', 'boolean')
            ->addColumn('mark_build_failed', 'boolean')
            ->addForeignKey('build_id', $builds, 'id', ['delete' => 'CASCADE']);
        $steps->create();

        return $steps;
    }

    /**
     * @param Table $builds
     * @param Table $plugins
     * @return Table
     */
    public function createAlertsTable(Table $builds, Table $plugins)
    {
        $alerts = $this->table('build_alerts')
            ->addColumn('build_id', 'integer')
            ->addColumn('plugin_id', 'integer', ['null' => true])
            ->addColumn('type', 'string', ['limit' => 20])
            ->addColumn('description', 'string')
            ->addForeignKey('build_id', $builds, 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('plugin_id', $plugins, 'id', ['delete' => 'CASCADE']);
        $alerts->create();

        return $alerts;
    }

    /**
     * @param Table $builds
     * @param Table $plugins
     * @return Table
     */
    public function createBuildExceptionsTable(Table $builds, Table $plugins)
    {
        $exceptions = $this->table('build_exceptions')
            ->addColumn('build_id', 'integer')
            ->addColumn('plugin_id', 'integer', ['null' => true])
            ->addColumn('type', 'string', ['limit' => 100])
            ->addColumn('message', 'text', ['null' => true])
            ->addColumn('asset', 'string', ['null' => true])
            ->addColumn('reference', 'string', ['null' => true])
            ->addColumn('url', 'string', ['null' => true])
            ->addForeignKey('build_id', $builds, 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('plugin_id', $plugins, 'id', ['delete' => 'CASCADE']);
        $exceptions->create();

        return $exceptions;
    }

    /**
     * @param Table $builds
     * @param Table $plugins
     * @return Table
     */
    public function createBuildStatisticsTable(Table $builds, Table $plugins)
    {
        $stats = $this->table('build_statistics')
            ->addColumn('build_id', 'integer')
            ->addColumn('plugin_id', 'integer', ['null' => true])
            ->addColumn('name', 'string')
            ->addColumn('value', 'text')
            ->addForeignKey('build_id', $builds, 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('plugin_id', $plugins, 'id', ['delete' => 'CASCADE']);
        $stats->create();

        return $stats;
    }

    /**
     * @return Table
     */
    public function createUsersTable()
    {
        $users = $this->table('users')
            ->addColumn('full_name', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('alias', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('password', 'text', ['null' => true])
            ->addColumn('public_key', 'text')
            ->addCOlumn('private_key', 'text')
            ->addColumn('created', 'timestamp');
        $users->create();

        $emails = $this->table('user_emails')
            ->addColumn('email', 'string')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', $users, 'id', ['delete' => 'CASCADE']);
        $emails->create();

        $tokens = $this->table('user_tokens')
            ->addColumn('user_id', 'integer')
            ->addColumn('auth_service', 'string', ['limit' => 100])
            ->addColumn('access_token', 'string')
            ->addForeignKey('user_id', $users, 'id', ['delete' => 'CASCADE']);
        $tokens->create();

        return $users;
    }

    /**
     * @return Table
     */
    public function createLogsTable()
    {
        $logs = $this->table('logs')
            ->addColumn('level', 'string', ['limit' => 30])
            ->addColumn('message', 'text')
            ->addColumn('read', 'boolean')
            ->addColumn('created', 'timestamp');
        $logs->create();

        return $logs;
    }
}
