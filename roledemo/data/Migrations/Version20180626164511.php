<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180626164511 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Create 'user' table
        $table = $schema->createTable('authors');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('first_name', 'string', ['notnull' => true, 'length' => 56]);
        $table->addColumn('last_name', 'string', ['notnull' => true, 'length' => 56]);
        $table->addColumn('birth_date', 'date', ['notnull' => true]);
        $table->addColumn('death_date', 'date', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $table = $schema->createTable('books');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('title', 'string', ['notnull' => true, 'length' => 56]);
        $table->addColumn('author_id', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint(
            'authors',
            ['author_id'],
            ['id'],
            [],
            'books_author_id_fk'
        );
        $table->addOption('engine' , 'InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('books');
        $schema->dropTable('authors');
    }
}
