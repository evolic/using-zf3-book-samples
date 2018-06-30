<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629170211 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This is the migration which provides OAuth2 tables.';

        return $description;
    }

    public function up(Schema $schema) : void
    {
        // ############################
        // ##        OAuth 2         ##
        // ############################

        $table = $schema->createTable('oauth_users');
        $table->addColumn('username', 'string', ['notnull' => true, 'length' => 31]);
        $table->addColumn('password', 'string', ['notnull' => true, 'length' => 60]);
        $table->addColumn('first_name', 'string', ['notnull' => true, 'length' => 127]);
        $table->addColumn('last_name', 'string', ['notnull' => true, 'length' => 127]);
        $table->setPrimaryKey(['username']);
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('oauth_clients');
        $table->addColumn('client_id', 'string', ['notnull' => true, 'length' => 63]);
        $table->addColumn('client_secret', 'string', ['notnull' => true, 'length' => 60]);
        $table->addColumn('redirect_uri', 'string', ['notnull' => true, 'default' => '/oauth/receivecode']);
        $table->addColumn('grant_types', 'string', ['notnull' => false, 'length' => 80]);
        $table->addColumn('scope', 'string', ['notnull' => false, 'length' => 511]);
        $table->addColumn('user_id', 'string', ['notnull' => false, 'length' => 31]);
        $table->setPrimaryKey(['client_id']);
        $table->addForeignKeyConstraint(
            'oauth_users',
            ['user_id'],
            ['username'],
            [],
            'oauth_clients_user_id_fk'
        );
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('oauth_access_tokens');
        $table->addColumn('access_token', 'string', ['notnull' => true, 'length' => 40]);
        $table->addColumn('client_id', 'string', ['notnull' => true, 'length' => 63]);
        $table->addColumn('user_id', 'string', ['notnull' => false, 'length' => 31]);
        $table->addColumn('expires', 'datetime', ['notnull' => true]);
        $table->addColumn('scope', 'string', ['notnull' => false, 'length' => 511]);
        $table->setPrimaryKey(['access_token']);
        $table->addForeignKeyConstraint(
            'oauth_clients',
            ['client_id'],
            ['client_id'],
            [],
            'oauth_access_tokens_client_id_fk'
        );
        $table->addForeignKeyConstraint(
            'oauth_users',
            ['user_id'],
            ['username'],
            [],
            'oauth_access_tokens_user_id_fk'
        );
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('oauth_authorization_codes');
        $table->addColumn('authorization_code', 'string', ['notnull' => true, 'length' => 40]);
        $table->addColumn('client_id', 'string', ['notnull' => true, 'length' => 63]);
        $table->addColumn('user_id', 'string', ['notnull' => false, 'length' => 31]);
        $table->addColumn('redirect_uri', 'string', ['notnull' => true, 'default' => '/oauth/receivecode']);
        $table->addColumn('expires', 'datetime', ['notnull' => true]);
        $table->addColumn('scope', 'string', ['notnull' => false, 'length' => 511]);
        $table->addColumn('id_token', 'string', ['notnull' => false, 'length' => 2047]);
        $table->setPrimaryKey(['authorization_code']);
        $table->addForeignKeyConstraint(
            'oauth_clients',
            ['client_id'],
            ['client_id'],
            [],
            'oauth_authorization_codes_client_id_fk'
        );
        $table->addForeignKeyConstraint(
            'oauth_users',
            ['user_id'],
            ['username'],
            [],
            'oauth_authorization_codes_user_id_fk'
        );
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('oauth_refresh_tokens');
        $table->addColumn('refresh_token', 'string', ['notnull' => true, 'length' => 40]);
        $table->addColumn('client_id', 'string', ['notnull' => true, 'length' => 63]);
        $table->addColumn('user_id', 'string', ['notnull' => false, 'length' => 31]);
        $table->addColumn('expires', 'datetime', ['notnull' => true]);
        $table->addColumn('scope', 'string', ['notnull' => false, 'length' => 511]);
        $table->setPrimaryKey(['refresh_token']);
        $table->addForeignKeyConstraint(
            'oauth_clients',
            ['client_id'],
            ['client_id'],
            [],
            'oauth_refresh_tokens_client_id_fk'
        );
        $table->addForeignKeyConstraint(
            'oauth_users',
            ['user_id'],
            ['username'],
            [],
            'oauth_refresh_tokens_user_id_fk'
        );
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('oauth_scopes');
        $table->addColumn('type', 'string', ['notnull' => true, 'length' => 255, 'default' => 'supported']);
        $table->addColumn('scope', 'string', ['notnull' => false, 'length' => 511]);
        $table->addColumn('client_id', 'string', ['notnull' => false, 'length' => 63]);
        $table->addColumn('is_default', 'smallint', ['notnull' => false]);
        $table->addForeignKeyConstraint(
            'oauth_clients',
            ['client_id'],
            ['client_id'],
            [],
            'oauth_scopes_client_id_fk'
        );
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('oauth_jwt');
        $table->addColumn('client_id', 'string', ['notnull' => true, 'length' => 63]);
        $table->addColumn('subject', 'string', ['notnull' => false, 'length' => 63]);
        $table->addColumn('public_key', 'string', ['notnull' => false, 'length' => 2047]);
        $table->setPrimaryKey(['client_id']);
        $table->addForeignKeyConstraint(
            'oauth_clients',
            ['client_id'],
            ['client_id'],
            [],
            'oauth_jwt_client_id_fk'
        );
        $table->addOption('engine', 'InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('oauth_jwt');
        $schema->dropTable('oauth_scopes');
        $schema->dropTable('oauth_refresh_tokens');
        $schema->dropTable('oauth_authorization_codes');
        $schema->dropTable('oauth_access_tokens');
        $schema->dropTable('oauth_clients');
        $schema->dropTable('oauth_users');
    }
}
