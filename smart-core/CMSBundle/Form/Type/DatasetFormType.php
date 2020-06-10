<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Form\Type;

use SmartCore\CMSBundle\Entity\Content\Dataset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatasetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['attr' => ['autofocus' => true]])
            ->add('slug')
            ->add('icon')
            ->add('description')

            ->add('create', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('save',   SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('cancel', SubmitType::class, ['attr' => ['class' => 'btn-light', 'formnovalidate' => 'formnovalidate']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dataset::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'cms_dataset';
    }
}
