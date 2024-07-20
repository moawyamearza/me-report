<?php

namespace App\Form;

use App\Entity\Booklib;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bookname', TextType::class, ['label' => 'Book Name'])
            ->add('isbn', TextType::class, ['label' => 'ISBN'])
            ->add('writer', TextType::class, ['label' => 'Author'])
            ->add('image', UrlType::class, ['label' => 'Book Cover']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booklib::class,
        ]);
    }
}
