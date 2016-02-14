<?php 
    include('Logger.php');
    Logger::configure('mini.logger.config.xml');

    // Fetch a logger, it will inherit settings from the root logger
    $log = Logger::getLogger('mini');
    
    // Start logging
    $log->trace("My first message.");   // Not logged because TRACE < WARN
    $log->debug("My second message.");  // Not logged because DEBUG < WARN
    $log->info("My third message.");    // Not logged because INFO < WARN
    $log->warn("My fourth message.");   // Logged because WARN >= WARN
    $log->error("My fifth message.");   // Logged because ERROR >= WARN
    $log->fatal("My sixth message.");   // Logged because FATAL >= WARN
?>