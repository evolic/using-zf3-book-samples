<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180601134128 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // Get 'role' table
        $table = $schema->getTable('role');

        $table->addColumn('can_impersonate', 'boolean', [
            'notnull' => true,
            'default' => false
        ]);
        $table->addColumn('can_be_impersonated', 'boolean', [
            'notnull' => true,
            'default' => false
        ]);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

        // Get 'role' table
        $table = $schema->getTable('role');

        $table->dropColumn('can_impersonate');
        $table->dropColumn('can_be_impersonated');
    }
}
