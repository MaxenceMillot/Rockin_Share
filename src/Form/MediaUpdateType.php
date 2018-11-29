<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            #->add('dateCreated')
            ->add('picture',FileType::class,array(
                'required' => false,
                'data_class' => null
            ))
            #->add('extension')
            ->add('genre', EntityType::class, array(
                'class' => Genre::class,
                'query_builder' => function (GenreRepository $er) use($options) {
                    return $er->createQueryBuilder('g')
                        ->andWhere("g.typeMedia = :typeMedia")
                        ->setParameter("typeMedia",$options["data"]->getGenre()[0]->getTypeMedia());
                },
                'choice_label' => 'name'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
