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
        $this->description   = lang('Halberd.prompt.description');
        $this->usage = lang('Halberd.prompt.usage');
        $this->arguments = [
            'issuer' => lang('Halberd.prompt.arguments.issuer'),
        ];
    }

    public function run(array $params)
    {
        helper('setting');

        setting('Halberd.issuer', empty($params) ? CLI::prompt(lang('Halberd.prompt.input'), setting('Halberd.issuer') ?? 'Halberd', 'required') : $params[0]);

        $views = setting('Auth.views');

        $views['action_halberd_layout'] = '\GrimPirate\Halberd\Views\layout';
        $views['action_halberd_register'] = '\GrimPirate\Halberd\Views\register';
        $views['action_halberd_login'] = '\GrimPirate\Halberd\Views\login';

        setting('Auth.views', $views);

        $actions = setting('Auth.actions');

        $actions['register'] = '\GrimPirate\Halberd\Authentication\Actions\Register';
        $actions['login'] = '\GrimPirate\Halberd\Authentication\Actions\Login';

        setting('Auth.actions', $actions);
    }
}