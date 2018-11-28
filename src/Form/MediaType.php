<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use App\Repository\GenreRepository;
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
            ->add('genre', (EntityType::class), array(
                'class' => Genre::class,
                'choice_label' => 'name',

            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, TypeMedia $typeMedia = null) {
        // 4. Add the province element
        $form->add('typeMedia', EntityType::class, array(
            'required' => true,
            'data' => $typeMedia,
            'placeholder' => 'Select a media type...',
            'class' => TypeMedia::class
        ));

        // Neighborhoods empty, unless there is a selected City (Edit View)
        $genres = array();

        // If there is a city stored in the Person entity, load the neighborhoods of it
        if ($typeMedia) {
            // Fetch Neighborhoods of the City if there's a selected city
            $repoGenre = $this->em->getRepository(Genre::class);
            $genres = $repoGenre->findByTypeMedia($typeMedia->getId());
        }

        // Add the Neighborhoods field with the properly data
        $form->add('genre', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Select a media type first ...',
            'class' => Genre::class,
            'choices' => $genres
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected typeMedia and convert it into an Entity
        $typeMedia = $this->em->getRepository(TypeMedia::class)->find($data['TypeMedia']);

        $this->addElements($form, $typeMedia);
    }

    function onPreSetData(FormEvent $event) {
        $media = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $typeMedia = $media->getGenre() ? $media->getGenre() : null;

        $this->addElements($form, $typeMedia);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_Media';
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
