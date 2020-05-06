DELETE FROM `ox_widget_query` WHERE ox_widget_id<=500 AND ox_query_id<=500;
DELETE FROM `ox_query` WHERE id<=500;
DELETE FROM `ox_widget` WHERE id<=500;
DELETE FROM `ox_visualization` WHERE id<=500;
DELETE FROM `ox_datasource` WHERE id<=500;
DELETE FROM `ox_dashboard` WHERE id<=500;

ALTER TABLE `ox_datasource` AUTO_INCREMENT=500;
INSERT INTO `ox_datasource` (`id`, `name`, `type`, `configuration`, `created_by`, `date_created`, `org_id`, `isdeleted`, `uuid`, `ispublic`, `version`) VALUES (1,'OxzionElasticDs','ElasticSearch','{\"data\": {\"user\": \"elastic\",\"password\": \"changeme\",\"serveraddress\": \"13.52.224.89\",\"port\": \"9200\", \"core\":\"oxzion\", \"type\":\"type\", \"scheme\":\"http\"}}',1,'2020-01-19 23:03:24',1,0,'d08d06ce-0cae-47e7-9c4f-a6716128a303',0,0);

ALTER TABLE `ox_visualization` AUTO_INCREMENT=500;
INSERT INTO `ox_visualization` (`id`, `uuid`, `name`, `created_by`, `date_created`, `org_id`, `isdeleted`, `configuration`, `renderer`, `type`, `version`) VALUES (1,'153f4f96-9b6c-47db-95b2-104af23e7522','Aggregate value',1,'2020-01-19 23:03:24',1,0,'','JsAggregate','inline',0),(2,'e9cdcd3c-01c9-11ea-8d71-362b9e155667','Chart',1,'2020-01-19 23:03:24',1,0,'','amCharts','chart',0),(4,'e9cdd110-01c9-11ea-8d71-362b9e155667','Table',1,'2020-01-19 23:03:24',1,0,'','JsTable','table',0);

ALTER TABLE `ox_query` AUTO_INCREMENT=500;
INSERT INTO `ox_query` (`id`, `uuid`, `name`, `datasource_id`, `configuration`, `ispublic`, `created_by`, `date_created`, `org_id`, `isdeleted`, `version`) VALUES (11,'8f1d2819-c5ff-4426-bc40-f7a20704a738','Sales Grouped by Store',1,'{\"app_name\":\"product_sales\",\"group\":\"store\",\"field\":\"sold_price\",\"operation\":\"sum\"}',0,1,'2019-06-27 07:25:06',1,0,1),(12,'86c0cc5b-2567-4e5f-a741-f34e9f6f1af1','Products sale by person',1,'{\"app_name\":\"product_sales\",\"group\":\"sold_by,product\",\"field\":\"sold_price\",\"operation\":\"sum\",\"filter\":[\"store\",\"==\",\"Chicago\"]}',1,2,'2019-06-27 07:25:06',1,0,1),(17,'6f1d2819-c5ff-2326-bc40-f7a20704a748','Sales by Month',1,'{\"app_name\":\"product_sales\",\"frequency\":\"2\",\"field\":\"sold_price\",\"operation\":\"sum\",\"date_type\":\"date\",\"date-period\":\"2019-01-01/now\"}',0,1,'2019-06-27 07:25:06',1,0,1),(20,'3cf380de-a2e8-41f6-8da4-4e51776cb00f','Product list price',1,'{\"app_name\":\"product_sales\",\"group\":\"product\",\"field\":\"list_price\",\"operation\":\"sum\",\"filter\":[\"store\",\"==\",\"Chicago\"]}',1,1,'2020-01-01 00:00:00',1,0,1),(21,'014a4efe-58a3-465b-98ac-6e4b155c81ba','Product sold price',1,'{\"app_name\":\"product_sales\",\"group\":\"product\",\"field\":\"sold_price\",\"operation\":\"sum\",\"filter\":[\"store\",\"==\",\"Chicago\"]}',1,1,'2020-01-01 00:00:00',1,0,1),(22,'c554519b-cfeb-4a37-9b30-c870d90913bf','Total sales by state',1,'{\"app_name\":\"product_sales\",\"group\":\"state\",\"field\":\"sold_price\",\"operation\":\"sum\"}',1,1,'2020-01-01 00:00:00',1,0,1),(23,'4d8d8467-f489-4440-8b4a-bcdc9a59c766','Total sales by state (sorted desc)',1,'{\"app_name\":\"product_sales\",\"group\":\"state\",\"field\":\"sold_price\",\"operation\":\"sum\"}',1,1,'2020-01-01 00:00:00',1,0,1),(24,'0cf68f21-6e83-4907-8e61-74e35ea9db4e','Total sales by state (sorted asc)',1,'{\"app_name\":\"product_sales\",\"group\":\"state\",\"field\":\"sold_price\",\"operation\":\"sum\"}',1,1,'2020-01-01 00:00:00',1,0,1),(25,'1aa22a25-54a2-405e-8f83-9e4a99d0cfca','One week\'s sale data',1,'{\"app_name\":\"product_sales\",\"list\":\"store,state,product,list_price,sold_price,sold_by,date\",\"date_type\":\"date\",\"date-period\":\"2019-01-01/2019-01-07\"}',1,1,'2020-01-01 00:00:00',1,0,1),(26,'79551607-ed12-4743-a591-17f883e0a32a','Total sales',1,'{\"app_name\":\"product_sales\",\"field\":\"sold_price\",\"operation\":\"sum\"}',1,1,'2020-01-01 00:00:00',1,0,1),(27,'a879b459-d5f2-4ca4-8a3c-0defea8e5a38','Total list price',1,'{\"app_name\":\"product_sales\",\"field\":\"list_price\",\"operation\":\"sum\"}',1,1,'2020-01-01 00:00:00',1,0,1),(28,'2948a4cf-cce8-4ca3-961c-372a21b0c5ed','Product list price (Without store filter)',1,'{\"app_name\":\"product_sales\",\"group\":\"product\",\"field\":\"list_price\",\"operation\":\"sum\",\"filter\":null}',1,1,'2020-01-01 00:00:00',1,0,1);

ALTER TABLE `ox_widget` AUTO_INCREMENT=500;
INSERT INTO `ox_widget` (`id`, `uuid`, `visualization_id`, `ispublic`, `created_by`, `date_created`, `org_id`, `isdeleted`, `name`, `configuration`, `version`, `expression`) VALUES (11,'51e881c3-040d-44d8-9295-f2c3130bafbc',2,1,1,'2019-06-27 07:25:06',1,0,'Vertical bar chart','{\n  \"oxzion-meta\": {\n    \"drillDown\": {\n      \"filter\":\"[\\\"store\\\", \\\"==\\\", \\\"${categoryX}\\\"]\",\n      \"nextWidgetId\":\"51e881c3-040d-44d8-9295-f2c3130bafbc\",\n      \"widgetTitle\":\"Sales by store - drilled down\",\n      \"widgetFooter\":\"Sales by store - drilled down - footer\",\n      \"maxDepth\":1\n    }\n  },\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"Sales\",\n      \"dataFields\": {\n        \"valueY\": \"sold_price\",\n        \"categoryX\": \"store\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"store\"\n      },\n      \"title\": {\n        \"text\": \"Store\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"rotation\":90,\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Sales ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Sales by Store\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Sales by store\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}',1,''),(12,'0e57b45f-5938-4e26-acd8-d65fb89e8503',2,1,1,'2019-06-27 07:25:06',1,0,'Vertical bar chart - stacked','{\n  \"oxzion-meta\": {\n    \"type\": \"stacked-bar\",\n    \"dataSet\": {\n      \"category\": \"sold_by\",\n      \"series\": {\n        \"name\": \"product\",\n        \"value\": \"sold_price\"\n      }\n    }\n  },\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"{SERIES_NAME}\",\n      \"dataFields\": {\n        \"valueY\": \"{SERIES_NAME}\",\n        \"categoryX\": \"sold_by\"\n      },\n      \"stacked\": true,\n      \"tooltipText\": \"{name}:[bold]{SERIES_NAME} : {valueY}[/]\"\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"sold_by\"\n      },\n      \"title\": {\n        \"text\": \"Sold by\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"rotation\": 90,\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Sales ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Products sale by person\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Products sale by person\",\n        \"align\": \"center\"\n      }\n    ]\n  },\n  \"legend\": {\n  }\n}',1,''),(13,'ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c',2,1,1,'2020-03-19 07:05:46',1,0,'Pie chart','{\n  \"series\": [\n    {\n      \"type\": \"PieSeries\",\n      \"name\": \"Sales\",\n      \"dataFields\": {\n        \"value\": \"sold_price\",\n        \"category\": \"product\"\n      },\n      \"slices\": {\n        \"template\": {\n          \"stroke\": \"#fff\",\n          \"strokeWidth\": 2,\n          \"strokeOpacity\": 1,\n          \"cursorOverStyle\": [\n            {\n              \"property\": \"cursor\",\n              \"value\": \"pointer\"\n            }\n          ],\n          \"tooltipText\": \"{name}:[bold]{category} - {value.formatNumber(\'###,###.\')} $[/]\"\n        }\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Sales by store\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Store\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}',1,NULL),(20,'53d2b6b8-5b32-4d19-99f0-b95ffe96bf14',2,1,1,'2020-01-01 00:00:00',1,0,'Line chart','{\n  \"series\": [\n    {\n      \"type\": \"LineSeries\",\n      \"stroke\": \"#CDA2AB\",\n      \"strokeWidth\": 3,\n      \"dataFields\": {\n        \"valueY\": \"sold_price\",\n        \"categoryX\": \"period-month\"\n      },\n      \"name\": \"Price\",\n      \"bullets\": [{\n          \"type\": \"Rectangle\",\n          \"width\": 10,\n          \"height\": 10\n      }]\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"period-month\",\n        \"title\": {\n          \"text\": \"Month\"\n        }\n      }\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Sale ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Sale by month\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Sale by month\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}',1,''),(21,'82c67bbf-2c13-4c45-86be-3455ba1e8444',2,1,1,'2020-01-01 00:00:00',1,0,'Horizontal bar chart','\n{\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"Sales\",\n      \"dataFields\": {\n        \"valueX\": \"sold_price\",\n        \"categoryY\": \"store\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryY} - {valueX}[/]\"\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"store\"\n      },\n      \"title\": {\n        \"text\": \"Store\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Sales ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Sales by Store\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Sales by store\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}\n',1,''),(22,'70a24b39-32cd-42f0-9f49-bf24fbe9222e',2,1,1,'2020-01-01 00:00:00',1,0,'Horizontal bar chart - stacked','{\n  \"oxzion-meta\": {\n    \"type\": \"stacked-bar\",\n    \"dataSet\": {\n      \"category\": \"sold_by\",\n      \"series\": {\n        \"name\": \"product\",\n        \"value\": \"sold_price\"\n      }\n    }\n  },\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"{SERIES_NAME}\",\n      \"dataFields\": {\n        \"valueX\": \"{SERIES_NAME}\",\n        \"categoryY\": \"sold_by\"\n      },\n      \"stacked\": true,\n      \"tooltipText\": \"[bold]{categoryY}[/] : {name} : {valueX} $\"\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"sold_by\"\n      },\n      \"title\": {\n        \"text\": \"Sold by\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Sales ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Products sale by person\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Products sale by person\",\n        \"align\": \"center\"\n      }\n    ]\n  },\n  \"legend\":{\n  }\n}',1,''),(23,'46c50a8f-ccec-4da7-ab1b-65f7aa701556',2,1,1,'2020-01-01 00:00:00',1,0,'Vertical clustered bar chart','{\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"List price\",\n      \"dataFields\": {\n        \"valueY\": \"list_price\",\n        \"categoryX\": \"product\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    },\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"Sold price\",\n      \"dataFields\": {\n        \"valueY\": \"sold_price\",\n        \"categoryX\": \"product\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"product\"\n      },\n      \"title\": {\n        \"text\": \"Product\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"rotation\":90,\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Price ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Product sales\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Product sales\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}',1,''),(24,'538796a8-3886-4c76-907e-35e026a71f2a',2,1,1,'2020-01-01 00:00:00',1,0,'Horizontal clustered bar chart','{\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"List price\",\n      \"dataFields\": {\n        \"valueX\": \"list_price\",\n        \"categoryY\": \"product\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    },\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"Sold price\",\n      \"dataFields\": {\n        \"valueX\": \"sold_price\",\n        \"categoryY\": \"product\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"product\"\n      },\n      \"title\": {\n        \"text\": \"Product\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Price ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Product sales\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Product sales\",\n        \"align\": \"center\"\n      }\n    ]\n  },\n  \"legend\":{\n  }\n}',1,''),(25,'40cb31f8-517c-4c95-a8f8-56f87106ca54',2,1,1,'2020-01-01 00:00:00',1,0,'Total sales by state','{\n    \"oxzion-meta\": {\n        \"type\": \"map\",\n        \"countryCode\":\"US\",\n        \"dataKeys\": {\n            \"state\":\"state\",\n            \"value\":\"sold_price\"\n        },\n        \"tooltipText\":\"{name}:{value.formatNumber(\'###,###.\')} $\",\n        \"showStateName\":true,\n        \"legend\": {\n            \"labels\": {\n                \"min\":\"Sales min.\",\n                \"max\":\"Sales max.\"\n            }\n        }\n    }\n}',1,''),(26,'d07a65ac-01cd-4621-99c4-c9e48a05f385',2,1,1,'2020-01-01 00:00:00',1,0,'Funnel chart','{\n  \"series\": [\n    {\n        \"type\":\"FunnelSeries\",\n        \"colors\": {\n            \"step\":2\n        },\n        \"dataFields\": {\n            \"value\":\"sold_price\",\n            \"category\":\"state\"\n        },\n        \"alignLabels\":true,\n        \"labelsContainer\": {\n            \"paddingLeft\":15,\n            \"width\":200\n        }\n    }\n  ],\n    \"legend\":{\n        \"position\":\"left\",\n        \"valign\":\"bottom\"\n    }\n}',1,''),(27,'27a2c796-0e34-4297-97b7-aa2aa6c254ae',2,1,1,'2020-01-01 00:00:00',1,0,'Pyramid chart','\n{\n  \"series\": [\n    {\n        \"type\":\"PyramidSeries\",\n        \"colors\": {\n            \"step\":2\n        },\n        \"dataFields\": {\n            \"value\":\"sold_price\",\n            \"category\":\"state\"\n        },\n        \"alignLabels\":true,\n        \"valueIs\":\"height\",\n        \"labelsContainer\": {\n            \"paddingLeft\":15,\n            \"width\":200\n        }\n    }\n  ],\n    \"legend\":{\n        \"position\":\"left\",\n        \"valign\":\"bottom\"\n    }\n}\n',1,''),(28,'f21d259d-894f-4102-8bef-55a2d2e14601',4,1,1,'2020-01-01 00:00:00',1,0,'Simple grid','\n{\n    \"oxzion-meta\":{\n        \"exportToExcel\":true\n    },\n    \"resizable\":true,\n    \"filerable\":true,\n    \"groupable\":true,\n    \"reorderable\":true,\n    \"column\":[\n        {\n            \"field\":\"state\",\n            \"title\":\"State\"\n        },\n        {\n            \"field\":\"store\",\n            \"title\":\"Store\"\n        },\n        {\n            \"field\":\"date\",\n            \"title\":\"Date\",\n            \"format\":\"{0:M/d/y}\",\n            \"dataType\":\"date\"\n        },\n        {\n            \"field\":\"product\",\n            \"title\":\"Product\"\n        },\n        {\n            \"field\":\"sold_by\",\n            \"title\":\"Sold by\"\n        },\n        {\n            \"field\":\"list_price\",\n            \"title\":\"List price ($)\",\n            \"format\":\"{0:c}\"\n        },\n        {\n            \"field\":\"sold_price\",\n            \"title\":\"Sold price ($)\",\n            \"format\":\"{0:c}\"\n        }\n    ],\n    \"pageSize\":50,\n    \"pageable\":{\n        \"buttonCount\": 5,\n        \"info\":true,\n        \"pageSizes\":[50,100,200]\n    },\n    \"sort\":[\n        {\n            \"field\":\"store\", \n            \"dir\":\"asc\"\n        }\n    ]\n}\n',1,''),(29,'2dba966e-3132-4164-b14e-f685f8e5b7e8',1,1,1,'2020-01-01 00:00:00',1,0,'Total sales','\n{\n    \"numberFormat\":\"0,0.00 $\"\n}\n',1,''),(30,'e369bcbb-5cd5-4060-a962-c22087e2337a',1,1,1,'2020-01-01 00:00:00',1,0,'Total discount','{\n    \"expression\":\"discount:list_price-sold_price\",\n    \"numberFormat\":\"0,0.00 $\"\n}',1,''),(31,'8572c816-824b-49ec-ad20-2e7cf1cb3568',2,1,1,'2020-01-01 00:00:00',1,0,'Sales by state','{\n    \"oxzion-meta\": {\n        \"drillDown\": {\n            \"target\":\"widget\",\n            \"nextWidgetId\":\"7c2323c1-402e-4175-bb45-c16da5023a7a\",\n            \"filter\":\"[\\\"state\\\", \\\"==\\\", \\\"${code}\\\"]\",\n            \"widgetTitle\":\"Sales by store in ${name} (${code})\",\n            \"widgetFooter\":\"Sales by store in ${name} (${code})\"\n        },\n        \"type\": \"map\",\n        \"countryCode\":\"US\",\n        \"dataKeys\": {\n            \"state\":\"state\",\n            \"value\":\"sold_price\"\n        },\n        \"tooltipText\":\"{name}:{value.formatNumber(\'###,###.\')} $\",\n        \"showStateName\":true,\n        \"legend\": {\n            \"labels\": {\n                \"min\":\"Sales min.\",\n                \"max\":\"Sales max.\"\n            }\n        }\n    }\n}',1,''),(32,'7c2323c1-402e-4175-bb45-c16da5023a7a',2,1,1,'2019-06-27 07:25:06',1,0,'Sales grouped by store','{\n  \"oxzion-meta\": {\n    \"drillDown\": {\n      \"filter\":\"[\\\"store\\\", \\\"==\\\", \\\"${categoryX}\\\"]\",\n      \"nextWidgetId\":\"a3130ba0-511e-4cd4-84ab-b16fa4a2433d\",\n      \"widgetTitle\":\"Product sales at ${categoryX}\",\n      \"widgetFooter\":\"Product sales at ${categoryX}\",\n      \"target\":\"widget\"\n    }\n  },\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"Sales\",\n      \"dataFields\": {\n        \"valueY\": \"sold_price\",\n        \"categoryX\": \"store\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"store\"\n      },\n      \"title\": {\n        \"text\": \"Store\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"rotation\":90,\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Sales ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Sales by Store\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Sales by store\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}',1,''),(33,'a3130ba0-511e-4cd4-84ab-b16fa4a2433d',2,1,1,'2020-01-01 00:00:00',1,0,'Sales in store','{\n  \"series\": [\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"List price\",\n      \"dataFields\": {\n        \"valueY\": \"list_price\",\n        \"categoryX\": \"product\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    },\n    {\n      \"type\": \"ColumnSeries\",\n      \"name\": \"Sold price\",\n      \"dataFields\": {\n        \"valueY\": \"sold_price\",\n        \"categoryX\": \"product\"\n      },\n      \"tooltipText\": \"{name}:[bold]{categoryX} - {valueY}[/]\"\n    }\n  ],\n  \"xAxes\": [\n    {\n      \"type\": \"CategoryAxis\",\n      \"dataFields\": {\n        \"category\": \"product\"\n      },\n      \"title\": {\n        \"text\": \"Product\"\n      },\n      \"renderer\": {\n        \"grid\": {\n          \"template\": {\n            \"location\": 0\n          }\n        },\n        \"labels\": {\n          \"rotation\":90,\n          \"verticalCenter\": \"middle\",\n          \"horizontalCenter\": \"left\"\n        },\n        \"minGridDistance\": 1\n      }\n    }\n  ],\n  \"yAxes\": [\n    {\n      \"type\": \"ValueAxis\",\n      \"title\": {\n        \"text\": \"Price ($)\"\n      }\n    }\n  ],\n  \"cursor\": {\n    \"type\": \"XYCursor\"\n  },\n  \"titles\": [\n    {\n      \"text\": \"Product sales\",\n      \"fontSize\": 25,\n      \"marginBottom\": 30\n    }\n  ],\n  \"chartContainer\": {\n    \"children\": [\n      {\n        \"type\": \"Label\",\n        \"forceCreate\": true,\n        \"text\": \"Product sales\",\n        \"align\": \"center\"\n      }\n    ]\n  }\n}',1,'');

ALTER TABLE `ox_widget_query` AUTO_INCREMENT=500;
INSERT INTO `ox_widget_query` (`ox_widget_id`, `ox_query_id`, `sequence`, `configuration`) VALUES (11,11,1,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(12,12,1,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(20,17,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(21,11,1,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(22,12,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(23,20,1,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(23,21,2,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(24,20,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(24,21,1,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(25,22,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(26,23,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(27,24,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(28,25,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(29,26,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(30,26,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(30,27,1,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(31,22,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(32,11,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(33,28,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}'),(13,21,0,'{\"filter\":null, \"grouping\":null, \"sort\":null}');

ALTER TABLE `ox_dashboard` AUTO_INCREMENT=500;
INSERT INTO `ox_dashboard` (`id`, `uuid`, `name`, `ispublic`, `description`, `dashboard_type`, `created_by`, `date_created`, `org_id`, `isdeleted`, `content`, `version`, `isdefault`, `filter_configuration`) VALUES (2,'12e8002b-f06e-4dca-b97e-5d3912058715','Widget demo',1,'','html',1,'2020-01-19 23:03:24',1,0,'<p>Lorem ipsum dolor sit amet, <span style=\"font-style:bold;font-size:2em;color:red;\"><span class=\"oxzion-widget\" data-oxzion-widget-id=\"2dba966e-3132-4164-b14e-f685f8e5b7e8\" id=\"id_2dba966e-3132-4164-b14e-f685f8e5b7e8\">6,589,579.05 $</span></span> consectetur adipiscing elit. Pellentesque varius, mi vel ornare feugiat, urna leo sagittis neque, ac ullamcorper tortor sem eget ex. Fusce nec finibus ante.</p>\n\n<p><span style=\"font-size:20px;\"><u><strong>Pie chart:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c\" id=\"id_a3130ba0-511e-4cd4-84ab-b16fa4a2433d\" style=\"width: 800px; height: 541px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 541px;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Vertical bar chart:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"51e881c3-040d-44d8-9295-f2c3130bafbc\" id=\"id_51e881c3-040d-44d8-9295-f2c3130bafbc\">\n<div class=\"oxzion-widget-content\" style=\"width: 600px; height: 400px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><u><span style=\"font-size:20px;\"><strong>Vertical stacked bar chart:</strong></span></u></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"0e57b45f-5938-4e26-acd8-d65fb89e8503\" id=\"id_0e57b45f-5938-4e26-acd8-d65fb89e8503\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 500px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><strong><u><span style=\"font-size:20px;\">Line chart:</span></u></strong></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"53d2b6b8-5b32-4d19-99f0-b95ffe96bf14\" id=\"id_53d2b6b8-5b32-4d19-99f0-b95ffe96bf14\">\n<div class=\"oxzion-widget-content\" style=\"width: 600px; height: 400px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Horizontal bar chart:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"82c67bbf-2c13-4c45-86be-3455ba1e8444\" id=\"id_82c67bbf-2c13-4c45-86be-3455ba1e8444\">\n<div class=\"oxzion-widget-content\" style=\"width: 600px; height: 400px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Horizontal stacked bar chart:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"70a24b39-32cd-42f0-9f49-bf24fbe9222e\" id=\"id_70a24b39-32cd-42f0-9f49-bf24fbe9222e\">\n<div class=\"oxzion-widget-content\" style=\"width: 600px; height: 500px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Clustered bar chart - vertical:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"46c50a8f-ccec-4da7-ab1b-65f7aa701556\" id=\"id_46c50a8f-ccec-4da7-ab1b-65f7aa701556\">\n<div class=\"oxzion-widget-content\" style=\"width: 600px; height: 400px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Clustered bar chart - horizontal:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"538796a8-3886-4c76-907e-35e026a71f2a\" id=\"id_538796a8-3886-4c76-907e-35e026a71f2a\">\n<div class=\"oxzion-widget-content\" style=\"width: 600px; height: 400px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Funnel chart:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"d07a65ac-01cd-4621-99c4-c9e48a05f385\" id=\"id_42507b62-4c91-4809-8bb9-746425e366b9\" style=\"width: 758px; height: 468px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 758px; height: 468px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Pyramid chart:</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"27a2c796-0e34-4297-97b7-aa2aa6c254ae\" id=\"id_e5d7b13a-08f7-46ec-89a8-689b102c2895\" style=\"width: 800px; height: 401px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 401px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><u><span style=\"font-size:20px;\"><strong>Map:</strong></span></u></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"40cb31f8-517c-4c95-a8f8-56f87106ca54\" id=\"id_496bce87-06ec-4525-b6f8-02c47bc80b50\" style=\"width: 800px; height: 541px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 541px;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p><span style=\"font-size:20px;\"><u><strong>Grid (with excel export icon on top right corner - visible only in view mode):</strong></u></span></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"f21d259d-894f-4102-8bef-55a2d2e14601\" id=\"id_678786ff-cfe9-4690-b206-83b835dbef5d\" style=\"width: 800px; height: 534px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 534px;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p>&nbsp;</p>\n\n<p><u><span style=\"font-size:20px;\"><strong>Drill down:Sales</strong></span></u></p>\n\n<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"8572c816-824b-49ec-ad20-2e7cf1cb3568\" id=\"id_7896b5b7-6783-426d-b59e-ad1a5ea40017\" style=\"width: 800px; height: 541px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 541px;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p>&nbsp;</p>\n\n<p>&nbsp;</p>\n\n<p>&nbsp;</p>\n\n<p>&nbsp;</p>\n\n<p>Maecenas a ligula id orci vestibulum venenatis.</p>\n\n<p>&nbsp;</p>\n\n<p>Pellentesque eros eros, rhoncus nec euismod id, accumsan eu lorem. Sed porta, tortor quis mattis pellentesque, felis sapien pellentesque sem, quis dignissim dui purus eget metus. Cras non neque vitae lectus lacinia luctus. Maecenas semper, velit gravida aliquam lacinia, arcu nulla ullamcorper augue, lacinia vulputate ligula arcu ac nisl. Phasellus rutrum diam ut posuere venenatis. Aliquam faucibus elit id purus finibus dictum. Nulla eget aliquet orci. In diam leo, ornare sit amet dictum sit amet, pellentesque a lorem. Nulla suscipit nulla non viverra ultricies. Phasellus mattis pretium sem a cursus. Morbi eu velit vitae velit sagittis elementum. Etiam est turpis, convallis volutpat enim vel, dapibus condimentum elit. Nulla semper porta odio ac dictum.</p>\n',4,1,NULL),(3,'34074787-14ec-4461-ae5d-ce33b1714533','Test dashboard',1,'','html',1,'2020-01-19 23:03:24',1,0,'<figure class=\"oxzion-widget\" data-oxzion-widget-id=\"51e881c3-040d-44d8-9295-f2c3130bafbc\" id=\"id_6a286ce8-7212-4e2a-95f0-0a5f46e63b31\" style=\"width: 800px; height: 438px;\">\n<div class=\"oxzion-widget-content\" style=\"width: 800px; height: 438px; position: relative;\"></div>\n\n<figcaption class=\"oxzion-widget-caption\">&nbsp;</figcaption>\n</figure>\n\n<p>&nbsp;</p>\n',4,0,NULL);


