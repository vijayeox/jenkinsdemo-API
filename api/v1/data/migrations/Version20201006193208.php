<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006193208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'update the pricacy as read to existing users';
    }

    public function up(Schema $schema) : void
    {
         $this->addSql("UPDATE ox_user SET policy_terms='1'");

    }

    public function down(Schema $schema) : void
    {
         $this->addSql("UPDATE ox_user SET policy_terms='' ");

    }
}
