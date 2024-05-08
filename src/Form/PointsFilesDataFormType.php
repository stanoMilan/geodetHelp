<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use Symfony\Component\Form\FormBuilderInterface;

class PointsFilesDataFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('files', SymfonyFileType::class, [
        'label' => 'Vyberte súbory',
        'multiple' => true,
        'attr' => ['accept' => '.txt,.stx'],
        ]);

    }
}
