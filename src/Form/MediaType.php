<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use App\Repository\GenreRepository;
use App\Repository\TypeMediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            #->add('dateCreated')
            ->add('picture',FileType::class)
            #->add('extension')
            ->add('genre', (EntityType::class), array(
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (GenreRepository $er) {
                    $qb = $er->createQueryBuilder('genre')
                        ->innerJoin('genre.typeMedia', 'typeMedia');
                    return $qb;
                },
                'group_by' => function (Genre $genre) {
                    return $genre->getTypeMedia()->getName();
                },
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
