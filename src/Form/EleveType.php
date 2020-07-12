<?php

namespace App\Form;

use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])
        ->add('prenom', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ],
            "required" => false
        ])
        ->add('classe', ChoiceType::class, [
            "attr" => [
                "class" => "form-control"
            ],
            "choices" => [
                'Sil' => [
                    'Sil-A' => 'Sil-A',
                    'Sil-B' => 'Sil-B',
                ],
                'CP' => [
                    'CP-A' => 'CP-A',
                    'CP-B' => 'CP-B',
                ],
                'CE1' => [
                    'CE1-A' => 'CE1-A',
                    'CE1-B' => 'CE1-B',
                ],
                'CE2' => [
                    'CE2-A' => 'CE2-A',
                    'CE2-B' => 'CE2-B',
                ],
                'CM1' => [
                    'CM1-A' => 'CM1-A',
                    'CM1-B' => 'CM1-B',
                ],
                'CM2' => [
                    'CM2-A' => 'CM2-A',
                    'CM2-B' => 'CM2-B',
                ]
            ],
        ])
        ->add('date_naissance', DateType::class, [
            'widget' => 'choice',
            'html5' => false,
            'years' => range(2000,2020)
        ])
        ->add('numero_parent', TextType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])
        ->add('photo', FileType::class, [
            "attr" => [
                "class" => "form-control"
            ],
            "required" => false
        ])
        ->add('montant', NumberType::class, [
            "attr" => [
                "class" => "form-control"
            ]
        ])
        ->add('Inscrire', SubmitType::class, [
            "attr" => [
                "class" => "btn btn-primary"
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }

    public function random($car) {
        $string = "";
        $chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
        $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
        }
        
}
