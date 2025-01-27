#[ORM\Entity]
class Navire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $origine = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateArrivee = null;

    #[ORM\OneToMany(targetEntity: Vehicule::class, mappedBy: "navire")]
    private Collection $vehicules;
} 