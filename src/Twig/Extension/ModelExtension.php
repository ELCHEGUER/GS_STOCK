<?php 

namespace App\Twig\Extension;
use App\Entity\User;
use App\Repository\ModelRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ModelExtension extends AbstractExtension
{
    private ModelRepository $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }
    
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('Models', [$this, 'Models']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('Models', [$this, 'Models']),
        ];
    }

    public function Models(): array
    {
        // if ($user instanceof User) {
        //     if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
        //         return $this->modelRepository->findAll();
        //     }
        //     // return $this->modelRepository->findByRoleUser();
        // }
        return $this->modelRepository->findAll();
    }
}
