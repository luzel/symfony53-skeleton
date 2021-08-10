<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Password hash example https://github.com/EasyCorp/EasyAdminBundle/issues/3349
 */
class UserCrudController extends AbstractCrudController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserCrudController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

        $plainPassword = TextField::new('plainPassword')
            ->setLabel("New Password")
            ->setFormType(PasswordType::class)
            ->setRequired(false)
            ->setHelp('If the right is not given, leave the field blank.')
            ->hideOnIndex();

        $roles = ChoiceField::new('roles')
            ->setChoices([
                "ROLE_ADMIN" => "ROLE_ADMIN", 
                "ROLE_USER" => "ROLE_USER"
            ])
            ->allowMultipleChoices();
            ;
        

        if (Crud::PAGE_INDEX === $pageName) {
            
            return [
                IdField::new('id'),
                EmailField::new('email'),
                $roles,
                $plainPassword,
            ];

        } elseif (Crud::PAGE_DETAIL === $pageName) {
            
            return [
                EmailField::new('email'),
                $roles,
                $plainPassword,
            ];

        } elseif (Crud::PAGE_NEW === $pageName) {

            return [
                EmailField::new('email'),
                $roles,
                $plainPassword,
            ];

        } elseif (Crud::PAGE_EDIT === $pageName) {

            return [
                EmailField::new('email'),
                $roles,
                $plainPassword,
            ];

        }

    }
        
    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        $this->addEncodePasswordEventListener($formBuilder);

        return $formBuilder;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        $this->addEncodePasswordEventListener($formBuilder);

        return $formBuilder;
    }

    protected function addEncodePasswordEventListener(FormBuilderInterface $formBuilder)
    {
        $formBuilder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var User $user */
            $user = $event->getData();
            if ($user->getPlainPassword()) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            }
        });
    }

}
