<?php
namespace AppBundle\Form\Type;

use AppBundle\Entity\SearchParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Služi za filtriranje po prioritetu ili statusu
 */
class SearchParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('priority', ChoiceType::class, [
            'choices'  => [
                'low' => 'low',
                'medium' => 'medium',
                'high' => 'high',
            ],
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'label' => false
        ])
        ->add('status', ChoiceType::class, [
            'choices'  => [
                'opened' => 'opened',
                'in_process' => 'in_process',
                'closed' => 'closed',
            ],
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'label' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'get',
            'data_class' => SearchParameters::class,
            'csrf_protection' => false,
        ]);
    }
}
