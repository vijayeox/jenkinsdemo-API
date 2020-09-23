<?php

namespace Oxzion\App;

interface AppArtifactListener {
    public function directoryNameCreated(string $directoryPath);
    public function databaseNameCreated(string $databaseName);
}

?>
