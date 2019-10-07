<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190915012846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Alter dashboard related tables and add sample data for testing.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_datasource MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_datasource MODIFY type VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_datasource ADD ispublic BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE ox_datasource ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("INSERT INTO ox_datasource (name, type, configuration, created_by, org_id, uuid) VALUES ('OxDs', 'MySQL', '', 1, 1, 'd08d06ce-0cae-47e7-9c4f-a6716128a303')");

        $this->addSql("ALTER TABLE ox_visualization ADD COLUMN configuration TEXT NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization ADD COLUMN renderer VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization ADD COLUMN type VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_visualization MODIFY org_id INTEGER NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('153f4f96-9b6c-47db-95b2-104af23e7522', 'Aggregate value', 1, 1, 'inline', 'html', '')");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('d4abdb81-a12a-4fc2-86ee-7145815beb61', 'Bar chart', 1, 1, 'barChart', 'amCharts', '{\"series\":[{\"type\":\"ColumnSeries\",\"name\":\"${valueSeriesName}\",\"dataFields\":{\"valueY\":\"${valueColumn}\",\"categoryX\":\"${categoryColumn}\"},\"tooltipText\":\"{name}:[bold]{categoryX} - {valueY}[/]\"}],\"xAxes\":[{\"type\":\"CategoryAxis\",\"dataFields\":{\"category\":\"${categoryColumn}\"},\"title\":{\"text\":\"${categorySeriesLabel}\"},\"renderer\":{\"grid\":{\"template\":{\"location\":0}},\"minGridDistance\":1}}],\"yAxes\":[{\"type\":\"ValueAxis\",\"title\":{\"text\":\"${valueSeriesLabel}\"}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"${chartTitle}\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"${chartFooter}\",\"align\": \"center\"}]}}')");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('e0658f1e-f84c-4d9b-b209-d2378730f776', 'Line chart', 1, 1, 'lineChart', 'amCharts', '{\"series\":[{\"type\":\"LineSeries\",\"name\":\"${valueSeriesName}\",\"dataFields\":{\"valueY\":\"${valueColumn}\",\"categoryX\":\"${categoryColumn}\"},\"bullets\":[{\"type\":\"CircleBullet\",\"circle\":{\"radius\":4,\"fill\":\"#fff\",\"strokeWidth\":\"2\"}}],\"tooltipText\":\"{categoryX}:[bold]{valueY} [/]\"}],\"xAxes\":[{\"type\":\"CategoryAxis\",\"dataFields\":{\"category\":\"${categoryColumn}\"},\"title\":{\"text\":\"${categorySeriesLabel}\"},\"renderer\":{\"minGridDistance\":1,\"labels\":{\"template\":{\"rotation\":\"${categoryLabelRotation}\"}},\"grid\":{\"template\":{\"location\":0}}}}],\"yAxes\":[{\"type\":\"ValueAxis\",\"title\":{\"text\":\"${valueSeriesLabel}\"}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"${chartTitle}\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"${chartFooter}\",\"align\":\"center\"}]}}')");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('0439fc08-f855-434d-8f24-c38424056963', 'Pie chart', 1, 1, 'pieChart', 'amCharts', '{\"series\":[{\"type\":\"PieSeries\",\"name\":\"${valueSeriesName}\",\"dataFields\":{\"value\":\"${valueColumn}\",\"category\":\"${categoryColumn}\"},\"slices\":{\"template\":{\"stroke\":\"#fff\",\"strokeWidth\":2,\"strokeOpacity\":1,\"cursorOverStyle\":[{\"property\":\"cursor\",\"value\":\"pointer\"}],\"tooltipText\":\"{name}:[bold]{category} - {value}[/]\"}}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"${chartTitle}\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"${chartFooter}\",\"align\":\"center\"}]}}')");

        $this->addSql("ALTER TABLE ox_query MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_query MODIFY org_id INTEGER NOT NULL");
        $this->addSql("ALTER TABLE ox_query ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('bf0a8a59-3a30-4021-aa79-726929469b07', 'Sales YTD', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('3c0c8e99-9ec8-4eac-8df5-9d6ac09628e7', 'Sales by sales person', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('45933c62-6933-43da-bbb2-59e6f331e8db', 'Quarterly revenue target', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('69f7732a-998a-41bb-ab89-aa7c434cb327', 'Revenue YTD', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('de5c309d-6bd6-494f-8c34-b85ac109a301', 'Product sales', 1, '', true, 1, 1)");

        $this->addSql("ALTER TABLE ox_widget MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_widget MODIFY org_id INTEGER NOT NULL");
        $this->addSql("ALTER TABLE ox_widget MODIFY configuration TEXT NOT NULL");
        $this->addSql("ALTER TABLE ox_widget ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("INSERT INTO ox_widget (uuid, query_id, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('2aab5e6a-5fd4-44a8-bb50-57d32ca226b0', 1, 1, true, 1, 1, 'Sales YTD', '')");
        $this->addSql("INSERT INTO ox_widget (uuid, query_id, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('bacb4ec3-5f29-49d7-ac41-978a99d014d3', 2, 2, true, 1, 1, 'Sales by sales person - bar chart', '{\"series\":[{\"type\":\"ColumnSeries\",\"name\":\"Sales\",\"dataFields\":{\"valueY\":\"sales\",\"categoryX\":\"person\"},\"tooltipText\":\"{name}:[bold]{categoryX} - {valueY}[/]\"}],\"xAxes\":[{\"type\":\"CategoryAxis\",\"dataFields\":{\"category\":\"person\"},\"title\":{\"text\":\"Sales person\"},\"renderer\":{\"grid\":{\"template\":{\"location\":0}},\"minGridDistance\":1}}],\"yAxes\":[{\"type\":\"ValueAxis\",\"title\":{\"text\":\"Sales (Million $)\"}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"Sales\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"Sales by person\",\"align\":\"center\"}]}}')");
        $this->addSql("INSERT INTO ox_widget (uuid, query_id, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c', 2, 4, true, 1, 1, 'Sales by sales person - pie chart', '{\"series\":[{\"type\":\"PieSeries\",\"name\":\"Sales\",\"dataFields\":{\"value\":\"sales\",\"category\":\"person\"},\"slices\":{\"template\":{\"stroke\":\"#fff\",\"strokeWidth\":2,\"strokeOpacity\":1,\"cursorOverStyle\":[{\"property\":\"cursor\",\"value\":\"pointer\"}],\"tooltipText\":\"{name}:[bold]{category} - {value}[/]\"}}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"Sales\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"Sales by person\",\"align\":\"center\"}]}}')");
        $this->addSql("INSERT INTO ox_widget (uuid, query_id, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('08bd8fc1-2336-423d-842a-7217a5482dc9', 3, 3, true, 1, 1, 'Quarterly revenue', '{\"series\":[{\"type\":\"LineSeries\",\"name\":\"Revenue\",\"dataFields\":{\"valueY\":\"revenue\",\"categoryX\":\"quarter\"},\"bullets\":[{\"type\":\"CircleBullet\",\"circle\":{\"radius\":4,\"fill\":\"#fff\",\"strokeWidth\":\"2\"}}],\"tooltipText\":\"{categoryX}:[bold]{valueY}[/]\"}],\"xAxes\":[{\"type\":\"CategoryAxis\",\"dataFields\":{\"category\":\"quarter\"},\"title\":{\"text\":\"Quarter\"},\"renderer\":{\"minGridDistance\":1,\"labels\":{\"template\":{\"rotation\":270}},\"grid\":{\"template\":{\"location\":0}}}}],\"yAxes\":[{\"type\":\"ValueAxis\",\"title\":{\"text\":\"Revenue (Million $)\"}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"Revenue\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"Quarterly revenue\",\"align\":\"center\"}]}}')");

        $this->addSql("ALTER TABLE ox_dashboard MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY dashboard_type VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY org_id INTEGER NOT NULL");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY content TEXT NOT NULL");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY ispublic boolean NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY isdeleted boolean NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE ox_dashboard ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("INSERT INTO ox_dashboard (uuid, name, ispublic, dashboard_type, created_by, org_id, content) VALUES ('c6318742-b9f9-4a18-abce-7a7fbbac8c8b', 'Test dashboard', true, 'html', 1, 1, '<p>Lorem ipsum dolor sit amet, <span style=\"font-style:bold;font-size:2em;color:red;\"><span class=\"oxzion-widget\" id=\"id_f5b8ee95-8da2-409a-8cf0-fa5b4af10667\" data-oxzion-widget-id=\"2aab5e6a-5fd4-44a8-bb50-57d32ca226b0\"></span></span> consectetur adipiscing elit. Pellentesque varius, mi vel ornare feugiat, urna leo sagittis neque, ac ullamcorper tortor sem eget ex. Fusce nec finibus ante. <figure class=\"oxzion-widget\" id=\"id_f5b8ee95-8da2-409a-8cf1-fa5b4af10667\" data-oxzion-widget-id=\"bacb4ec3-5f29-49d7-ac41-978a99d014d3\"><div class=\"oxzion-widget-content\" style=\"width:600px;height:300px;\"></div><figcaption class=\"oxzion-widget-caption\"></figcaption></figure> Maecenas a ligula id orci vestibulum venenatis. </p><p>Pellentesque eros eros, rhoncus nec euismod id, accumsan eu lorem. Sed porta, tortor quis mattis pellentesque, felis sapien pellentesque sem, quis dignissim dui purus eget metus. Cras non neque vitae lectus lacinia luctus. Maecenas semper, velit gravida aliquam lacinia, arcu nulla ullamcorper augue, lacinia vulputate ligula arcu ac nisl. Phasellus rutrum diam ut posuere venenatis. Aliquam faucibus elit id purus finibus dictum. Nulla eget aliquet orci. In diam leo, ornare sit amet dictum sit amet, pellentesque a lorem. Nulla suscipit nulla non viverra ultricies. Phasellus mattis pretium sem a cursus. Morbi eu velit vitae velit sagittis elementum. Etiam est turpis, convallis volutpat enim vel, dapibus condimentum elit. Nulla semper porta odio ac dictum.</p>')");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM ox_dashboard WHERE uuid='c6318742-b9f9-4a18-abce-7a7fbbac8c8b'");
        $this->addSql("ALTER TABLE ox_dashboard DROP version");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='08bd8fc1-2336-423d-842a-7217a5482dc9'");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c'");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='bacb4ec3-5f29-49d7-ac41-978a99d014d3'");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='2aab5e6a-5fd4-44a8-bb50-57d32ca226b0'");
        $this->addSql("ALTER TABLE ox_widget DROP version");
        $this->addSql("DELETE FROM ox_query WHERE uuid='de5c309d-6bd6-494f-8c34-b85ac109a301'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='69f7732a-998a-41bb-ab89-aa7c434cb327'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='45933c62-6933-43da-bbb2-59e6f331e8db'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='3c0c8e99-9ec8-4eac-8df5-9d6ac09628e7'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='bf0a8a59-3a30-4021-aa79-726929469b07'");
        $this->addSql("ALTER TABLE ox_query DROP version");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='0439fc08-f855-434d-8f24-c38424056963'");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='e0658f1e-f84c-4d9b-b209-d2378730f776'");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='d4abdb81-a12a-4fc2-86ee-7145815beb61'");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='153f4f96-9b6c-47db-95b2-104af23e7522'");
        $this->addSql("ALTER TABLE ox_visualization DROP COLUMN type");
        $this->addSql("ALTER TABLE ox_visualization DROP COLUMN renderer");
        $this->addSql("ALTER TABLE ox_visualization DROP COLUMN configuration");
        $this->addSql("ALTER TABLE ox_visualization DROP version");
        $this->addSql("DELETE FROM ox_datasource WHERE uuid='d08d06ce-0cae-47e7-9c4f-a6716128a303'");
        $this->addSql("ALTER TABLE ox_datasource DROP ispublic");
        $this->addSql("ALTER TABLE ox_datasource DROP version");
    }
}

