<?php

namespace Tripteki\AuthApi\Console\Commands;

use Illuminate\Console\Command;
use Tripteki\Helpers\Helpers\ProjectHelper;

class InstallCommand extends Command
{
    use InstallsBackendSanctum, InstallsBackendPassport;

    /**
     * @var string
     */
    protected $signature = "auth:install";

    /**
     * @var string
     */
    protected $description = "Install the auth stack";

    /**
     * @var \Tripteki\Helpers\Helpers\ProjectHelper
     */
    protected $helper;

    /**
     * @param \Tripteki\Helpers\Helpers\ProjectHelper $helper
     * @return void
     */
    public function __construct(ProjectHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function handle()
    {
        return 0;
    }
};
