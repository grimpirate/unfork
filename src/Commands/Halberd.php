<?php

namespace GrimPirate\Halberd\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Psr\Log\LoggerInterface;

class Halberd extends BaseCommand
{
    protected $group =          'Halberd';
    protected $name =           'halberd:ini';

    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        $this->description   = lang('TOTP.prompt.description');
        $this->usage = lang('TOTP.prompt.usage');
        $this->arguments = [
            'issuer' => lang('TOTP.prompt.arguments.issuer'),
            'stylesheet' => lang('TOTP.prompt.arguments.stylesheet'),
        ];
    }

    public function run(array $params)
    {
        helper('setting');

        setting('TOTP.issuer', !isset($params[0]) ? CLI::prompt(lang('TOTP.prompt.input'), setting('TOTP.issuer') ?? 'Halberd', 'required') : $params[0]);
        setting('TOTP.stylesheet', !isset($params[1]) ? CLI::prompt(lang('TOTP.prompt.input'), setting('TOTP.stylesheet') ?? 'css/totp.css', 'required') : $params[1]);

        $views = setting('Auth.views');

        $views['action_totp'] = '\GrimPirate\Halberd\Views\totp_show';

        setting('Auth.views', $views);

        $actions = setting('Auth.actions');

        $actions['register'] = '\GrimPirate\Halberd\Authentication\Actions\TOTPActivator';
        $actions['login'] = '\GrimPirate\Halberd\Authentication\Actions\TOTPActivator';

        setting('Auth.actions', $actions);
    }
}
