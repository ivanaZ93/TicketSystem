<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Definiranje forme za stvaranje novog naloga
 */
class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', null, [
            'attr' => ['rows' => 20],
            'label' => 'Content',
        ])
        ->add('priority', ChoiceType::class, array(
            'choices' => array(
            'low' => "low",
            'medium' => "medium",
            'high' => "high",
            ),
        ))
        ->add('status', ChoiceType::class, array(
            'choices' => array(
            'opened' => "opened",
            'in_process' => "in_process",
            'closed' => "closed",
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
