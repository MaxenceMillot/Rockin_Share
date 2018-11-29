<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            #->add('dateCreated')
            ->add('picture',FileType::class)
            #->add('extension')
            ->add('typeMedia', EntityType::class, array(
                'class' => TypeMedia::class,
                'placeholder' => '',
                'mapped' => false,
                'choice_label' => 'name'
            ))
        ;

        $formModifier = function (FormInterface $form, TypeMedia $typeMedia = null) {
            $genres = null === $typeMedia ? array() : $typeMedia->getGenres();
            $form->add('genre', (EntityType::class), array(
                'class' => Genre::class,
                'choices' =>$genres,
                'multiple' => true,
                'required' => true,
                'choice_label' => 'name',
                'placeholder' => ''
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getGenre());
            }
        );

        $builder ->get('typeMedia')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $typeMedia = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $typeMedia);
            }
        );
    }



    public function getName()
    {
        return "media_type";
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
