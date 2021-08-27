<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125161610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Analytics table data insertion, alterations and new tables.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("DROP TABLE ox_widget_dashboard_mapper");

        $this->addSql("INSERT INTO ox_datasource (name, type, configuration, created_by, org_id, uuid) VALUES ('OxzionElasticDs', 'ElasticSearch', '', 1, 1, 'd08d06ce-0cae-47e7-9c4f-a6716128a303')");

        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('153f4f96-9b6c-47db-95b2-104af23e7522', 'Aggregate value', 1, 1, 'inline', 'JsAggregate', '')");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('e9cdcd3c-01c9-11ea-8d71-362b9e155667', 'Chart', 1, 1, 'chart', 'amCharts', '')");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('e9cdcfb2-01c9-11ea-8d71-362b9e155667', 'Calculated value', 1, 1, 'inline', 'JsCalculator', '')");
        $this->addSql("INSERT INTO ox_visualization (uuid, name, created_by, org_id, type, renderer, configuration) VALUES ('e9cdd110-01c9-11ea-8d71-362b9e155667', 'Table', 1, 1, 'table', 'JsTable', '')");

        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('bf0a8a59-3a30-4021-aa79-726929469b07', 'Sales YTD', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('3c0c8e99-9ec8-4eac-8df5-9d6ac09628e7', 'Sales by sales person', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('45933c62-6933-43da-bbb2-59e6f331e8db', 'Quarterly revenue target', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('69f7732a-998a-41bb-ab89-aa7c434cb327', 'Revenue YTD', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('de5c309d-6bd6-494f-8c34-b85ac109a301', 'Product sales', 1, '', true, 1, 1)");
        $this->addSql("INSERT INTO ox_query (uuid, name, datasource_id, configuration, ispublic, created_by, org_id) VALUES ('689ce139-0d77-46ba-bcf1-d4a80f3e25db', 'Sales target', 1, '', true, 1, 1)");

        $this->addSql("ALTER TABLE ox_widget DROP FOREIGN KEY `ox_widget_ibfk_2`");
        $this->addSql("ALTER TABLE ox_widget DROP COLUMN query_id");

        $this->addSql("INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('2aab5e6a-5fd4-44a8-bb50-57d32ca226b0', 1, true, 1, 1, 'Sales YTD', '{\"numberFormat\":\"$ 0.00 a\"}')");
        $this->addSql("INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('bacb4ec3-5f29-49d7-ac41-978a99d014d3', 2, true, 1, 1, 'Sales by sales person - bar chart', '{\"series\":[{\"type\":\"ColumnSeries\",\"name\":\"Sales\",\"dataFields\":{\"valueY\":\"sales\",\"categoryX\":\"person\"},\"tooltipText\":\"{name}:[bold]{categoryX} - {valueY}[/]\"}],\"xAxes\":[{\"type\":\"CategoryAxis\",\"dataFields\":{\"category\":\"person\"},\"title\":{\"text\":\"Sales person\"},\"renderer\":{\"grid\":{\"template\":{\"location\":0}},\"minGridDistance\":1}}],\"yAxes\":[{\"type\":\"ValueAxis\",\"title\":{\"text\":\"Sales (Million $)\"}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"Sales\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"Sales by person\",\"align\":\"center\"}]}}')");
        $this->addSql("INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c', 2, true, 1, 1, 'Sales by sales person - pie chart', '{\"series\":[{\"type\":\"PieSeries\",\"name\":\"Sales\",\"dataFields\":{\"value\":\"sales\",\"category\":\"person\"},\"slices\":{\"template\":{\"stroke\":\"#fff\",\"strokeWidth\":2,\"strokeOpacity\":1,\"cursorOverStyle\":[{\"property\":\"cursor\",\"value\":\"pointer\"}],\"tooltipText\":\"{name}:[bold]{category} - {value}[/]\"}}}],\"cursor\":{\"type\":\"XYCursor\"},\"titles\":[{\"text\":\"Sales\",\"fontSize\":25,\"marginBottom\":30}],\"chartContainer\":{\"children\":[{\"type\":\"Label\",\"forceCreate\":true,\"text\":\"Sales by person\",\"align\":\"center\"}]}}')");
        $this->addSql("INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('d5927bc2-d87b-4dd5-b45b-66d7c5fcb3f1', 3, true, 1, 1, '% sales achieved', '{\"expression\":\"Q1/Q2*100\", \"numberFormat\":\"0.00 %\"}')");
        $this->addSql("INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, org_id, name, configuration) VALUES ('e1933370-22bd-4cd8-abc9-fcdc29b6481d', 4, true, 1, 1, 'Sales by sales person - table', '')");

        $this->addSql("CREATE TABLE ox_widget_query ( 
            ox_widget_id INT NOT NULL REFERENCES ox_widget.id, 
            ox_query_id INT NOT NULL REFERENCES ox_query.id, 
            sequence TINYINT UNSIGNED DEFAULT 0,
            configuration TEXT)");
        $this->addSql("INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, configuration) VALUES (1, 1, '')");
        $this->addSql("INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, configuration) VALUES (2, 2, '')");
        $this->addSql("INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, configuration) VALUES (3, 2, '')");
        $this->addSql("INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, configuration) VALUES (4, 1, '')");
        $this->addSql("INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, configuration) VALUES (4, 6, '')");

        $this->addSql("INSERT INTO ox_dashboard (uuid, name, ispublic, dashboard_type, created_by, org_id, content) VALUES ('c6318742-b9f9-4a18-abce-7a7fbbac8c8b', 'Test dashboard', true, 'html', 1, 1, '<p>Lorem ipsum dolor sit amet, <span style=\"font-style:bold;font-size:2em;color:red;\"><span class=\"oxzion-widget\" id=\"id_f5b8ee95-8da2-409a-8cf0-fa5b4af10667\" data-oxzion-widget-id=\"2aab5e6a-5fd4-44a8-bb50-57d32ca226b0\"></span></span> consectetur adipiscing elit. Pellentesque varius, mi vel ornare feugiat, urna leo sagittis neque, ac ullamcorper tortor sem eget ex. Fusce nec finibus ante. <figure class=\"oxzion-widget\" id=\"id_f5b8ee95-8da2-409a-8cf1-fa5b4af10667\" data-oxzion-widget-id=\"bacb4ec3-5f29-49d7-ac41-978a99d014d3\"><div class=\"oxzion-widget-content\" style=\"width:600px;height:300px;\"></div><figcaption class=\"oxzion-widget-caption\"></figcaption></figure> Maecenas a ligula id orci vestibulum venenatis. </p><p>Pellentesque eros eros, rhoncus nec euismod id, accumsan eu lorem. Sed porta, tortor quis mattis pellentesque, felis sapien pellentesque sem, quis dignissim dui purus eget metus. Cras non neque vitae lectus lacinia luctus. Maecenas semper, velit gravida aliquam lacinia, arcu nulla ullamcorper augue, lacinia vulputate ligula arcu ac nisl. Phasellus rutrum diam ut posuere venenatis. Aliquam faucibus elit id purus finibus dictum. Nulla eget aliquet orci. In diam leo, ornare sit amet dictum sit amet, pellentesque a lorem. Nulla suscipit nulla non viverra ultricies. Phasellus mattis pretium sem a cursus. Morbi eu velit vitae velit sagittis elementum. Etiam est turpis, convallis volutpat enim vel, dapibus condimentum elit. Nulla semper porta odio ac dictum.</p>')");
    }

    public function down(Schema $schema) : void
    {
        //There are complex table changes and data insertions in "up" migration. It is not possible to cleanly revert the changes. 
        //Therefore "down" migration has not been provided.
    }
}
