<?php
namespace Oxzion\Workflow;

interface IncidentManager
{
    public function getIncident($incidentId);

    public function resolveIncident($incidentId);
}
