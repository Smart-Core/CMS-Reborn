<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Form\Type;

use SmartCore\CMSBundle\Entity\Content\Table;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => true]])
            ->add('name')
            ->add('class_name')
            ->add('description')

            ->add('create', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('save',   SubmitType::class, ['attr' => ['class' => 'btn-success']])
//            ->add('cancel', SubmitType::class, ['attr' => ['class' => 'btn-light', 'formnovalidate' => 'formnovalidate']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Table::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'cms_dataset';
    }
}
