<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629205238 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // ############################
        // ##         Notes          ##
        // ############################

        $table = $schema->createTable('notes');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('title', 'string', ['notnull' => false, 'length' => 127]);
        $table->addColumn('body', 'text', ['notnull' => false]);
        $table->addColumn('user_id', 'string', ['notnull' => false, 'length' => 31]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint(
            'oauth_users',
            ['user_id'],
            ['username'],
            [],
            'notes_user_id_fk'
        );
        $table->addOption('engine' , 'InnoDB');


        // ############################
        // ##       Book Store       ##
        // ############################

        $table = $schema->getTable('books');

        $table->addColumn('price', 'float', [
            'notnull' => true,
            'default' => 0
        ]);
    }

    public function down(Schema $schema) : void
    {
        // ############################
        // ##         Notes          ##
        // ############################

        $schema->dropTable('notes');


        // ############################
        // ##       Book Store       ##
        // ############################

        $table = $schema->getTable('books');
        $table->dropColumn('price');
    }
}
